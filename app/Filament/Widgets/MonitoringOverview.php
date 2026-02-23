<?php

namespace App\Filament\Widgets;

use App\Models\Shipment;
use App\Models\DistributionPlan;
use App\Models\Unit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class MonitoringOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Calculate average lead time
        $avgLeadTime = Shipment::where('status', 'delivered')
            ->whereNotNull('dispatched_at')
            ->whereNotNull('arrived_at')
            ->get()
            ->avg(function ($shipment) {
            return Carbon::parse($shipment->dispatched_at)->diffInDays(Carbon::parse($shipment->arrived_at));
        });

        // Current realization %
        $month = now()->format('Y-m');
        $totalTarget = DistributionPlan::where('month', $month)->sum('target_quantity');
        $realization = Shipment::where('status', 'delivered')
            ->whereYear('arrived_at', now()->year)
            ->whereMonth('arrived_at', now()->month)
            ->count();
        $progress = $totalTarget > 0 ? round(($realization / $totalTarget) * 100, 1) : 0;

        // Bottleneck awareness: Delayed shipments
        $delayedCount = Shipment::where('status', 'delayed')->count();

        return [
            Stat::make('Avg. Lead Time', round($avgLeadTime ?? 0, 1) . ' Days')
            ->description('Speed from dispatch to dealer')
            ->descriptionIcon('heroicon-m-bolt')
            ->color($avgLeadTime > 3 ? 'danger' : 'success'),
            Stat::make('Monthly Realization', $progress . '%')
            ->description($realization . ' of ' . $totalTarget . ' units delivered')
            ->descriptionIcon('heroicon-m-chart-bar-square')
            ->chart([7, 10, 15, 12, 18, 20, $progress])
            ->color($progress >= 80 ? 'success' : 'warning'),
            Stat::make('Pending Bottlenecks', $delayedCount)
            ->description('Total delayed/stuck shipments')
            ->descriptionIcon('heroicon-m-exclamation-triangle')
            ->color($delayedCount > 0 ? 'danger' : 'gray'),
        ];
    }
}