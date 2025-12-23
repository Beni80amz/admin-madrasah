<?php

namespace App\Filament\Resources\ProfileMadrasahs\Pages;

use App\Filament\Resources\ProfileMadrasahs\ProfileMadrasahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProfileMadrasahs extends ListRecords
{
    protected static string $resource = ProfileMadrasahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
