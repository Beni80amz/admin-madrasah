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
        $user = auth()->user();
        if ($user && $user->hasAnyRole(['super_admin', 'admin', 'Superadmin', 'Admin', 'Kurikulum'])) {
            return 'Siswa';
        }

        return 'Siswa Saya';
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        $allowedKelas = static::getAllowedKelasStrings($user);

        if ($allowedKelas !== null) {
            if ($allowedKelas->isEmpty()) {
                return $query->whereRaw('1 = 0');
            }
            $query->whereIn('kelas', $allowedKelas);
        }

        return $query;
    }

    public static function getAllowedKelasStrings(\App\Models\User $user): ?\Illuminate\Support\Collection
    {
        // If admin/kurikulum/kepala sekolah, return null meaning all classes allowed
        if ($user->hasAnyRole(['super_admin', 'admin', 'Superadmin', 'Admin', 'Kurikulum', 'kepala_sekolah', 'Kepala Sekolah'])) {
            return null;
        }

        $teacher = $user->teacher;
        if (!$teacher) {
            return collect(); // No teacher record, no students
        }

        $rombelIds = collect();

        // 1. Wali Kelas Logic
        $isGuruKelas = $teacher->jabatan?->nama === 'Guru Kelas' || \App\Models\Rombel::where('wali_kelas_id', $teacher->id)->exists();

        if ($isGuruKelas) {
            $rombelId = $teacher->rombel_id ?? \App\Models\Rombel::where('wali_kelas_id', $teacher->id)->value('id');
            if ($rombelId) {
                $rombelIds->push($rombelId);
            }
        } else {
            // 2. Guru Mata Pelajaran Logic: Fetch rombels from schedule
            $scheduleRombels = \App\Models\JadwalPelajaran::where('teacher_id', $teacher->id)
                ->pluck('rombel_id')
                ->unique();
            $rombelIds = $rombelIds->merge($scheduleRombels)->unique();
        }

        if ($rombelIds->isEmpty()) {
            return collect();
        }

        // Convert Rombel IDs to "tingkat-nama" format matching students.kelas column
        return \App\Models\Rombel::whereIn('id', $rombelIds)
            ->with('kelas')
            ->get()
            ->map(function ($rombel) {
                $tingkat = static::romanToArabic($rombel->kelas?->tingkat ?? '');
                return $tingkat . '-' . ($rombel->nama ?? '');
            })
            ->unique();
    }

    protected static function romanToArabic(string $roman): string
    {
        $romans = ['I' => 1, 'V' => 5, 'X' => 10, 'L' => 50, 'C' => 100];
        $roman = strtoupper(trim($roman));

        if (is_numeric($roman)) {
            return $roman;
        }

        $result = 0;
        $length = strlen($roman);
        for ($i = 0; $i < $length; $i++) {
            $current = $romans[$roman[$i]] ?? 0;
            $next = ($i + 1 < $length) ? ($romans[$roman[$i + 1]] ?? 0) : 0;

            if ($current < $next) {
                $result -= $current;
            } else {
                $result += $current;
            }
        }

        return $result > 0 ? (string) $result : $roman;
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

        // Guru can only edit if they are the Wali Kelas for this specific student's rombel (matched by 'kelas' string)
        if ($user->hasAnyRole(['Guru', 'teacher'])) {
            $teacher = $user->teacher;
            if (!$teacher) {
                return false;
            }

            // A Wali Kelas is a teacher whose ID is set as wali_kelas_id in a Rombel 
            // that matches the student's 'kelas' label.
            $isWaliKelas = \App\Models\Rombel::where('wali_kelas_id', $teacher->id)
                ->with('kelas')
                ->get()
                ->contains(function ($rombel) use ($record) {
                    $tingkat = static::romanToArabic($rombel->kelas?->tingkat ?? '');
                    $rombelKelasLabel = $tingkat . '-' . ($rombel->nama ?? '');
                    return $rombelKelasLabel === $record->kelas;
                });

            return $isWaliKelas;
        }

        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();
        return $user && $user->hasAnyRole(['super_admin', 'admin', 'Superadmin', 'Admin', 'Kurikulum']);
    }

    public static function canDeleteAny(): bool
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
