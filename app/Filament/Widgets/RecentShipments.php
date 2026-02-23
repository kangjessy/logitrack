<?php

namespace App\Filament\Widgets;

use App\Models\Shipment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentShipments extends BaseWidget
{
    protected static ?string $heading = 'Monitoring Pengiriman Terbaru';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 9;

    public function table(Table $table): Table
    {
        return $table
            ->query(
            Shipment::query()->latest()->limit(5)
        )
            ->columns([
            Tables\Columns\TextColumn::make('unit.vin_number')
            ->label('VIN')
            ->searchable(),
            Tables\Columns\TextColumn::make('origin.name')
            ->label('Asal'),
            Tables\Columns\TextColumn::make('dealer.name')
            ->label('Tujuan'),
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
            Tables\Columns\TextColumn::make('updated_at')
            ->label('Update Terakhir')
            ->dateTime()
            ->since(),
        ])
            ->actions([
            Tables\Actions\Action::make('view')
            ->label('Detail')
            ->icon('heroicon-m-eye')
            ->url(fn(Shipment $record): string => "/admin/shipments/{$record->id}/edit"),
        ]);
    }
}