<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class StudentImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    // Heading row is row 1
    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        // Skip if this looks like a header row or sample data (e.g., contains 'nama_lengkap' text)
        if (
            empty($row['nama_lengkap']) ||
            $row['nama_lengkap'] === 'nama_lengkap' ||
            str_contains(strtolower($row['nama_lengkap'] ?? ''), 'contoh')
        ) {
            return null;
        }

        // Handle tanggal_lahir - can be string (YYYY-MM-DD) or Excel serial number
        $tanggalLahir = null;
        if (isset($row['tanggal_lahir']) && $row['tanggal_lahir'] !== '') {
            if (is_numeric($row['tanggal_lahir'])) {
                // Excel serial number
                $tanggalLahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d');
            } else {
                // Already a string date
                $tanggalLahir = $row['tanggal_lahir'];
            }
        }

        return new Student([
            'nama_lengkap' => $row['nama_lengkap'],
            'nis_lokal' => (string) $row['nis_lokal'],
            'nisn' => (string) $row['nisn'],
            'nik' => isset($row['nik']) ? (string) $row['nik'] : null,
            'gender' => $row['gender'],
            'tempat_lahir' => $row['tempat_lahir'] ?? null,
            'tanggal_lahir' => $tanggalLahir,
            'kelas' => $this->normalizeKelas($row['kelas']),
            'nama_ibu' => $row['nama_ibu'] ?? null,
            'nama_ayah' => $row['nama_ayah'] ?? null,
            'nomor_mobile' => isset($row['nomor_mobile']) ? (string) $row['nomor_mobile'] : null,
            'nomor_pip' => isset($row['nomor_pip']) ? (string) $row['nomor_pip'] : null,
            'alamat_kk' => $row['alamat_kk'] ?? null,
            'alamat_domisili' => $row['alamat_domisili'] ?? null,
            'is_active' => true,
        ]);
    }

    public function prepareForValidation($data, $index)
    {
        // Skip header row or sample data rows
        if (
            empty($data['nama_lengkap']) ||
            $data['nama_lengkap'] === 'nama_lengkap' ||
            str_contains(strtolower($data['nama_lengkap'] ?? ''), 'contoh')
        ) {
            // Return empty to skip validation for this row
            return [];
        }

        // Convert numeric values to string before validation
        $data['nis_lokal'] = isset($data['nis_lokal']) ? (string) $data['nis_lokal'] : null;
        $data['nisn'] = isset($data['nisn']) ? (string) $data['nisn'] : null;
        $data['nik'] = isset($data['nik']) ? (string) $data['nik'] : null;

        return $data;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required|max:255',
            'nis_lokal' => 'required|min:3|max:8|unique:students,nis_lokal',
            'nisn' => 'required|max:10|unique:students,nisn',
            'nik' => 'required|min:3|max:16|unique:students,nik',
            'gender' => 'required|in:Laki-laki,Perempuan,L,P',
            'kelas' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nis_lokal.required' => 'Kolom NIS Lokal wajib diisi',
            'nis_lokal.min' => 'NIS Lokal minimal 3 karakter',
            'nis_lokal.max' => 'NIS Lokal maksimal 8 karakter',
            'nis_lokal.unique' => 'NIS Lokal sudah terdaftar di database',
            'nisn.required' => 'Kolom NISN wajib diisi',
            'nisn.max' => 'NISN maksimal 10 karakter',
            'nisn.unique' => 'NISN sudah terdaftar di database',
            'nik.required' => 'Kolom NIK wajib diisi',
            'nik.min' => 'NIK minimal 3 karakter',
            'nik.max' => 'NIK maksimal 16 karakter',
            'nik.unique' => 'NIK sudah terdaftar di database',
            'gender.in' => 'Gender harus Laki-laki atau Perempuan',
        ];
    }

    /**
     * Normalize kelas format from "6A" to "6-A"
     */
    private function normalizeKelas($kelas): string
    {
        $kelas = (string) $kelas;

        // If already contains hyphen, return as is
        if (str_contains($kelas, '-')) {
            return $kelas;
        }

        // If 2+ characters and no hyphen, insert hyphen before last character
        if (strlen($kelas) >= 2) {
            return substr($kelas, 0, -1) . '-' . substr($kelas, -1);
        }

        return $kelas;
    }
}
