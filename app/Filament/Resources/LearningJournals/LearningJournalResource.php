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
            $user->hasRole('Guru');
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user->hasAnyRole(['super_admin', 'admin', 'Superadmin', 'Admin', 'Kurikulum', 'kepala_sekolah', 'Kepala Sekolah'])) {
            return $query;
        }

        $teacher = $user->teacher;
        if (!$teacher) {
            return $query->whereRaw('1 = 0');
        }

        $isGuruKelas = $teacher->jabatan?->nama === 'Guru Kelas' || \App\Models\Rombel::where('wali_kelas_id', $teacher->id)->exists();

        if ($isGuruKelas) {
            // Guru Kelas sees journals for their Rombel
            $rombelId = $teacher->rombel_id ?? \App\Models\Rombel::where('wali_kelas_id', $teacher->id)->value('id');
            return $query->where('rombel_id', $rombelId);
        }

        // Guru Mata Pelajaran sees only their own journals
        return $query->where('user_id', $user->id);
    }

    public static function form(Schema $schema): Schema
    {
        return LearningJournalForm::configure($schema);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return LearningJournalTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            // Widgets\LJInstructions::class,
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
