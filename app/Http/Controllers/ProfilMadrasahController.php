<?php

namespace App\Http\Controllers;

use App\Models\ProfileMadrasah;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\QrCodeService;

class ProfilMadrasahController extends Controller
{
    public function downloadPdf()
    {
        $profile = ProfileMadrasah::firstOrNew();

        // Generate QR Code
        $qrRaw = app(QrCodeService::class)->generateDocumentVerificationQrCode();
        $qrCodeImage = 'data:image/png;base64,' . base64_encode($qrRaw);

        $pdf = Pdf::loadView('pdf.profil-madrasah', [
            'profile' => $profile,
            'qrCodeImage' => $qrCodeImage,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'Profil-' . ($profile->nama_madrasah ?? 'Madrasah') . '.pdf';
        $filename = str_replace(['/', '\\', ' '], '-', $filename);

        return $pdf->download($filename);
    }
}
