<?php

namespace App\Exports;

use App\Models\Alumni;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AlumniExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Alumni::orderBy('tahun_lulus', 'desc')
            ->orderBy('nama_lengkap', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'Tahun Lulus',
            'Alamat',
            'Nomor Mobile',
        ];
    }

    public function map($alumni): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $alumni->nama_lengkap,
            $alumni->tahun_lulus,
            $alumni->alamat,
            $alumni->nomor_mobile,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
