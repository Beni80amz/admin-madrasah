<?php

namespace App\Filament\Resources\SiswaMasuks\Pages;

use App\Filament\Resources\SiswaMasuks\SiswaMasukResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSiswaMasuks extends ListRecords
{
    protected static string $resource = SiswaMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Siswa Masuk'),
        ];
    }
}
