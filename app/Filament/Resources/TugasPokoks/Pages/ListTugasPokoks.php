<?php

namespace App\Filament\Resources\TugasPokoks\Pages;

use App\Filament\Resources\TugasPokoks\TugasPokokResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTugasPokoks extends ListRecords
{
    protected static string $resource = TugasPokokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
