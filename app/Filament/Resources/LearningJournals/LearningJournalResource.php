<?php

namespace App\Filament\Resources\LearningJournals;

use App\Filament\Resources\LearningJournals\Pages\CreateLearningJournal;
use App\Filament\Resources\LearningJournals\Pages\EditLearningJournal;
use App\Filament\Resources\LearningJournals\Pages\ListLearningJournals;
use App\Filament\Resources\LearningJournals\Schemas\LearningJournalForm;
use App\Filament\Resources\LearningJournals\Tables\LearningJournalTable;
use App\Models\LearningJournal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class LearningJournalResource extends Resource
{
    protected static ?string $model = LearningJournal::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Jurnal Pembelajaran';

    protected static ?string $pluralLabel = 'Jurnal Pembelajaran';

    protected static ?string $modelLabel = 'Jurnal Pembelajaran';

    protected static UnitEnum|string|null $navigationGroup = 'Akademik';

    protected static ?string $recordTitleAttribute = 'date';

    public static function canAccess(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        return $user->hasRole('Superadmin') ||
            $user->hasRole('super_admin') ||
            $user->hasRole('teacher');
    }

    public static function form(Schema $schema): Schema
    {
        return LearningJournalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LearningJournalTable::configure($table);
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
            'index' => ListLearningJournals::route('/'),
            'create' => CreateLearningJournal::route('/create'),
            'edit' => EditLearningJournal::route('/{record}/edit'),
        ];
    }
}
