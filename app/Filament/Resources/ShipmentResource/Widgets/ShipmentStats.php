<?php

namespace App\Filament\Resources\ShipmentResource\Widgets;

use App\Models\Shipment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ShipmentStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pending Shipments', Shipment::where('status', 'pending')->count())
            ->description('Awaiting dispatch')
            ->descriptionIcon('heroicon-m-clock')
            ->color('gray'),
            Stat::make('In Transit', Shipment::whereIn('status', ['dispatched', 'on_progress'])->count())
            ->description('Currently on the road')
            ->descriptionIcon('heroicon-m-truck')
            ->color('info'),
            Stat::make('Delivered (Month)', Shipment::where('status', 'delivered')
            ->whereMonth('arrived_at', now()->month)
            ->whereYear('arrived_at', now()->year)
            ->count())
            ->description('Successfully completed')
            ->descriptionIcon('heroicon-m-check-circle')
            ->color('success'),
        ];
    }
}