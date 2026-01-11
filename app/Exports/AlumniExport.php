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
            'NIS Lokal',
            'Nama Lengkap',
            'NISN',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Gender',
            'Nama Ibu',
            'Nama Ayah',
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
            $alumni->nis_lokal ?? '-',
            $alumni->nama_lengkap,
            $alumni->nisn ?? '-',
            $alumni->tempat_lahir ?? '-',
            $alumni->tanggal_lahir ? $alumni->tanggal_lahir->format('d-m-Y') : '-',
            $alumni->gender ?? '-',
            $alumni->nama_ibu ?? '-',
            $alumni->nama_ayah ?? '-',
            $alumni->tahun_lulus ?? '-',
            $alumni->alamat ?? '-',
            $alumni->nomor_mobile ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
