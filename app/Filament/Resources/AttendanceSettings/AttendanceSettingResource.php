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
use UnitEnum;

class AttendanceSettingResource extends Resource
{
    protected static ?string $model = AttendanceSetting::class;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('view_any_attendance_setting');
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static UnitEnum|string|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 20;

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
