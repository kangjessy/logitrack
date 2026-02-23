<?php

namespace App\Filament\Resources\DealerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\DistributionPlan;

class DistributionPlansRelationManager extends RelationManager
{
    protected static string $relationship = 'distributionPlans';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\TextInput::make('month')
            ->placeholder('2026-03')
            ->required(),
            Forms\Components\TextInput::make('target_quantity')
            ->label('Target Qty')
            ->numeric()
            ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('month')
            ->columns([
            Tables\Columns\TextColumn::make('month')
            ->label('Period')
            ->sortable(),
            Tables\Columns\TextColumn::make('target_quantity')
            ->label('Target')
            ->numeric()
            ->sortable(),
            Tables\Columns\TextColumn::make('realization')
            ->label('Realization')
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

            if ($record->target_quantity == 0)
                return '0%';
            $percent = round(($realization / $record->target_quantity) * 100, 1);
            return $percent . '%';
        })
            ->badge()
            ->color(fn(string $state): string => match (true) {
            (float)$state >= 100 => 'success',
            (float)$state >= 80 => 'warning',
            default => 'danger',
        }),
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