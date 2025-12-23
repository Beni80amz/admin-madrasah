<?php

namespace App\Filament\Resources\OperationalHours;

use App\Filament\Resources\OperationalHours\Pages\CreateOperationalHour;
use App\Filament\Resources\OperationalHours\Pages\EditOperationalHour;
use App\Filament\Resources\OperationalHours\Pages\ListOperationalHours;
use App\Filament\Resources\OperationalHours\Schemas\OperationalHourForm;
use App\Filament\Resources\OperationalHours\Tables\OperationalHoursTable;
use App\Models\OperationalHour;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class OperationalHourResource extends Resource
{
    protected static ?string $model = OperationalHour::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static UnitEnum|string|null $navigationGroup = 'Data Pendukung';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'hari';

    public static function form(Schema $schema): Schema
    {
        return OperationalHourForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OperationalHoursTable::configure($table);
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
            'index' => ListOperationalHours::route('/'),
            'create' => CreateOperationalHour::route('/create'),
            'edit' => EditOperationalHour::route('/{record}/edit'),
        ];
    }
}
