<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Warehouse;
use App\Models\Unit;

class WarehouseStock extends ChartWidget
{
    protected static ?string $heading = 'Stock Levels per Warehouse';
    protected static ?int $sort = 6;

    protected function getData(): array
    {
        $warehouses = Warehouse::withCount(['units' => function ($query) {
            $query->where('status', 'available');
        }])->get();

        return [
            'datasets' => [
                [
                    'label' => 'Units Available',
                    'data' => $warehouses->pluck('units_count')->toArray(),
                    'backgroundColor' => '#8b5cf6', // Violet
                ],
            ],
            'labels' => $warehouses->pluck('name')->map(fn($name) => \Illuminate\Support\Str::limit($name, 20))->toArray(),
        ];
    }

    protected int|string|array $columnSpan = 1;
    protected static ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                ],
                'y' => [
                    'ticks' => [
                        'autoSkip' => false,
                    ],
                ],
            ],
        ];
    }
}