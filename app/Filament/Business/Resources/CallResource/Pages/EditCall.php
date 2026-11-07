<?php

namespace App\Filament\Business\Resources\CallResource\Pages;

use App\Filament\Business\Resources\CallResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCall extends EditRecord
{
    protected static string $resource = CallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
