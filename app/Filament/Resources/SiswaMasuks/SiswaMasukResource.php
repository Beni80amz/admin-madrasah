<?php

namespace App\Filament\Resources\SiswaMasuks;

use App\Filament\Resources\SiswaMasuks\Pages\CreateSiswaMasuk;
use App\Filament\Resources\SiswaMasuks\Pages\EditSiswaMasuk;
use App\Filament\Resources\SiswaMasuks\Pages\ListSiswaMasuks;
use App\Filament\Resources\SiswaMasuks\Schemas\SiswaMasukForm;
use App\Filament\Resources\SiswaMasuks\Tables\SiswaMasuksTable;
use App\Models\SiswaMasuk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SiswaMasukResource extends Resource
{
    protected static ?string $model = SiswaMasuk::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowLeftEndOnRectangle;

    protected static UnitEnum|string|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Siswa Masuk';

    protected static ?string $modelLabel = 'Siswa Masuk';

    protected static ?string $pluralModelLabel = 'Siswa Masuk';

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    public static function form(Schema $schema): Schema
    {
        return SiswaMasukForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiswaMasuksTable::configure($table);
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
            'index' => ListSiswaMasuks::route('/'),
            'create' => CreateSiswaMasuk::route('/create'),
            'edit' => EditSiswaMasuk::route('/{record}/edit'),
        ];
    }
}
