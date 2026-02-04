<?php

namespace App\Filament\Resources\FeeCategories\Pages;

use App\Filament\Resources\FeeCategories\FeeCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeeCategory extends CreateRecord
{
    protected static string $resource = FeeCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
