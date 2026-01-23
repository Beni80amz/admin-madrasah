<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\ProfileMadrasah;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;

class AchievementController extends Controller
{
    public function downloadStudentPdf()
    {
        $siteProfile = ProfileMadrasah::first();
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();

        $achievements = Achievement::where('type', 'siswa')
            ->orderBy('tahun', 'desc')
            ->orderBy('peringkat', 'asc')
            ->get();

        // Calculate statistics
        $juara1 = $achievements->where('peringkat', 1)->count();
        $juara2 = $achievements->where('peringkat', 2)->count();
        $juara3 = $achievements->where('peringkat', 3)->count();
        $total = $achievements->count();

        $data = [
            'siteProfile' => $siteProfile,
            'tahunAjaran' => $tahunAjaran,
            'achievements' => $achievements,
            'juara1' => $juara1,
            'juara2' => $juara2,
            'juara3' => $juara3,
            'total' => $total,
            'type' => 'Siswa',
            'qrCodeImage' => 'data:image/png;base64,' . base64_encode(app(\App\Services\QrCodeService::class)->generateDocumentVerificationQrCode()),
        ];

        $pdf = Pdf::loadView('pdf.achievements', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions(['isRemoteEnabled' => true]);

        $filename = 'Prestasi-Siswa-' . ($siteProfile->nama_madrasah ?? 'Madrasah') . '.pdf';
        $filename = str_replace(['/', '\\'], '-', $filename);

        return $pdf->download($filename);
    }

    public function downloadTeacherPdf()
    {
        $siteProfile = ProfileMadrasah::first();
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();

        $achievements = Achievement::where('type', 'guru')
            ->orderBy('tahun', 'desc')
            ->orderBy('peringkat', 'asc')
            ->get();

        // Calculate statistics
        $juara1 = $achievements->where('peringkat', 1)->count();
        $juara2 = $achievements->where('peringkat', 2)->count();
        $juara3 = $achievements->where('peringkat', 3)->count();
        $total = $achievements->count();

        $data = [
            'siteProfile' => $siteProfile,
            'tahunAjaran' => $tahunAjaran,
            'achievements' => $achievements,
            'juara1' => $juara1,
            'juara2' => $juara2,
            'juara3' => $juara3,
            'total' => $total,
            'type' => 'Guru',
            'qrCodeImage' => 'data:image/png;base64,' . base64_encode(app(\App\Services\QrCodeService::class)->generateDocumentVerificationQrCode()),
        ];

        $pdf = Pdf::loadView('pdf.achievements', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions(['isRemoteEnabled' => true]);

        $filename = 'Prestasi-Guru-' . ($siteProfile->nama_madrasah ?? 'Madrasah') . '.pdf';
        $filename = str_replace(['/', '\\'], '-', $filename);

        return $pdf->download($filename);
    }
}
