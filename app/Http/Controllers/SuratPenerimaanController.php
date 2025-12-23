<?php

namespace App\Http\Controllers;

use App\Models\SiswaMasuk;
use App\Models\ProfileMadrasah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SuratPenerimaanController extends Controller
{
    public function download($id)
    {
        $siswaMasuk = SiswaMasuk::findOrFail($id);
        $siteProfile = ProfileMadrasah::first();

        $data = [
            'siswaMasuk' => $siswaMasuk,
            'siteProfile' => $siteProfile,
        ];

        $pdf = Pdf::loadView('pdf.surat-penerimaan-sementara', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'Surat_Penerimaan_' . str_replace(' ', '_', $siswaMasuk->nama_lengkap) . '.pdf';

        return $pdf->download($filename);
    }

    public function stream($id)
    {
        $siswaMasuk = SiswaMasuk::findOrFail($id);
        $siteProfile = ProfileMadrasah::first();

        $data = [
            'siswaMasuk' => $siswaMasuk,
            'siteProfile' => $siteProfile,
        ];

        $pdf = Pdf::loadView('pdf.surat-penerimaan-sementara', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Surat_Penerimaan_' . str_replace(' ', '_', $siswaMasuk->nama_lengkap) . '.pdf');
    }
}
