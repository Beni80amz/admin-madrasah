<?php

namespace App\Filament\Resources\OperationalHours\Pages;

use App\Filament\Resources\OperationalHours\OperationalHourResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOperationalHours extends ListRecords
{
    protected static string $resource = OperationalHourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
