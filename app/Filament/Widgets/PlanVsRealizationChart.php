<?php

namespace App\Filament\Widgets;

use App\Models\Dealer;
use App\Models\DistributionPlan;
use App\Models\Shipment;
use Filament\Widgets\ChartWidget;

class PlanVsRealizationChart extends ChartWidget
{
    protected static ?string $heading = 'Rencana vs Realisasi (Per Dealer)';
    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '300px';
    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $dealers = Dealer::limit(10)->get();
        $month = now()->format('Y-m');

        $labels = $dealers->pluck('name')->toArray();
        $targets = [];
        $actuals = [];

        foreach ($dealers as $dealer) {
            $targets[] = DistributionPlan::where('dealer_id', $dealer->id)
                ->where('month', $month)
                ->sum('target_quantity');

            $actuals[] = Shipment::where('dealer_id', $dealer->id)
                ->where('status', 'delivered')
                ->whereYear('arrived_at', now()->year)
                ->whereMonth('arrived_at', now()->month)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Target Rencana',
                    'data' => $targets,
                    'backgroundColor' => '#94a3b8', // slate-400
                ],
                [
                    'label' => 'Realisasi Unit',
                    'data' => $actuals,
                    'backgroundColor' => '#6366f1', // indigo-500
                ],
            ],
            'labels' => collect($labels)->map(fn($label) => \Illuminate\Support\Str::limit($label, 15))->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'ticks' => [
                        'maxRotation' => 45,
                        'minRotation' => 0,
                        'autoSkip' => true,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}