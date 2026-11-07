<?php

namespace App\Filament\Business\Resources\ContactResource\Pages;

use App\Filament\Business\Resources\ContactResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContact extends CreateRecord
{
    protected static string $resource = ContactResource::class;
}
