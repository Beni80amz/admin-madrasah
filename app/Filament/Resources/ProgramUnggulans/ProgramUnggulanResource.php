<?php

namespace App\Filament\Resources\ProgramUnggulans;

use App\Filament\Resources\ProgramUnggulans\Pages\CreateProgramUnggulan;
use App\Filament\Resources\ProgramUnggulans\Pages\EditProgramUnggulan;
use App\Filament\Resources\ProgramUnggulans\Pages\ListProgramUnggulans;
use App\Filament\Resources\ProgramUnggulans\Schemas\ProgramUnggulanForm;
use App\Filament\Resources\ProgramUnggulans\Tables\ProgramUnggulanTable;
use App\Models\ProgramUnggulan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProgramUnggulanResource extends Resource
{
    protected static ?string $model = ProgramUnggulan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static UnitEnum|string|null $navigationGroup = 'Data Pendukung';

    protected static ?string $navigationLabel = 'Program Unggulan';

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ProgramUnggulanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgramUnggulanTable::configure($table);
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
            'index' => ListProgramUnggulans::route('/'),
            'create' => CreateProgramUnggulan::route('/create'),
            'edit' => EditProgramUnggulan::route('/{record}/edit'),
        ];
    }
}
