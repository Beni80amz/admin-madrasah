<?php

namespace App\Http\Controllers;

use App\Models\ProfileMadrasah;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfilMadrasahController extends Controller
{
    public function downloadPdf()
    {
        $profile = ProfileMadrasah::first();

        if (!$profile) {
            abort(404, 'Profile Madrasah tidak ditemukan');
        }

        $pdf = Pdf::loadView('pdf.profil-madrasah', [
            'profile' => $profile,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'Profil-' . ($profile->nama_madrasah ?? 'Madrasah') . '.pdf';
        $filename = str_replace(['/', '\\', ' '], '-', $filename);

        return $pdf->download($filename);
    }
}
