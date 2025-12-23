<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AlumniTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        // Sample data for template
        return [
            ['Ahmad Fadillah', '2024', 'Jl. Merdeka No. 15, Depok', '081234567890'],
            ['Siti Aisyah', '2024', 'Jl. Kemerdekaan No. 20, Depok', '082345678901'],
            ['Muhammad Rizky', '2023', 'Jl. Veteran No. 8, Depok', '083456789012'],
        ];
    }

    public function headings(): array
    {
        return [
            'nama_lengkap',
            'tahun_lulus',
            'alamat',
            'nomor_mobile',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '10B981'],
            ],
        ]);

        // Sample data style (italic to indicate it's just example)
        $sheet->getStyle('A2:D4')->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '666666']],
        ]);

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(20);

        return [];
    }
}
