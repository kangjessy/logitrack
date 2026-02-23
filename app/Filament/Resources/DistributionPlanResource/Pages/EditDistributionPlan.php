<?php

namespace App\Filament\Resources\DistributionPlanResource\Pages;

use App\Filament\Resources\DistributionPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDistributionPlan extends EditRecord
{
    protected static string $resource = DistributionPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
