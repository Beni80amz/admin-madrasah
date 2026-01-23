<?php

namespace App\Http\Controllers;

use App\Models\AcademicCalendar;
use App\Models\ProfileMadrasah;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;

class AcademicCalendarController extends Controller
{
    public function downloadPdf()
    {
        $siteProfile = ProfileMadrasah::first();
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();
        $tahunAjaranNama = $tahunAjaran->nama ?? null;

        $semesterGanjil = AcademicCalendar::where('tahun_ajaran', $tahunAjaranNama)
            ->where('semester', 'Ganjil')
            ->orderBy('tanggal_mulai')
            ->get();

        $semesterGenap = AcademicCalendar::where('tahun_ajaran', $tahunAjaranNama)
            ->where('semester', 'Genap')
            ->orderBy('tanggal_mulai')
            ->get();

        // Hari efektif per semester
        $hariEfektifGanjil = $tahunAjaran->hari_efektif_ganjil ?? 100;
        $hariEfektifGenap = $tahunAjaran->hari_efektif_genap ?? 100;

        $data = [
            'siteProfile' => $siteProfile,
            'tahunAjaran' => $tahunAjaran,
            'semesterGanjil' => $semesterGanjil,
            'semesterGenap' => $semesterGenap,
            'hariEfektifGanjil' => $hariEfektifGanjil,
            'hariEfektifGenap' => $hariEfektifGenap,
            'qrCodeImage' => 'data:image/png;base64,' . base64_encode(app(\App\Services\QrCodeService::class)->generateDocumentVerificationQrCode()),
        ];

        $pdf = Pdf::loadView('pdf.academic-calendar', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions(['isRemoteEnabled' => true]);

        $filename = 'Kalender-Akademik-' . ($tahunAjaran->nama ?? 'Madrasah') . '.pdf';
        $filename = str_replace(['/', '\\'], '-', $filename);

        return $pdf->download($filename);
    }
}
