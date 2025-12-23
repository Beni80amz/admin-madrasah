<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Alumni;
use App\Models\Achievement;
use App\Models\Rombel;
use App\Models\PpdbRegistration;
use App\Models\SiswaKeluar;
use App\Models\SiswaMasuk;
use App\Models\TahunAjaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Count students by status (prefer status field, fallback to is_active)
        $totalStudents = Student::where('status', 'aktif')
            ->orWhere(function ($query) {
                $query->whereNull('status')->where('is_active', true);
            })
            ->count();

        $totalLakiLaki = Student::where(function ($query) {
            $query->where('status', 'aktif')
                ->orWhere(function ($q) {
                    $q->whereNull('status')->where('is_active', true);
                });
        })
            ->where('gender', 'Laki-laki')
            ->count();

        $totalPerempuan = Student::where(function ($query) {
            $query->where('status', 'aktif')
                ->orWhere(function ($q) {
                    $q->whereNull('status')->where('is_active', true);
                });
        })
            ->where('gender', 'Perempuan')
            ->count();

        $totalTeachers = Teacher::where('is_active', true)->count();
        $totalAlumni = Alumni::count();
        $totalAchievements = Achievement::count();
        $totalSiswaKeluar = SiswaKeluar::count();
        $totalSiswaMasuk = SiswaMasuk::count();

        // Hitung rombel hanya dari tahun ajaran aktif
        $activeTahunAjaran = TahunAjaran::getActive();
        $totalRombel = $activeTahunAjaran
            ? Rombel::where('tahun_ajaran_id', $activeTahunAjaran->id)
                ->where('nama', '!=', 'Alumni')
                ->count()
            : 0;

        return [
            Stat::make('Total Siswa Aktif', $totalStudents)
                ->description("L: {$totalLakiLaki} | P: {$totalPerempuan}")
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5]),

            Stat::make('Total Guru & Staff', $totalTeachers)
                ->description('Tenaga pendidik aktif')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info')
                ->chart([3, 5, 4, 3, 6, 5, 4]),

            Stat::make('Total Rombel', $totalRombel)
                ->description('Rombongan belajar')
                ->descriptionIcon('heroicon-m-rectangle-group')
                ->color('primary')
                ->chart([3, 3, 4, 4, 5, 5, 6]),

            Stat::make('Total Alumni', $totalAlumni)
                ->description('Lulusan madrasah')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning')
                ->chart([2, 4, 6, 5, 4, 3, 5]),

            Stat::make('Total Siswa Keluar', $totalSiswaKeluar)
                ->description('Siswa mutasi keluar')
                ->descriptionIcon('heroicon-m-arrow-right-start-on-rectangle')
                ->color('gray')
                ->chart([1, 2, 1, 3, 2, 1, 2]),

            Stat::make('Total Siswa Masuk', $totalSiswaMasuk)
                ->description('Siswa mutasi masuk')
                ->descriptionIcon('heroicon-m-arrow-left-end-on-rectangle')
                ->color('info')
                ->chart([2, 1, 3, 2, 1, 2, 3]),

            Stat::make('Total Prestasi', $totalAchievements)
                ->description('Pencapaian siswa & guru')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('danger')
                ->chart([1, 3, 2, 5, 3, 4, 6]),

            Stat::make('Total Siswa PPDB', PpdbRegistration::count())
                ->description('Pendaftar baru')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success')
                ->chart([2, 5, 3, 4, 6, 5, 7]),
        ];
    }
}

