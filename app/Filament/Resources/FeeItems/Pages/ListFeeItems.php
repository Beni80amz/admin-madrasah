<?php

namespace App\Filament\Resources\FeeItems\Pages;

use App\Filament\Resources\FeeItems\FeeItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeeItems extends ListRecords
{
    protected static string $resource = FeeItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Item Biaya')
                ->icon('heroicon-o-plus'),
        ];
    }
}
