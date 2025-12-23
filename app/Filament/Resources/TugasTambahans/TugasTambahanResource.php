<?php

namespace App\Filament\Resources\TugasTambahans;

use App\Filament\Resources\TugasTambahans\Pages\CreateTugasTambahan;
use App\Filament\Resources\TugasTambahans\Pages\EditTugasTambahan;
use App\Filament\Resources\TugasTambahans\Pages\ListTugasTambahans;
use App\Filament\Resources\TugasTambahans\Schemas\TugasTambahanForm;
use App\Filament\Resources\TugasTambahans\Tables\TugasTambahansTable;
use App\Models\TugasTambahan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TugasTambahanResource extends Resource
{
    protected static ?string $model = TugasTambahan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static UnitEnum|string|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return TugasTambahanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TugasTambahansTable::configure($table);
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
            'index' => ListTugasTambahans::route('/'),
            'create' => CreateTugasTambahan::route('/create'),
            'edit' => EditTugasTambahan::route('/{record}/edit'),
        ];
    }
}
