<?php

namespace App\Filament\Resources\FeeItems\Pages;

use App\Filament\Resources\FeeItems\FeeItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFeeItem extends EditRecord
{
    protected static string $resource = FeeItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
