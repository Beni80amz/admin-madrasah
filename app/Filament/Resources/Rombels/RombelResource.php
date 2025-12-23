<?php

namespace App\Filament\Resources\Rombels;

use App\Filament\Resources\Rombels\Pages\CreateRombel;
use App\Filament\Resources\Rombels\Pages\EditRombel;
use App\Filament\Resources\Rombels\Pages\ListRombels;
use App\Filament\Resources\Rombels\Schemas\RombelForm;
use App\Filament\Resources\Rombels\Tables\RombelsTable;
use App\Models\Rombel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RombelResource extends Resource
{
    protected static ?string $model = Rombel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static UnitEnum|string|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 7;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return RombelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RombelsTable::configure($table);
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
            'index' => ListRombels::route('/'),
            'create' => CreateRombel::route('/create'),
            'edit' => EditRombel::route('/{record}/edit'),
        ];
    }
}
