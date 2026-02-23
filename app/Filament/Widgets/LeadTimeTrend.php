<?php

namespace App\Filament\Widgets;

use App\Models\Shipment;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeadTimeTrend extends ChartWidget
{
    protected static ?string $heading = 'Tren Lead Time (Hari)';
    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '300px';
    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $data = Shipment::where('status', 'delivered')
            ->whereNotNull('dispatched_at')
            ->whereNotNull('arrived_at')
            ->select(
            DB::raw('DATE(arrived_at) as date'),
            DB::raw('AVG(DATEDIFF(arrived_at, dispatched_at)) as avg_days')
        )
            ->where('arrived_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Rata-rata Lead Time',
                    'data' => $data->pluck('avg_days')->toArray(),
                    'borderColor' => '#ef4444',
                    'fill' => false,
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}