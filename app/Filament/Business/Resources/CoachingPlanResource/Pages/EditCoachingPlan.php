<?php

namespace App\Filament\Business\Resources\CoachingPlanResource\Pages;

use App\Filament\Business\Resources\CoachingPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoachingPlan extends EditRecord
{
    protected static string $resource = CoachingPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
