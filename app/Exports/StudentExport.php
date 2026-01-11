<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class StudentExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    private $students;
    private ?string $kelas;
    private ?string $gender;

    public function __construct(?string $kelas = null, ?string $gender = null)
    {
        $this->kelas = $kelas;
        $this->gender = $gender;
    }

    public function collection()
    {
        $query = Student::where('status', Student::STATUS_AKTIF);

        if ($this->kelas) {
            $query->where('kelas', $this->kelas);
        }

        if ($this->gender) {
            $query->where('gender', $this->gender);
        }

        $this->students = $query
            ->orderBy('kelas', 'asc')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        return collect([]); // Return empty - we'll populate via events
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'NIS Lokal',
            'NISN',
            'NIK',
            'Gender',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Kelas',
            'Nama Ibu',
            'Nama Ayah',
            'Nomor Mobile',
            'Nomor PIP',
            'Alamat KK',
            'Alamat Domisili',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set column formats as TEXT for numeric columns
                $sheet->getStyle('C:E')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->getStyle('L:M')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

                $row = 2;
                foreach ($this->students as $index => $student) {
                    $no = $index + 1;

                    $sheet->setCellValue('A' . $row, $no);
                    $sheet->setCellValue('B' . $row, $student->nama_lengkap);
                    $sheet->setCellValueExplicit('C' . $row, (string) $student->nis_lokal, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('D' . $row, (string) $student->nisn, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('E' . $row, (string) $student->nik, DataType::TYPE_STRING);
                    $sheet->setCellValue('F' . $row, $student->gender);
                    $sheet->setCellValue('G' . $row, $student->tempat_lahir);
                    $sheet->setCellValue('H' . $row, $student->tanggal_lahir?->format('Y-m-d'));
                    $sheet->setCellValue('I' . $row, $student->kelas);
                    $sheet->setCellValue('J' . $row, $student->nama_ibu);
                    $sheet->setCellValue('K' . $row, $student->nama_ayah);
                    $sheet->setCellValueExplicit('L' . $row, (string) $student->nomor_mobile, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('M' . $row, (string) $student->nomor_pip, DataType::TYPE_STRING);
                    $sheet->setCellValue('N' . $row, $student->alamat_kk);
                    $sheet->setCellValue('O' . $row, $student->alamat_domisili);

                    $row++;
                }
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '10B981'],
            ],
        ]);

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(5);   // No
        $sheet->getColumnDimension('B')->setWidth(25);  // Nama Lengkap
        $sheet->getColumnDimension('C')->setWidth(12);  // NIS Lokal
        $sheet->getColumnDimension('D')->setWidth(15);  // NISN
        $sheet->getColumnDimension('E')->setWidth(20);  // NIK
        $sheet->getColumnDimension('F')->setWidth(12);  // Gender
        $sheet->getColumnDimension('G')->setWidth(12);  // Tempat Lahir
        $sheet->getColumnDimension('H')->setWidth(12);  // Tanggal Lahir
        $sheet->getColumnDimension('I')->setWidth(10);  // Kelas
        $sheet->getColumnDimension('J')->setWidth(15);  // Nama Ibu
        $sheet->getColumnDimension('K')->setWidth(15);  // Nama Ayah
        $sheet->getColumnDimension('L')->setWidth(15);  // Nomor Mobile
        $sheet->getColumnDimension('M')->setWidth(18);  // Nomor PIP
        $sheet->getColumnDimension('N')->setWidth(25);  // Alamat KK
        $sheet->getColumnDimension('O')->setWidth(25);  // Alamat Domisili

        return [];
    }
}
