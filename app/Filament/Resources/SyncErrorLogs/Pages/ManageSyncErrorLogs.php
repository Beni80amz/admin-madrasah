<?php

namespace App\Filament\Resources\SyncErrorLogs\Pages;

use App\Filament\Resources\SyncErrorLogs\SyncErrorLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSyncErrorLogs extends ManageRecords
{
    protected static string $resource = SyncErrorLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
