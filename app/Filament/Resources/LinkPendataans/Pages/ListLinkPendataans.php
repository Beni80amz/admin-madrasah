<?php

namespace App\Filament\Resources\LinkPendataans\Pages;

use App\Filament\Resources\LinkPendataans\LinkPendataanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLinkPendataans extends ListRecords
{
    protected static string $resource = LinkPendataanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
