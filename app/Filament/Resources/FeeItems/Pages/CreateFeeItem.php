<?php

namespace App\Filament\Resources\FeeItems\Pages;

use App\Filament\Resources\FeeItems\FeeItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeeItem extends CreateRecord
{
    protected static string $resource = FeeItemResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
