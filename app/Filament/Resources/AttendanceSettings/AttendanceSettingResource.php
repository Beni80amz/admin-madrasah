<?php

namespace App\Filament\Resources\AttendanceSettings;

use App\Filament\Resources\AttendanceSettings\Pages\CreateAttendanceSetting;
use App\Filament\Resources\AttendanceSettings\Pages\EditAttendanceSetting;
use App\Filament\Resources\AttendanceSettings\Pages\ListAttendanceSettings;
use App\Filament\Resources\AttendanceSettings\Schemas\AttendanceSettingForm;
use App\Filament\Resources\AttendanceSettings\Tables\AttendanceSettingsTable;
use App\Models\AttendanceSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AttendanceSettingResource extends Resource
{
    protected static ?string $model = AttendanceSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'key';

    public static function form(Schema $schema): Schema
    {
        return AttendanceSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendanceSettingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendanceSettings::route('/'),
            'create' => CreateAttendanceSetting::route('/create'),
            'edit' => EditAttendanceSetting::route('/{record}/edit'),
        ];
    }
}
