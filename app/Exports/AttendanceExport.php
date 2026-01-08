<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $attendances;

    public function __construct($attendances)
    {
        $this->attendances = $attendances;
    }

    public function collection()
    {
        return $this->attendances;
    }

    public function map($attendance): array
    {
        return [
            $attendance->date,
            $attendance->user->name ?? '-',
            $attendance->time_in,
            $attendance->time_out,
            $attendance->status,
            $attendance->keterlambatan,
            $attendance->lembur,
            $attendance->note,
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Pegawai',
            'Jam Masuk',
            'Jam Pulang',
            'Status',
            'Telat (Menit)',
            'Lembur (Menit)',
            'Catatan',
        ];
    }
}
