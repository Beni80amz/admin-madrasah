<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class StudentTemplateExport implements FromArray, WithHeadings, WithStyles, WithEvents
{
    public function array(): array
    {
        // Return empty array - we'll set values explicitly in registerEvents
        return [];
    }

    public function headings(): array
    {
        return [
            'nama_lengkap',
            'nis_lokal',
            'nisn',
            'nik',
            'gender',
            'tempat_lahir',
            'tanggal_lahir',
            'kelas',
            'nama_ibu',
            'nama_ayah',
            'nomor_mobile',
            'nomor_pip',
            'alamat_kk',
            'alamat_domisili',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Sample data - Row 2
                $sheet->setCellValue('A2', 'Ahmad Fauzi');
                $sheet->setCellValueExplicit('B2', '2024001', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('C2', '0051234567', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('D2', '3276051234560001', DataType::TYPE_STRING);
                $sheet->setCellValue('E2', 'Laki-laki');
                $sheet->setCellValue('F2', 'Depok');
                $sheet->setCellValue('G2', '2012-05-15');
                $sheet->setCellValue('H2', '6-A');
                $sheet->setCellValue('I2', 'Siti Aminah');
                $sheet->setCellValue('J2', 'Budi Rahman');
                $sheet->setCellValueExplicit('K2', '081234567890', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('L2', '1234567890123456', DataType::TYPE_STRING);
                $sheet->setCellValue('M2', 'Jl. Merdeka No. 10');
                $sheet->setCellValue('N2', 'Jl. Merdeka No. 10');

                // Sample data - Row 3
                $sheet->setCellValue('A3', 'Aisyah Putri');
                $sheet->setCellValueExplicit('B3', '2024002', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('C3', '0051234568', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('D3', '3276051234560002', DataType::TYPE_STRING);
                $sheet->setCellValue('E3', 'Perempuan');
                $sheet->setCellValue('F3', 'Jakarta');
                $sheet->setCellValue('G3', '2013-08-22');
                $sheet->setCellValue('H3', '5-A');
                $sheet->setCellValue('I3', 'Fatimah Azzahra');
                $sheet->setCellValue('J3', 'Muhammad Rizky');
                $sheet->setCellValueExplicit('K3', '082345678901', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('L3', '2345678901234567', DataType::TYPE_STRING);
                $sheet->setCellValue('M3', 'Jl. Kemerdekaan No. 25');
                $sheet->setCellValue('N3', 'Jl. Kemerdekaan No. 25');

                // Set column formats as TEXT for numeric columns
                $sheet->getStyle('B:D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->getStyle('K:L')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

                // Sample data style (italic to indicate it's just example)
                $sheet->getStyle('A2:N3')->applyFromArray([
                    'font' => ['italic' => true, 'color' => ['rgb' => '666666']],
                ]);
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '10B981'],
            ],
        ]);

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(20);  // nama_lengkap
        $sheet->getColumnDimension('B')->setWidth(12);  // nis_lokal
        $sheet->getColumnDimension('C')->setWidth(15);  // nisn
        $sheet->getColumnDimension('D')->setWidth(20);  // nik
        $sheet->getColumnDimension('E')->setWidth(12);  // gender
        $sheet->getColumnDimension('F')->setWidth(12);  // tempat_lahir
        $sheet->getColumnDimension('G')->setWidth(12);  // tanggal_lahir
        $sheet->getColumnDimension('H')->setWidth(8);   // kelas
        $sheet->getColumnDimension('I')->setWidth(15);  // nama_ibu
        $sheet->getColumnDimension('J')->setWidth(15);  // nama_ayah
        $sheet->getColumnDimension('K')->setWidth(15);  // nomor_mobile
        $sheet->getColumnDimension('L')->setWidth(18);  // nomor_pip
        $sheet->getColumnDimension('M')->setWidth(25);  // alamat_kk
        $sheet->getColumnDimension('N')->setWidth(25);  // alamat_domisili

        return [];
    }
}
