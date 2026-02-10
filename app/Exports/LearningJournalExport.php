<?php

namespace App\Exports;

use App\Models\LearningJournal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LearningJournalExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithMapping
{
    public function collection()
    {
        return LearningJournal::with(['user.teacher', 'mataPelajaran', 'rombel.kelas'])
            ->orderBy('date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Guru',
            'Mata Pelajaran',
            'Kelas',
            'Pertemuan',
            'Semester',
            'Materi',
            'Absensi (S/I/A)',
            'Hambatan (Evaluasi)',
            'Solusi (Tindak Lanjut)',
        ];
    }

    public function map($journal): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $journal->date->format('d/m/Y'),
            $journal->user?->teacher?->nama_lengkap ?? $journal->user?->name,
            $journal->mataPelajaran?->nama,
            ($journal->rombel?->kelas?->nama ?? '') . ' - ' . ($journal->rombel?->nama ?? ''),
            $journal->pertemuan_ke,
            $journal->semester,
            $journal->materi,
            $journal->getFormattedAttendanceNames(),
            $journal->hambatan,
            $journal->solusi,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '10B981']
                ],
                'alignment' => ['horizontal' => 'center'],
            ],
            // Wrap text for Materi, Hambatan, Solusi
            'H' => ['alignment' => ['wrapText' => true]],
            'J' => ['alignment' => ['wrapText' => true]],
            'K' => ['alignment' => ['wrapText' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 25,
            'D' => 20,
            'E' => 12,
            'F' => 10,
            'G' => 10,
            'H' => 40, // Materi
            'I' => 30, // Absensi
            'J' => 30, // Hambatan
            'K' => 30, // Solusi
        ];
    }
}
