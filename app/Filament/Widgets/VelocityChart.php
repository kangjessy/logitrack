<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Shipment;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class VelocityChart extends ChartWidget
{
    protected static ?string $heading = 'Daily Distribution Velocity (Last 7 Days)';

    protected static ?int $sort = 7;
    protected int|string|array $columnSpan = 1;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Calculate units delivered per day for the last 7 days
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d M');
            $data[] = Shipment::query()
                ->where('status', 'delivered')
                ->whereDate('arrived_at', $date->toDateString())
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Units Delivered',
                    'data' => $data,
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'borderColor' => '#10b981',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}