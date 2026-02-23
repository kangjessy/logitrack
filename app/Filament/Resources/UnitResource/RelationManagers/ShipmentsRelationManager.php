<?php

namespace App\Filament\Resources\UnitResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ShipmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'shipments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Select::make('dealer_id')
            ->relationship('dealer', 'name')
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
            Tables\Columns\TextColumn::make('dealer.name')
            ->label('Destination Dealer')
            ->searchable(),
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
            ->label('Dispatched At')
            ->dateTime(),
            Tables\Columns\TextColumn::make('arrived_at')
            ->label('Arrived At')
            ->dateTime(),
        ])
            ->filters([
            //
        ])
            ->headerActions([
            Tables\Actions\CreateAction::make(),
        ])
            ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }
}