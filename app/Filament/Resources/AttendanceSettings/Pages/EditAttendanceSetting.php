<?php

namespace App\Filament\Resources\AttendanceSettings\Pages;

use App\Filament\Resources\AttendanceSettings\AttendanceSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAttendanceSetting extends EditRecord
{
    protected static string $resource = AttendanceSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
