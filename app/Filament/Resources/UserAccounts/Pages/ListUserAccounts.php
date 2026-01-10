<?php

namespace App\Filament\Resources\UserAccounts\Pages;

use App\Filament\Resources\UserAccounts\UserAccountResource;
use Filament\Resources\Pages\ListRecords;

class ListUserAccounts extends ListRecords
{
    protected static string $resource = UserAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Export action will be here later
        ];
    }
}
