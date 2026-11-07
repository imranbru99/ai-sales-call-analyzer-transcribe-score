<?php

namespace App\Filament\Business\Resources\ScorecardResource\Pages;

use App\Filament\Business\Resources\ScorecardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScorecard extends EditRecord
{
    protected static string $resource = ScorecardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
