<?php

namespace App\Filament\Resources\WarehouseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\TextInput::make('vin_number')
            ->required()
            ->maxLength(255),
            Forms\Components\TextInput::make('model_name')
            ->required()
            ->maxLength(255),
            Forms\Components\Select::make('status')
            ->options([
                'available' => 'Available',
                'booked' => 'Booked',
                'in_transit' => 'In Transit',
                'delivered' => 'Delivered',
            ])
            ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('vin_number')
            ->columns([
            Tables\Columns\TextColumn::make('vin_number')
            ->label('VIN')
            ->searchable()
            ->fontFamily('mono')
            ->weight('bold'),
            Tables\Columns\TextColumn::make('model_name')
            ->label('Model')
            ->searchable(),
            Tables\Columns\TextColumn::make('status')
            ->badge()
            ->color(fn(string $state): string => match ($state) {
            'available' => 'success',
            'booked' => 'warning',
            'in_transit' => 'info',
            'delivered' => 'gray',
            default => 'primary',
        }),
            Tables\Columns\TextColumn::make('created_at')
            ->label('Entry Date')
            ->dateTime()
            ->sortable(),
        ])
            ->filters([
            Tables\Filters\SelectFilter::make('status')
            ->options([
                'available' => 'Available',
                'booked' => 'Booked',
                'in_transit' => 'In Transit',
                'delivered' => 'Delivered',
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