<?php

namespace App\Imports;

use App\Models\Alumni;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class AlumniImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row = $row->toArray();

        // 1. Try to find by NIS Lokal (most accurate)
        $alumni = null;
        if (!empty($row['nis_lokal'])) {
            $alumni = Alumni::where('nis_lokal', $row['nis_lokal'])->first();
        }

        // 2. If not found by NIS Lokal, try by Nama Lengkap (fallback)
        if (!$alumni && !empty($row['nama_lengkap'])) {
            $alumni = Alumni::where('nama_lengkap', $row['nama_lengkap'])->first();
        }

        // If alumni found, update MISSING data only
        if ($alumni) {
            $updates = [];

            // Helper to fill if empty
            $fillIfEmpty = function ($column, $value) use ($alumni, &$updates) {
                if (empty($alumni->$column) && !empty($value)) {
                    $updates[$column] = $value;
                }
            };

            // Map Excel columns to DB columns
            $fillIfEmpty('nis_lokal', $row['nis_lokal'] ?? null); // In case matched by name and nis_lokal was empty in DB
            $fillIfEmpty('nisn', $row['nisn'] ?? null);
            $fillIfEmpty('tempat_lahir', $row['tempat_lahir'] ?? null);

            // Handle Date: 2005-01-01
            if (!empty($row['tanggal_lahir'])) {
                try {
                    // Excel might return int or string
                    $tgl = $row['tanggal_lahir'];
                    if (is_numeric($tgl)) {
                        $tgl = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tgl)->format('Y-m-d');
                    }
                    $fillIfEmpty('tanggal_lahir', $tgl);
                } catch (\Exception $e) {
                    // Ignore invalid date format
                }
            }

            $fillIfEmpty('gender', $row['gender'] ?? null);
            $fillIfEmpty('nama_ibu', $row['nama_ibu'] ?? null);
            $fillIfEmpty('nama_ayah', $row['nama_ayah'] ?? null);
            $fillIfEmpty('tahun_lulus', $row['tahun_lulus'] ?? null);
            $fillIfEmpty('alamat', $row['alamat'] ?? null);
            $fillIfEmpty('nomor_mobile', $row['nomor_mobile'] ?? null);

            if (!empty($updates)) {
                $alumni->update($updates);
            }
        }
        // If not found, do nothing (as per requirement: "tidak menambah data")
    }
}
