<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class StudentImport implements OnEachRow, WithHeadingRow
{
    // Heading row is row 1
    public function headingRow(): int
    {
        return 1;
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row = $row->toArray();

        // Skip if this looks like a header row or sample data
        if (
            empty($row['nama_lengkap']) ||
            $row['nama_lengkap'] === 'nama_lengkap' ||
            str_contains(strtolower($row['nama_lengkap'] ?? ''), 'contoh')
        ) {
            return;
        }

        // 1. Try to find by NIS Lokal (Priority)
        $student = null;
        if (!empty($row['nis_lokal'])) {
            $student = Student::where('nis_lokal', (string) $row['nis_lokal'])->first();
        }

        // 2. If not found, try by NISN
        if (!$student && !empty($row['nisn'])) {
            $student = Student::where('nisn', (string) $row['nisn'])->first();
        }

        // 3. If not found, try by Nama Lengkap
        if (!$student && !empty($row['nama_lengkap'])) {
            $student = Student::where('nama_lengkap', $row['nama_lengkap'])->first();
        }

        // If student matches, perform UPDATE only for empty fields
        if ($student) {
            $updates = [];

            // Helper to fill if empty
            $fillIfEmpty = function ($column, $value) use ($student, &$updates) {
                // Determine if the DB column is effectively "empty"
                $dbValue = $student->$column;
                $isEmpty = is_null($dbValue) || trim((string) $dbValue) === '';

                if ($isEmpty && !empty($value)) {
                    $updates[$column] = $value;
                }
            };

            // Process Date
            $tanggalLahir = null;
            if (!empty($row['tanggal_lahir'])) {
                try {
                    if (is_numeric($row['tanggal_lahir'])) {
                        $tanggalLahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d');
                    } else {
                        $tanggalLahir = $row['tanggal_lahir'];
                    }
                } catch (\Exception $e) {
                    // Ignore invalid date
                }
            }

            // --- MAPPING COLUMNS ---
            // Key fields (if they were used for identification but other key fields are missing in DB, fill them too)
            $fillIfEmpty('nis_lokal', (string) ($row['nis_lokal'] ?? ''));
            $fillIfEmpty('nisn', (string) ($row['nisn'] ?? ''));
            $fillIfEmpty('nik', (string) ($row['nik'] ?? ''));

            // Biodata
            $fillIfEmpty('tempat_lahir', $row['tempat_lahir'] ?? null);
            if ($tanggalLahir) {
                $fillIfEmpty('tanggal_lahir', $tanggalLahir);
            }
            $fillIfEmpty('gender', $row['gender'] ?? null); // Expect Laki-laki / Perempuan / L / P

            // Normalize Gender if updating
            if (isset($updates['gender'])) {
                $g = $updates['gender'];
                if (strtoupper($g) === 'L')
                    $updates['gender'] = 'Laki-laki';
                if (strtoupper($g) === 'P')
                    $updates['gender'] = 'Perempuan';
            }

            $fillIfEmpty('nama_ibu', $row['nama_ibu'] ?? null);
            $fillIfEmpty('nama_ayah', $row['nama_ayah'] ?? null);
            $fillIfEmpty('nomor_mobile', isset($row['nomor_mobile']) ? (string) $row['nomor_mobile'] : null);
            $fillIfEmpty('nomor_pip', isset($row['nomor_pip']) ? (string) $row['nomor_pip'] : null);
            $fillIfEmpty('alamat_kk', $row['alamat_kk'] ?? null);
            $fillIfEmpty('alamat_domisili', $row['alamat_domisili'] ?? null);

            // Not updating 'kelas' or 'is_active' to prevent messing up current status via simple import
            // unless user explicitly wants to? For now, let's stick to biodata as requested.

            if (!empty($updates)) {
                $student->update($updates);
            }
        }
    }
}
