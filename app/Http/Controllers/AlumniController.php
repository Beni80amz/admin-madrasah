<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\ProfileMadrasah;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;

class AlumniController extends Controller
{
    public function downloadPdf()
    {
        $siteProfile = ProfileMadrasah::first();
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();

        $alumni = Alumni::orderBy('tahun_lulus', 'desc')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        $total = $alumni->count();

        // Group by tahun lulus for statistics
        $byYear = $alumni->groupBy('tahun_lulus')
            ->map(function ($items) {
                return $items->count();
            })
            ->sortKeysDesc();

        $data = [
            'siteProfile' => $siteProfile,
            'tahunAjaran' => $tahunAjaran,
            'alumni' => $alumni,
            'total' => $total,
            'byYear' => $byYear,
            'qrCodeImage' => 'data:image/png;base64,' . base64_encode(app(\App\Services\QrCodeService::class)->generateDocumentVerificationQrCode()),
        ];

        $pdf = Pdf::loadView('pdf.alumni', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions(['isRemoteEnabled' => true]);

        $filename = 'Data-Alumni-' . ($siteProfile->nama_madrasah ?? 'Madrasah') . '.pdf';
        $filename = str_replace(['/', '\\'], '-', $filename);

        return $pdf->download($filename);
    }
}
