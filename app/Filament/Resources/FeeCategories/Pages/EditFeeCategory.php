<?php

namespace App\Filament\Resources\FeeCategories\Pages;

use App\Filament\Resources\FeeCategories\FeeCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFeeCategory extends EditRecord
{
    protected static string $resource = FeeCategoryResource::class;

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
