<?php

namespace App\Filament\Resources\DealerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShipmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'shipments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Select::make('unit_id')
            ->relationship('unit', 'vin_number')
            ->searchable()
            ->required(),
            Forms\Components\Select::make('status')
            ->options([
                'pending' => 'Pending',
                'dispatched' => 'Dispatched',
                'on_progress' => 'On Progress',
                'delivered' => 'Delivered',
                'delayed' => 'Delayed',
            ])
            ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
            Tables\Columns\TextColumn::make('unit.vin_number')
            ->label('Unit VIN')
            ->searchable()
            ->fontFamily('mono'),
            Tables\Columns\TextColumn::make('status')
            ->badge()
            ->color(fn(string $state): string => match ($state) {
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
            ->label('Arrival')
            ->dateTime()
            ->sortable(),
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
        ])
            ->headerActions([
            Tables\Actions\CreateAction::make(),
        ])
            ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
            ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }
}