<?php

namespace App\Http\Controllers;

use App\Models\SiswaKeluar;
use App\Models\ProfileMadrasah;
use Illuminate\Http\Request;

class VerifikasiSuratController extends Controller
{
    public function show($id)
    {
        $siswaKeluar = SiswaKeluar::findOrFail($id);
        $siteProfile = ProfileMadrasah::first();

        return view('verifikasi-surat', [
            'siswaKeluar' => $siswaKeluar,
            'siteProfile' => $siteProfile,
        ]);
    }
}
