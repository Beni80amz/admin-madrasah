<?php

namespace App\Filament\Resources\TugasTambahans\Pages;

use App\Filament\Resources\TugasTambahans\TugasTambahanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTugasTambahans extends ListRecords
{
    protected static string $resource = TugasTambahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
