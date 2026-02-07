<?php

namespace App\Filament\Resources\Students;

use App\Filament\Resources\Students\Pages\CreateStudent;
use App\Filament\Resources\Students\Pages\EditStudent;
use App\Filament\Resources\Students\Pages\ListStudents;
use App\Filament\Resources\Students\Schemas\StudentForm;
use App\Filament\Resources\Students\Tables\StudentsTable;
use App\Models\Student;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasAnyRole(['super_admin', 'admin', 'Superadmin', 'Admin', 'kepala_sekolah', 'Kepala Sekolah', 'Kurikulum', 'Guru', 'teacher']));
    }

    public static function getNavigationGroup(): ?string
    {
        $user = auth()->user();
        if ($user && $user->hasRole(['Guru', 'teacher'])) {
            return 'Administrasi Guru';
        }

        return 'Akademik';
    }

    public static function getNavigationLabel(): string
    {
        return 'Siswa Saya';
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // If not admin/kurikulum, filter by teacher assignments
        if ($user && $user->hasAnyRole(['Guru', 'teacher']) && !$user->hasAnyRole(['super_admin', 'admin', 'Superadmin', 'Admin', 'Kurikulum'])) {
            $teacher = $user->teacher;

            if (!$teacher) {
                return $query->whereRaw('1 = 0'); // No teacher record, no students
            }

            $rombelIds = collect();

            // 1. Wali Kelas Logic (Guru Kelas MI - ID 2)
            if ($teacher->tugas_pokok_id == 2 && $teacher->rombel_id) {
                $rombelIds->push($teacher->rombel_id);
            }

            // 2. Guru Mata Pelajaran Logic (ID 1)
            // Fetch rombels from schedule
            $scheduleRombels = \App\Models\JadwalPelajaran::where('teacher_id', $teacher->id)
                ->pluck('rombel_id')
                ->unique();

            $rombelIds = $rombelIds->merge($scheduleRombels)->unique();

            if ($rombelIds->isEmpty()) {
                return $query->whereRaw('1 = 0');
            }

            $query->whereIn('rombel_id', $rombelIds);
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        return $user && $user->hasAnyRole(['super_admin', 'admin', 'Superadmin', 'Admin', 'Kurikulum']);
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();

        // Admin/Kurikulum can always edit
        if ($user->hasAnyRole(['super_admin', 'admin', 'Superadmin', 'Admin', 'Kurikulum'])) {
            return true;
        }

        // Guru can only edit if they are the Wali Kelas for this specific student's rombel
        if ($user->hasAnyRole(['Guru', 'teacher'])) {
            $teacher = $user->teacher;
            return $teacher && $teacher->tugas_pokok_id == 2 && $teacher->rombel_id == $record->rombel_id;
        }

        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();
        return $user && $user->hasAnyRole(['super_admin', 'admin', 'Superadmin', 'Admin', 'Kurikulum']);
    }

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    public static function form(Schema $schema): Schema
    {
        return StudentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentsTable::configure($table);
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
            'index' => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
            'edit' => EditStudent::route('/{record}/edit'),
        ];
    }
}
