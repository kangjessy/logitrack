<?php

namespace App\Filament\Widgets;

use App\Models\Shipment;
use App\Models\Dealer;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RegionalDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Sebaran Distribusi Per Wilayah';
    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '300px';
    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $data = DB::table('shipments')
            ->join('dealers', 'shipments.dealer_id', '=', 'dealers.id')
            ->select('dealers.region', DB::raw('count(*) as total'))
            ->whereYear('shipments.created_at', now()->year)
            ->groupBy('dealers.region')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Units',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                ],
            ],
            'labels' => $data->pluck('region')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}