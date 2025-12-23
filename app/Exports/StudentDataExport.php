<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StudentDataExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithColumnFormatting
{
    protected string $search;
    protected string $kelas;

    public function __construct(string $search = '', string $kelas = '')
    {
        $this->search = $search;
        $this->kelas = $kelas;
    }

    public function collection()
    {
        $query = Student::query()
            ->where('is_active', true)
            ->orderBy('kelas', 'asc')
            ->orderBy('nama_lengkap', 'asc');

        // Filter by search
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    ->orWhere('nisn', 'like', '%' . $this->search . '%')
                    ->orWhere('nis_lokal', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by kelas
        if (!empty($this->kelas)) {
            $query->where('kelas', $this->kelas);
        }

        $students = $query->get();

        // Map to export format with row numbers
        $result = $students->map(function ($student, $index) {
            return [
                'no' => $index + 1,
                'nama_lengkap' => $student->nama_lengkap,
                'nis_lokal' => $student->nis_lokal,
                'nisn' => $student->nisn,
                'kelas' => $student->kelas,
                'gender' => $student->gender,
                'status' => $student->is_active ? 'Aktif' : 'Tidak Aktif',
            ];
        });

        return $result;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'NIS Lokal',
            'NISN',
            'Kelas',
            'Jenis Kelamin',
            'Status',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT, // nis_lokal
            'D' => NumberFormat::FORMAT_TEXT, // nisn
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set columns as text format
        $sheet->getStyle('C:D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '10B981']
                ],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 30,
            'C' => 12,
            'D' => 15,
            'E' => 10,
            'F' => 15,
            'G' => 15,
        ];
    }
}
