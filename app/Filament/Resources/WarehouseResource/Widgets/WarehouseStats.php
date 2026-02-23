<?php

namespace App\Filament\Resources\WarehouseResource\Widgets;

use App\Models\Warehouse;
use App\Models\Unit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WarehouseStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Warehouses', Warehouse::count())
            ->description('Active storage facilities')
            ->descriptionIcon('heroicon-m-home-modern')
            ->color('indigo'),
            Stat::make('Total Units in Stock', Unit::whereNotNull('warehouse_id')->count())
            ->description('Units across all locations')
            ->descriptionIcon('heroicon-m-cube')
            ->color('success'),
            Stat::make('Highest Capacity', Warehouse::withCount('units')->get()->max('units_count') ?? 0)
            ->description('Units in most crowded warehouse')
            ->descriptionIcon('heroicon-m-chart-pie')
            ->color('warning'),
        ];
    }
}