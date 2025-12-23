<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\ProfileMadrasah;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;

class StrukturKurikulumController extends Controller
{
    public function downloadPdf()
    {
        $profile = ProfileMadrasah::first();
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();

        $subjectsA = MataPelajaran::where('is_active', true)
            ->where('kelompok', 'A-Wajib')
            ->orderBy('id')
            ->get();

        $subjectsB = MataPelajaran::where('is_active', true)
            ->where('kelompok', 'B-Pilihan')
            ->orderBy('id')
            ->get();

        $subjectsC = MataPelajaran::where('is_active', true)
            ->where('kelompok', 'C-Muatan Lokal')
            ->orderBy('id')
            ->get();

        $subjectsKokurikuler = MataPelajaran::where('is_active', true)
            ->where('kelompok', 'Kokurikuler')
            ->orderBy('id')
            ->get();

        $pdf = Pdf::loadView('pdf.struktur-kurikulum', [
            'profile' => $profile,
            'tahunAjaran' => $tahunAjaran,
            'subjectsA' => $subjectsA,
            'subjectsB' => $subjectsB,
            'subjectsC' => $subjectsC,
            'subjectsKokurikuler' => $subjectsKokurikuler,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'Struktur-Kurikulum-' . ($tahunAjaran->nama ?? 'Madrasah') . '.pdf';
        $filename = str_replace(['/', '\\'], '-', $filename);

        return $pdf->download($filename);
    }
}
