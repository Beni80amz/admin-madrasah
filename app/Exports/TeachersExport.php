<?php

namespace App\Exports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TeachersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, ShouldAutoSize
{
    public function collection()
    {
        return Teacher::with(['jabatan', 'tugasPokok', 'tugasTambahan'])->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'NIP/NIK',
            'NUPTK',
            'NPK/Peg.ID',
            'Jabatan',
            'Tugas Pokok',
            'Tugas Tambahan',
            'Status',
            'Sertifikasi',
            'Aktif',
        ];
    }

    public function map($teacher): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $teacher->nama_lengkap,
            "'" . $teacher->nip, // Prefix with ' to force text format
            "'" . $teacher->nuptk,
            "'" . $teacher->npk_peg_id,
            $teacher->jabatan?->nama ?? '-',
            $teacher->tugasPokok?->nama ?? '-',
            $teacher->tugasTambahan?->nama ?? '-',
            $teacher->status,
            $teacher->sertifikasi,
            $teacher->is_active ? 'Ya' : 'Tidak',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT, // NIP/NIK column as text
            'D' => NumberFormat::FORMAT_TEXT, // NUPTK
            'E' => NumberFormat::FORMAT_TEXT, // NPK
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '10B981'],
                ],
            ],
        ];
    }
}
