<?php

namespace App\Http\Controllers;

use App\Models\PpdbRegistration;
use App\Models\ProfileMadrasah;
use App\Models\AppSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PpdbReceiptController extends Controller
{
    /**
     * Generate QR code as base64 SVG for embedding in PDF
     */
    private function generateQrCodeBase64(string $data, int $size = 70): string
    {
        $qrCode = QrCode::format('svg')
            ->size($size)
            ->margin(0)
            ->generate($data);

        return 'data:image/svg+xml;base64,' . base64_encode($qrCode);
    }

    /**
     * Download the PPDB registration receipt as PDF
     */
    public function download($id)
    {
        $registration = PpdbRegistration::findOrFail($id);
        $profile = ProfileMadrasah::first();
        $ppdbInfo = AppSetting::getPpdbInfo();

        // Generate QR Code as base64
        $verificationUrl = route('ppdb.success', $registration->id);
        $qrCodeBase64 = $this->generateQrCodeBase64($verificationUrl);

        $pdf = Pdf::loadView('pdf.ppdb-receipt', [
            'registration' => $registration,
            'profile' => $profile,
            'ppdbInfo' => $ppdbInfo,
            'qrCodeUrl' => $qrCodeBase64,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'Bukti-Pendaftaran-PPDB-' . $registration->no_daftar . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Stream the PPDB registration receipt as PDF (preview in browser)
     */
    public function stream($id)
    {
        $registration = PpdbRegistration::findOrFail($id);
        $profile = ProfileMadrasah::first();
        $ppdbInfo = AppSetting::getPpdbInfo();

        // Generate QR Code as base64
        $verificationUrl = route('ppdb.success', $registration->id);
        $qrCodeBase64 = $this->generateQrCodeBase64($verificationUrl);

        $pdf = Pdf::loadView('pdf.ppdb-receipt', [
            'registration' => $registration,
            'profile' => $profile,
            'ppdbInfo' => $ppdbInfo,
            'qrCodeUrl' => $qrCodeBase64,
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Bukti-Pendaftaran-PPDB-' . $registration->no_daftar . '.pdf');
    }
}

