<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\AcademicCalendar as AcademicCalendarModel;
use App\Models\TahunAjaran;

#[Layout('components.layouts.public')]
class AcademicCalendar extends Component
{
    #[Title('Kalender Akademik')]
    public function render()
    {
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
        $tahunAjaranNama = $tahunAjaranAktif->nama ?? null;

        // Parse tahun dari nama tahun ajaran (format: 2025/2026)
        $tahunGanjil = null;
        $tahunGenap = null;
        if ($tahunAjaranNama && preg_match('/(\d{4})\/(\d{4})/', $tahunAjaranNama, $matches)) {
            $tahunGanjil = $matches[1]; // 2025
            $tahunGenap = $matches[2];  // 2026
        }

        // Get calendar events for active academic year
        $semesterGanjil = AcademicCalendarModel::where('tahun_ajaran', $tahunAjaranNama)
            ->where('semester', 'Ganjil')
            ->orderBy('tanggal_mulai')
            ->get();

        $semesterGenap = AcademicCalendarModel::where('tahun_ajaran', $tahunAjaranNama)
            ->where('semester', 'Genap')
            ->orderBy('tanggal_mulai')
            ->get();

        // Get hari efektif per semester
        $hariEfektifGanjil = $tahunAjaranAktif->hari_efektif_ganjil ?? 100;
        $hariEfektifGenap = $tahunAjaranAktif->hari_efektif_genap ?? 100;
        $totalHariEfektif = $hariEfektifGanjil + $hariEfektifGenap;

        return view('livewire.academic-calendar', [
            'semesterGanjil' => $semesterGanjil,
            'semesterGenap' => $semesterGenap,
            'totalHariEfektif' => $totalHariEfektif,
            'hariEfektifGanjil' => $hariEfektifGanjil,
            'hariEfektifGenap' => $hariEfektifGenap,
            'tahunGanjil' => $tahunGanjil,
            'tahunGenap' => $tahunGenap,
        ]);
    }
}
