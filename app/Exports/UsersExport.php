<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    protected $records;

    public function __construct($records = null)
    {
        $this->records = $records;
    }

    public function collection()
    {
        return $this->records ?: User::all();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'Username / Email',
            'Role',
            'Nama Asli (Guru/Siswa)',
        ];
    }

    public function map($user): array
    {
        static $no = 0;
        $no++;

        $role = $user->roles->first()?->name ?? '-';
        $realName = $user->teacher?->nama_lengkap ?? ($user->student?->nama_lengkap ?? $user->name);

        // Translating role names for better readability
        $roleDisplay = match ($role) {
            'super_admin' => 'Super Admin',
            'teacher' => 'Guru',
            'student' => 'Siswa',
            default => $role,
        };

        return [
            $no,
            $user->name,
            "'" . $user->email, // Force text format for numeric usernames
            $roleDisplay,
            $realName,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '10B981'], // Emerald-500 equivalent
                ],
            ],
        ];
    }
}
