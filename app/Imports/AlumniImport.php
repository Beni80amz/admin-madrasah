<?php

namespace App\Imports;

use App\Models\Alumni;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AlumniImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Alumni([
            'nama_lengkap' => $row['nama_lengkap'],
            'tahun_lulus' => (string) $row['tahun_lulus'], // Convert to string
            'alamat' => $row['alamat'] ?? null,
            'nomor_mobile' => isset($row['nomor_mobile']) ? (string) $row['nomor_mobile'] : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required|max:255',
            'tahun_lulus' => 'required',
            'alamat' => 'nullable',
            'nomor_mobile' => 'nullable|max:20',
        ];
    }
}
