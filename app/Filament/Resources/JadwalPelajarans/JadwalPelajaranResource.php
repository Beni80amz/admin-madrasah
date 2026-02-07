<?php

namespace App\Filament\Resources\JadwalPelajarans;

use App\Filament\Resources\JadwalPelajarans\Pages\CreateJadwalPelajaran;
use App\Filament\Resources\JadwalPelajarans\Pages\EditJadwalPelajaran;
use App\Filament\Resources\JadwalPelajarans\Pages\ListJadwalPelajarans;
use App\Filament\Resources\JadwalPelajarans\Pages\ManageJadwal;
use App\Filament\Resources\JadwalPelajarans\Schemas\JadwalPelajaranForm;
use App\Filament\Resources\JadwalPelajarans\Tables\JadwalPelajaransTable;
use App\Models\JadwalPelajaran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class JadwalPelajaranResource extends Resource
{
    protected static ?string $model = JadwalPelajaran::class;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasAnyRole(['super_admin', 'admin', 'Superadmin', 'Admin', 'kepala_sekolah', 'Kepala Sekolah', 'Kurikulum', 'Guru', 'teacher']));
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static UnitEnum|string|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationLabel = 'Jadwal Pelajaran';

    protected static ?string $modelLabel = 'Jadwal Pelajaran';

    protected static ?string $pluralModelLabel = 'Jadwal Pelajaran';

    public static function getAllowedRombelIds(\App\Models\User $user): ?\Illuminate\Support\Collection
    {
        if ($user->hasAnyRole(['super_admin', 'admin', 'Superadmin', 'Admin', 'Kurikulum', 'kepala_sekolah', 'Kepala Sekolah'])) {
            return null;
        }

        $teacher = $user->teacher;
        if (!$teacher) {
            return collect();
        }

        $rombelIds = collect();

        // 1. Wali Kelas Logic
        if ($teacher->rombel_id) {
            $rombelIds->push($teacher->rombel_id);
        }

        // 2. Guru Mata Pelajaran Logic (Fetch rombels from existing schedule)
        $scheduleRombels = \App\Models\JadwalPelajaran::where('teacher_id', $teacher->id)
            ->pluck('rombel_id')
            ->unique();

        return $rombelIds->merge($scheduleRombels)->unique();
    }

    public static function form(Schema $schema): Schema
    {
        return JadwalPelajaranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JadwalPelajaransTable::configure($table);
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
            'index' => ListJadwalPelajarans::route('/'),
            'create' => CreateJadwalPelajaran::route('/create'),
            'edit' => EditJadwalPelajaran::route('/{record}/edit'),
            'manage' => ManageJadwal::route('/manage'),
        ];
    }
}
