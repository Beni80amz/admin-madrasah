<?php

namespace App\Filament\Resources\AttendanceSettings\Pages;

use App\Filament\Resources\AttendanceSettings\AttendanceSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceSettings extends ListRecords
{
    protected static string $resource = AttendanceSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
