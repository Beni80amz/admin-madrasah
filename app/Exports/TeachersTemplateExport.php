<?php

namespace App\Exports;

use App\Models\Jabatan;
use App\Models\TugasPokok;
use App\Models\TugasTambahan;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TeachersTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Data Guru' => new TeachersTemplateSheet(),
            'Referensi Jabatan' => new JabatanReferenceSheet(),
            'Referensi Tugas Pokok' => new TugasPokokReferenceSheet(),
            'Referensi Tugas Tambahan' => new TugasTambahanReferenceSheet(),
        ];
    }
}

class TeachersTemplateSheet implements FromArray, WithHeadings, WithStyles, WithColumnFormatting, ShouldAutoSize
{
    public function array(): array
    {
        return [
            ['Ahmad Fauzi', "'1234567890123456", "'1234567890123456", "'123456", 'Guru Kelas', 'Guru Matematika', 'Wali Kelas', 'PNS', 'Sudah', 'Ya'],
            ['Siti Nurhaliza', "'9876543210987654", "", "", 'Guru Mapel', 'Guru Bahasa Indonesia', '', 'Non PNS', 'Belum', 'Ya'],
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap *',
            'NIP/NIK',
            'NUPTK',
            'NPK/Peg.ID',
            'Jabatan * (lihat sheet Referensi)',
            'Tugas Pokok * (lihat sheet Referensi)',
            'Tugas Tambahan (lihat sheet Referensi)',
            'Status * (PNS/Non PNS/P3K)',
            'Sertifikasi * (Sudah/Belum)',
            'Aktif * (Ya/Tidak)',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT, // NIP/NIK
            'C' => NumberFormat::FORMAT_TEXT, // NUPTK
            'D' => NumberFormat::FORMAT_TEXT, // NPK
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

class JabatanReferenceSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return Jabatan::all()->map(fn($item) => [$item->id, $item->nama])->toArray();
    }

    public function headings(): array
    {
        return ['ID', 'Nama Jabatan'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D1FAE5'],
                ],
            ],
        ];
    }
}

class TugasPokokReferenceSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return TugasPokok::all()->map(fn($item) => [$item->id, $item->nama])->toArray();
    }

    public function headings(): array
    {
        return ['ID', 'Nama Tugas Pokok'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D1FAE5'],
                ],
            ],
        ];
    }
}

class TugasTambahanReferenceSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return TugasTambahan::all()->map(fn($item) => [$item->id, $item->nama])->toArray();
    }

    public function headings(): array
    {
        return ['ID', 'Nama Tugas Tambahan'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D1FAE5'],
                ],
            ],
        ];
    }
}
