<?php

namespace App\Filament\Resources\SiswaKeluars;

use App\Filament\Resources\SiswaKeluars\Pages\CreateSiswaKeluar;
use App\Filament\Resources\SiswaKeluars\Pages\EditSiswaKeluar;
use App\Filament\Resources\SiswaKeluars\Pages\ListSiswaKeluars;
use App\Filament\Resources\SiswaKeluars\Schemas\SiswaKeluarForm;
use App\Filament\Resources\SiswaKeluars\Tables\SiswaKeluarsTable;
use App\Models\SiswaKeluar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SiswaKeluarResource extends Resource
{
    protected static ?string $model = SiswaKeluar::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowRightStartOnRectangle;

    protected static UnitEnum|string|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Siswa Keluar';

    protected static ?string $modelLabel = 'Siswa Keluar';

    protected static ?string $pluralModelLabel = 'Siswa Keluar';

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    public static function form(Schema $schema): Schema
    {
        return SiswaKeluarForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiswaKeluarsTable::configure($table);
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
            'index' => ListSiswaKeluars::route('/'),
            'create' => CreateSiswaKeluar::route('/create'),
            'edit' => EditSiswaKeluar::route('/{record}/edit'),
        ];
    }
}
