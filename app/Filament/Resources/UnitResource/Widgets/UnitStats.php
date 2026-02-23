<?php

namespace App\Filament\Resources\UnitResource\Widgets;

use App\Models\Unit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UnitStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Available Stock', Unit::where('status', 'available')->count())
            ->description('Ready to be booked')
            ->descriptionIcon('heroicon-m-check-badge')
            ->color('success'),
            Stat::make('In Transit', Unit::where('status', 'in_transit')->count())
            ->description('Units being shipped')
            ->descriptionIcon('heroicon-m-arrow-path')
            ->color('info'),
            Stat::make('Aging Stock (>7 Days)', Unit::where('status', 'available')
            ->where('created_at', '<=', now()->subDays(7))
            ->count())
            ->description('Needs immediate attention')
            ->descriptionIcon('heroicon-m-exclamation-triangle')
            ->color('danger'),
        ];
    }
}