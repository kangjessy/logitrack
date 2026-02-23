<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShipmentResource\Pages;
use App\Filament\Resources\ShipmentResource\RelationManagers;
use App\Models\Shipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Main Operations';
    protected static ?string $recordTitleAttribute = 'unit.vin_number';

    public static function getGloballySearchableAttributes(): array
    {
        return ['unit.vin_number', 'dealer.name'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('unit_id')
                    ->relationship('unit', 'model_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('dealer_id')
                    ->relationship('dealer', 'name')
                    ->label('Dealer Tujuan')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('origin_id')
                    ->relationship('origin', 'name')
                    ->label('Origin Warehouse (Gudang Asal)')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'dispatched' => 'Dispatched',
                        'on_progress' => 'On Progress',
                        'delivered' => 'Delivered',
                        'delayed' => 'Delayed',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\DateTimePicker::make('dispatched_at'),
                Forms\Components\DateTimePicker::make('arrived_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('unit.vin_number')
                    ->label('VIN')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('origin.name')
                    ->label('Asal Gudang')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('dealer.name')
                    ->label('Tujuan Dealer')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'dispatched' => 'info',
                        'on_progress' => 'info',
                        'delivered' => 'success',
                        'delayed' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('dispatched_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('arrived_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lead_time_in_days')
                    ->label('Lead Time')
                    ->state(function ($record) {
                        return $record->lead_time_in_days !== null 
                            ? $record->lead_time_in_days . ' Days' 
                            : '-';
                    })
                    ->color(function ($record) {
                        if ($record->lead_time_in_days !== null && $record->lead_time_in_days > 3) {
                            return 'danger'; // Warning color if lead time is > 3 days
                        }
                        return 'success';
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
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'dispatched' => 'Dispatched',
                        'on_progress' => 'On Progress',
                        'delivered' => 'Delivered',
                        'delayed' => 'Delayed',
                    ]),
                Tables\Filters\Filter::make('dispatched_at')
                    ->form([
                        Forms\Components\DatePicker::make('dispatched_from'),
                        Forms\Components\DatePicker::make('dispatched_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dispatched_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('dispatched_at', '>=', $date),
                            )
                            ->when(
                                $data['dispatched_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('dispatched_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    \pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction::make(),
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
            ShipmentResource\Widgets\ShipmentStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShipments::route('/'),
            'create' => Pages\CreateShipment::route('/create'),
            'edit' => Pages\EditShipment::route('/{record}/edit'),
        ];
    }
}