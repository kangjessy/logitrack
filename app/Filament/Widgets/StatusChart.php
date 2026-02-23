<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class StatusChart extends ChartWidget
{
    protected static ?string $heading = 'Shipment Status Pulse';
    protected static ?int $sort = 4;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = [
            'Pending' => \App\Models\Shipment::where('status', 'pending')->count(),
            'Dispatched' => \App\Models\Shipment::where('status', 'dispatched')->count(),
            'On Progress' => \App\Models\Shipment::where('status', 'on_progress')->count(),
            'Delivered' => \App\Models\Shipment::where('status', 'delivered')->count(),
            'Delayed' => \App\Models\Shipment::where('status', 'delayed')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Total Unit',
                    'data' => array_values($data),
                    'backgroundColor' => [
                        '#9ca3af', // Gray (Pending)
                        '#60a5fa', // Light Blue (Dispatched)
                        '#3b82f6', // Blue (On Progress)
                        '#10b981', // Emerald (Delivered)
                        '#ef4444', // Red (Delayed)
                    ],
                    'hoverOffset' => 4
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'cutout' => '70%',
        ];
    }
}