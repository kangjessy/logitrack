<?php

namespace App\Filament\Widgets;

use App\Models\Shipment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class BottleneckDetector extends BaseWidget
{
    protected static ?string $heading = '🚨 Identifikasi Bottleneck & Keterlambatan';
    protected static ?int $sort = 8;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
            Shipment::query()
            ->where(function (Builder $query) {
            $query->where('status', 'delayed')
                ->orWhere(function (Builder $inner) {
                $inner->whereIn('status', ['dispatched', 'on_progress'])
                    ->where('dispatched_at', '<=', now()->subDays(3));
            }
            );
        })
        )
            ->columns([
            Tables\Columns\TextColumn::make('unit.vin_number')
            ->label('VIN Unit')
            ->fontFamily('mono')
            ->weight('bold')
            ->color('danger'),
            Tables\Columns\TextColumn::make('dealer.name')
            ->label('Destinasi Dealer'),
            Tables\Columns\TextColumn::make('status')
            ->badge()
            ->color('danger'),
            Tables\Columns\TextColumn::make('aging')
            ->label('Lama Perjalanan')
            ->state(fn($record) => $record->dispatched_at ? $record->dispatched_at->diffInDays(now()) . ' Hari' : '-')
            ->weight('bold')
            ->color('danger'),
            Tables\Columns\TextColumn::make('updated_at')
            ->label('Terakhir Update')
            ->since(),
        ])
            ->actions([
            Tables\Actions\Action::make('resolve')
            ->label('Intervensi')
            ->button()
            ->color('danger')
            ->url(fn(Shipment $record): string => "/admin/shipments/{$record->id}/edit"),
        ])
            ->emptyStateHeading('Distribusi Lancar')
            ->emptyStateDescription('Tidak ditemukan bottleneck atau pengiriman tertunda di atas 3 hari.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}