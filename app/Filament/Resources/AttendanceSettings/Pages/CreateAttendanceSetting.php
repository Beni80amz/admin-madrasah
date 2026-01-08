<?php

namespace App\Filament\Resources\AttendanceSettings\Pages;

use App\Filament\Resources\AttendanceSettings\AttendanceSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendanceSetting extends CreateRecord
{
    protected static string $resource = AttendanceSettingResource::class;
}
