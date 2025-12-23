<?php

namespace App\Filament\Resources\SiswaMasuks\Pages;

use App\Filament\Resources\SiswaMasuks\SiswaMasukResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSiswaMasuk extends CreateRecord
{
    protected static string $resource = SiswaMasukResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
