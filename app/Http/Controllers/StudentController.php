<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ProfileMadrasah;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    public function downloadPdf()
    {
        // Increase memory limit and execution time for large PDF generation
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '300');

        $siteProfile = ProfileMadrasah::first();
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();

        $students = Student::where('is_active', true)
            ->orderBy('kelas', 'asc')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        $total = $students->count();

        // Group by kelas for statistics
        $byKelas = $students->groupBy('kelas')
            ->map(function ($items) {
                return $items->count();
            })
            ->sortKeys();

        $data = [
            'siteProfile' => $siteProfile,
            'tahunAjaran' => $tahunAjaran,
            'students' => $students,
            'total' => $total,
            'byKelas' => $byKelas,
        ];

        $pdf = Pdf::loadView('pdf.students', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'Data-Siswa-' . (optional($siteProfile)->nama_madrasah ?? 'Madrasah') . '.pdf';
        $filename = str_replace(['/', '\\'], '-', $filename);

        return $pdf->download($filename);
    }
}
