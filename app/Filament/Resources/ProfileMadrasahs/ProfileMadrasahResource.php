<?php

namespace App\Filament\Resources\ProfileMadrasahs;

use App\Filament\Resources\ProfileMadrasahs\Pages\CreateProfileMadrasah;
use App\Filament\Resources\ProfileMadrasahs\Pages\EditProfileMadrasah;
use App\Filament\Resources\ProfileMadrasahs\Pages\ListProfileMadrasahs;
use App\Filament\Resources\ProfileMadrasahs\Schemas\ProfileMadrasahForm;
use App\Filament\Resources\ProfileMadrasahs\Tables\ProfileMadrasahsTable;
use App\Models\ProfileMadrasah;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProfileMadrasahResource extends Resource
{
    protected static ?string $model = ProfileMadrasah::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static UnitEnum|string|null $navigationGroup = 'Data Pendukung';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nama_madrasah';

    public static function form(Schema $schema): Schema
    {
        return ProfileMadrasahForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProfileMadrasahsTable::configure($table);
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
            'index' => ListProfileMadrasahs::route('/'),
            'create' => CreateProfileMadrasah::route('/create'),
            'edit' => EditProfileMadrasah::route('/{record}/edit'),
        ];
    }
}
