<?php

namespace App\Filament\Business\Resources\QaReviewResource\Pages;

use App\Filament\Business\Resources\QaReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQaReview extends EditRecord
{
    protected static string $resource = QaReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
