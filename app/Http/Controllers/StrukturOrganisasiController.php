<?php

namespace App\Http\Controllers;

use App\Models\ProfileMadrasah;
use App\Models\StrukturOrganisasi;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;

class StrukturOrganisasiController extends Controller
{
    public function downloadPdf()
    {
        $profile = ProfileMadrasah::first();
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();

        // Get all struktur organisasi by level
        $strukturLevel0 = StrukturOrganisasi::with('teacher.rombelWaliKelas.kelas')
            ->active()
            ->where('level', 0)
            ->ordered()
            ->get();

        $strukturLevel1 = StrukturOrganisasi::with('teacher.rombelWaliKelas.kelas')
            ->active()
            ->where('level', 1)
            ->ordered()
            ->get();

        $strukturLevel2 = StrukturOrganisasi::with('teacher.rombelWaliKelas.kelas')
            ->active()
            ->where('level', 2)
            ->ordered()
            ->get();

        $strukturLevel3 = StrukturOrganisasi::with('teacher.rombelWaliKelas.kelas')
            ->active()
            ->where('level', 3)
            ->ordered()
            ->get();

        $strukturLevel4 = StrukturOrganisasi::with('teacher.rombelWaliKelas.kelas')
            ->active()
            ->where('level', 4)
            ->ordered()
            ->get();

        $strukturLevel5 = StrukturOrganisasi::with('teacher.rombelWaliKelas.kelas')
            ->active()
            ->where('level', 5)
            ->ordered()
            ->get();

        $strukturLevel6 = StrukturOrganisasi::with('teacher.rombelWaliKelas.kelas')
            ->active()
            ->where('level', 6)
            ->ordered()
            ->get();

        $data = [
            'profile' => $profile,
            'tahunAjaran' => $tahunAjaran,
            'strukturLevel0' => $strukturLevel0,
            'strukturLevel1' => $strukturLevel1,
            'strukturLevel2' => $strukturLevel2,
            'strukturLevel3' => $strukturLevel3,
            'strukturLevel4' => $strukturLevel4,
            'strukturLevel5' => $strukturLevel5,
            'strukturLevel6' => $strukturLevel6,
            'qrCodeImage' => 'data:image/png;base64,' . base64_encode(app(\App\Services\QrCodeService::class)->generateDocumentVerificationQrCode()),
        ];

        $pdf = Pdf::loadView('pdf.struktur-organisasi', $data);
        $pdf->setPaper('A4', 'landscape');

        $filename = 'Struktur-Organisasi-' . ($tahunAjaran->nama ?? 'Madrasah') . '.pdf';
        $filename = str_replace('/', '-', $filename);

        return $pdf->download($filename);
    }
}
