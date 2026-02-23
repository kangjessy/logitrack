<?php

namespace App\Filament\Resources\DistributionPlanResource\Pages;

use App\Filament\Resources\DistributionPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDistributionPlans extends ListRecords
{
    protected static string $resource = DistributionPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DistributionPlanResource\Widgets\DistributionPlanStats::class ,
        ];
    }
}