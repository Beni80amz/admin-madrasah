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
        $query = \App\Filament\Resources\Students\StudentResource::getEloquentQuery()
            ->where('status', Student::STATUS_AKTIF);

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
            'No KK',
            'Kepala Keluarga (KK)',
            'Gender',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Kelas',
            'Status Ibu',
            'Nama Ibu',
            'NIK Ibu',
            'Tempat Lahir Ibu',
            'Tanggal Lahir Ibu',
            'Pendidikan Ibu',
            'Pekerjaan Ibu',
            'Status Ayah',
            'Nama Ayah',
            'NIK Ayah',
            'Tempat Lahir Ayah',
            'Tanggal Lahir Ayah',
            'Pendidikan Ayah',
            'Pekerjaan Ayah',
            'NIK Wali',
            'Nama Wali/Tempat Lahir',
            'Tanggal Lahir Wali',
            'Pendidikan Wali',
            'Pekerjaan Wali',
            'Nomor Mobile',
            'Nomor PIP',
            'Alamat KK',
            'Alamat Domisili',
            'Penghasilan Ortu',
            'Status Rumah',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set column formats as TEXT for numeric columns
                // C:E (NIS Lokal, NISN, NIK), F (No KK), N (NIK Ibu), U (NIK Ayah), Z (NIK Wali), AE:AF (Mobile, PIP)
                $sheet->getStyle('C:F')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->getStyle('N:N')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->getStyle('U:U')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->getStyle('Z:Z')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->getStyle('AE:AF')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

                $row = 2;
                foreach ($this->students as $index => $student) {
                    $no = $index + 1;

                    $sheet->setCellValue('A' . $row, $no);
                    $sheet->setCellValue('B' . $row, $student->nama_lengkap);
                    $sheet->setCellValueExplicit('C' . $row, (string) $student->nis_lokal, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('D' . $row, (string) $student->nisn, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('E' . $row, (string) $student->nik, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('F' . $row, (string) $student->no_kk, DataType::TYPE_STRING);
                    $sheet->setCellValue('G' . $row, $student->nama_kepala_keluarga_diKK);
                    $sheet->setCellValue('H' . $row, $student->gender);
                    $sheet->setCellValue('I' . $row, $student->tempat_lahir);
                    $sheet->setCellValue('J' . $row, $student->tanggal_lahir?->format('Y-m-d'));
                    $sheet->setCellValue('K' . $row, $student->kelas);

                    // Mother
                    $sheet->setCellValue('L' . $row, $student->status_ibu);
                    $sheet->setCellValue('M' . $row, $student->nama_ibu);
                    $sheet->setCellValueExplicit('N' . $row, (string) $student->nik_ibu, DataType::TYPE_STRING);
                    $sheet->setCellValue('O' . $row, $student->tempat_lahir_ibu);
                    $sheet->setCellValue('P' . $row, $student->tanggal_lahir_ibu?->format('Y-m-d'));
                    $sheet->setCellValue('Q' . $row, $student->pendidikan_ibu);
                    $sheet->setCellValue('R' . $row, $student->pekerjaan_ibu === 'Lainnya' ? $student->pekerjaan_ibu_lainnya : $student->pekerjaan_ibu);

                    // Father
                    $sheet->setCellValue('S' . $row, $student->status_ayah);
                    $sheet->setCellValue('T' . $row, $student->nama_ayah);
                    $sheet->setCellValueExplicit('U' . $row, (string) $student->nik_ayah_kandung, DataType::TYPE_STRING);
                    $sheet->setCellValue('V' . $row, $student->tempat_lahir_ayah_kandung);
                    $sheet->setCellValue('W' . $row, $student->tgl_lahir_ayah_kandung?->format('Y-m-d'));
                    $sheet->setCellValue('X' . $row, $student->pendidikan_ayah_kandung);
                    $sheet->setCellValue('Y' . $row, $student->pekerjaan_ayah_kandung === 'Lainnya' ? $student->pekerjaan_ayah_kandung_lainnya : $student->pekerjaan_ayah_kandung);

                    // Guardian
                    $sheet->setCellValueExplicit('Z' . $row, (string) $student->nik_wali, DataType::TYPE_STRING);
                    $sheet->setCellValue('AA' . $row, $student->tempat_lahir_wali);
                    $sheet->setCellValue('AB' . $row, $student->tanggal_lahir_wali?->format('Y-m-d'));
                    $sheet->setCellValue('AC' . $row, $student->pendidikan_wali);
                    $sheet->setCellValue('AD' . $row, $student->pekerjaan_wali === 'Lainnya' ? $student->pekerjaan_wali_lainnya : $student->pekerjaan_wali);

                    // Contact & Bio
                    $sheet->setCellValueExplicit('AE' . $row, (string) $student->nomor_mobile, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('AF' . $row, (string) $student->nomor_pip, DataType::TYPE_STRING);
                    $sheet->setCellValue('AG' . $row, $student->alamat_kk);
                    $sheet->setCellValue('AH' . $row, $student->alamat_domisili);
                    $sheet->setCellValue('AI' . $row, $student->penghasilan_orangtua);
                    $sheet->setCellValue('AJ' . $row, $student->status_rumah === 'Lainnya' ? $student->status_rumah_lainnya : $student->status_rumah);

                    $row++;
                }
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style - up to AJ
        $sheet->getStyle('A1:AJ1')->applyFromArray([
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
        $sheet->getColumnDimension('F')->setWidth(20);  // No KK
        $sheet->getColumnDimension('G')->setWidth(20);  // Kepala Keluarga
        $sheet->getColumnDimension('H')->setWidth(12);  // Gender
        $sheet->getColumnDimension('I')->setWidth(15);  // Tempat Lahir
        $sheet->getColumnDimension('J')->setWidth(12);  // Tanggal Lahir
        $sheet->getColumnDimension('K')->setWidth(10);  // Kelas

        // Mother (L - R)
        $sheet->getColumnDimension('L')->setWidth(12);
        $sheet->getColumnDimension('M')->setWidth(20);
        $sheet->getColumnDimension('N')->setWidth(18);
        $sheet->getColumnDimension('O')->setWidth(15);
        $sheet->getColumnDimension('P')->setWidth(12);
        $sheet->getColumnDimension('Q')->setWidth(15);
        $sheet->getColumnDimension('R')->setWidth(18);

        // Father (S - Y)
        $sheet->getColumnDimension('S')->setWidth(12);
        $sheet->getColumnDimension('T')->setWidth(20);
        $sheet->getColumnDimension('U')->setWidth(18);
        $sheet->getColumnDimension('V')->setWidth(15);
        $sheet->getColumnDimension('W')->setWidth(12);
        $sheet->getColumnDimension('X')->setWidth(15);
        $sheet->getColumnDimension('Y')->setWidth(18);

        // Guardian (Z - AD)
        $sheet->getColumnDimension('Z')->setWidth(18);
        $sheet->getColumnDimension('AA')->setWidth(15);
        $sheet->getColumnDimension('AB')->setWidth(12);
        $sheet->getColumnDimension('AC')->setWidth(15);
        $sheet->getColumnDimension('AD')->setWidth(18);

        // Others
        $sheet->getColumnDimension('AE')->setWidth(15); // Nomor Mobile
        $sheet->getColumnDimension('AF')->setWidth(18); // Nomor PIP
        $sheet->getColumnDimension('AG')->setWidth(30); // Alamat KK
        $sheet->getColumnDimension('AH')->setWidth(30); // Alamat Domisili
        $sheet->getColumnDimension('AI')->setWidth(18); // Penghasilan
        $sheet->getColumnDimension('AJ')->setWidth(15); // Status Rumah

        return [];
    }
}
