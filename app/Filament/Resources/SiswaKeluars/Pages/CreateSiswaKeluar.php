<?php

namespace App\Filament\Resources\SiswaKeluars\Pages;

use App\Filament\Resources\SiswaKeluars\SiswaKeluarResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSiswaKeluar extends CreateRecord
{
    protected static string $resource = SiswaKeluarResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
