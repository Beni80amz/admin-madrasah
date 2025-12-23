<?php

namespace App\Filament\Resources\SiswaMasuks\Pages;

use App\Filament\Resources\SiswaMasuks\SiswaMasukResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSiswaMasuk extends EditRecord
{
    protected static string $resource = SiswaMasukResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
