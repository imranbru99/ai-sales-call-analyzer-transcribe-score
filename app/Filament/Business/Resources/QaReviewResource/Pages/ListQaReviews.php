<?php

namespace App\Filament\Business\Resources\QaReviewResource\Pages;

use App\Filament\Business\Resources\QaReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQaReviews extends ListRecords
{
    protected static string $resource = QaReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
