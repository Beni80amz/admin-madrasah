<?php

namespace App\Http\Controllers;

use App\Models\SiswaMasuk;
use App\Models\ProfileMadrasah;
use Illuminate\Http\Request;

class VerifikasiSuratMasukController extends Controller
{
    public function show($id)
    {
        $siswaMasuk = SiswaMasuk::findOrFail($id);
        $siteProfile = ProfileMadrasah::first();

        return view('verifikasi-surat-masuk', [
            'siswaMasuk' => $siswaMasuk,
            'siteProfile' => $siteProfile,
        ]);
    }
}
