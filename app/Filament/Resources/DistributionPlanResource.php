<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DistributionPlanResource\Pages;
use App\Filament\Resources\DistributionPlanResource\RelationManagers;
use App\Models\DistributionPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DistributionPlanResource extends Resource
{
    protected static ?string $model = DistributionPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Main Operations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('dealer_id')
                    ->relationship('dealer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('month')
                    ->placeholder('2026-03')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('target_quantity')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dealer.name')
                    ->label('Dealer')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('dealer.region')
                    ->label('Wilayah')
                    ->sortable(),
                Tables\Columns\TextColumn::make('month')
                    ->label('Periode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_quantity')
                    ->label('Target')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('realization')
                    ->label('Realisasi')
                    ->state(function (DistributionPlan $record) {
                        return \App\Models\Shipment::where('dealer_id', $record->dealer_id)
                            ->where('status', 'delivered')
                            ->whereYear('arrived_at', substr($record->month, 0, 4))
                            ->whereMonth('arrived_at', substr($record->month, 5, 2))
                            ->count();
                    }),
                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress %')
                    ->state(function (DistributionPlan $record) {
                        $realization = \App\Models\Shipment::where('dealer_id', $record->dealer_id)
                            ->where('status', 'delivered')
                            ->whereYear('arrived_at', substr($record->month, 0, 4))
                            ->whereMonth('arrived_at', substr($record->month, 5, 2))
                            ->count();
                        
                        if ($record->target_quantity == 0) return '0%';
                        $percent = round(($realization / $record->target_quantity) * 100, 1);
                        return $percent . '%';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        (float)$state >= 100 => 'success',
                        (float)$state >= 80 => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('dealer_id')
                    ->relationship('dealer', 'name'),
                Tables\Filters\SelectFilter::make('month')
                    ->options(\App\Models\DistributionPlan::pluck('month', 'month')->unique()->toArray()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            DistributionPlanResource\Widgets\DistributionPlanStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDistributionPlans::route('/'),
            'create' => Pages\CreateDistributionPlan::route('/create'),
            'edit' => Pages\EditDistributionPlan::route('/{record}/edit'),
        ];
    }
}