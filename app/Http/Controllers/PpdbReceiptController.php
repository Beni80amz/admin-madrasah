<?php

namespace App\Http\Controllers;

use App\Models\PpdbRegistration;
use App\Models\ProfileMadrasah;
use App\Models\AppSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\QrCodeService;

class PpdbReceiptController extends Controller
{
    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
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
        $qrData = $this->qrCodeService->generateDocumentVerificationQrCode();
        $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrData);

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
        $qrData = $this->qrCodeService->generateDocumentVerificationQrCode();
        $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrData);

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

