<?php

namespace App\Filament\Resources\TugasPokoks;

use App\Filament\Resources\TugasPokoks\Pages\CreateTugasPokok;
use App\Filament\Resources\TugasPokoks\Pages\EditTugasPokok;
use App\Filament\Resources\TugasPokoks\Pages\ListTugasPokoks;
use App\Filament\Resources\TugasPokoks\Schemas\TugasPokokForm;
use App\Filament\Resources\TugasPokoks\Tables\TugasPokoksTable;
use App\Models\TugasPokok;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TugasPokokResource extends Resource
{
    protected static ?string $model = TugasPokok::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static UnitEnum|string|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return TugasPokokForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TugasPokoksTable::configure($table);
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
            'index' => ListTugasPokoks::route('/'),
            'create' => CreateTugasPokok::route('/create'),
            'edit' => EditTugasPokok::route('/{record}/edit'),
        ];
    }
}
