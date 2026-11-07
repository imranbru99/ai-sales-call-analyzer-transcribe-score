<?php

namespace App\Filament\Business\Resources\CallResource\Pages;

use App\Filament\Business\Resources\CallResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCalls extends ListRecords
{
    protected static string $resource = CallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
