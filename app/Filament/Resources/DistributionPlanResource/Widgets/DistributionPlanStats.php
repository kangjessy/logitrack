<?php

namespace App\Filament\Resources\DistributionPlanResource\Widgets;

use App\Models\DistributionPlan;
use App\Models\Shipment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DistributionPlanStats extends BaseWidget
{
    protected function getStats(): array
    {
        $month = now()->format('Y-m');
        $plans = DistributionPlan::where('month', $month)->get();

        $totalTarget = $plans->sum('target_quantity');

        $realization = Shipment::where('status', 'delivered')
            ->whereYear('arrived_at', now()->year)
            ->whereMonth('arrived_at', now()->month)
            ->count();

        $progress = $totalTarget > 0 ? round(($realization / $totalTarget) * 100, 1) : 0;

        return [
            Stat::make('Monthly Target', $totalTarget)
            ->description('Total units planned for ' . now()->format('F'))
            ->descriptionIcon('heroicon-m-flag')
            ->color('gray'),
            Stat::make('Total Realization', $realization)
            ->description('Units delivered this month')
            ->descriptionIcon('heroicon-m-shopping-cart')
            ->color('info'),
            Stat::make('Overall Progress', $progress . '%')
            ->description('Monthly performance vs plan')
            ->descriptionIcon('heroicon-m-chart-bar')
            ->color($progress >= 100 ? 'success' : ($progress >= 80 ? 'warning' : 'danger')),
        ];
    }
}