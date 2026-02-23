<?php

namespace App\Filament\Resources\DealerResource\Widgets;

use App\Models\Dealer;
use App\Models\DistributionPlan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DealerStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Dealers', Dealer::count())
            ->description('Registered business partners')
            ->descriptionIcon('heroicon-m-building-office-2')
            ->color('indigo'),
            Stat::make('Territories', Dealer::distinct('region')->count('region'))
            ->description('Unique distribution regions')
            ->descriptionIcon('heroicon-m-map')
            ->color('info'),
            Stat::make('Planned Supply', DistributionPlan::where('month', now()->format('Y-m'))->sum('target_quantity'))
            ->description('Total target units for ' . now()->format('M Y'))
            ->descriptionIcon('heroicon-m-clipboard-document-check')
            ->color('success'),
        ];
    }
}