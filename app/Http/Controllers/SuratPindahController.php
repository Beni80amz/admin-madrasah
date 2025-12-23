<?php

namespace App\Http\Controllers;

use App\Models\SiswaKeluar;
use App\Models\ProfileMadrasah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SuratPindahController extends Controller
{
    public function download($id)
    {
        $siswaKeluar = SiswaKeluar::findOrFail($id);
        $siteProfile = ProfileMadrasah::first();

        $data = [
            'siswaKeluar' => $siswaKeluar,
            'siteProfile' => $siteProfile,
        ];

        $pdf = Pdf::loadView('pdf.surat-pindah-keluar', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'Surat_Pindah_' . str_replace(' ', '_', $siswaKeluar->nama_lengkap) . '.pdf';

        return $pdf->download($filename);
    }

    public function stream($id)
    {
        $siswaKeluar = SiswaKeluar::findOrFail($id);
        $siteProfile = ProfileMadrasah::first();

        $data = [
            'siswaKeluar' => $siswaKeluar,
            'siteProfile' => $siteProfile,
        ];

        $pdf = Pdf::loadView('pdf.surat-pindah-keluar', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Surat_Pindah_' . str_replace(' ', '_', $siswaKeluar->nama_lengkap) . '.pdf');
    }
}
