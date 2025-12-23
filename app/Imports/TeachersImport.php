<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\Jabatan;
use App\Models\TugasPokok;
use App\Models\TugasTambahan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;


use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class TeachersImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        // Log keys for debugging if needed
        // Log::info('Import Row Keys: ' . json_encode(array_keys($row)));

        // Try to find name key
        $name = null;
        $nameKeys = ['nama_lengkap', 'nama_lengkap_', 'nama', 'nama_guru'];

        foreach ($nameKeys as $key) {
            if (!empty($row[$key])) {
                $name = $row[$key];
                break;
            }
        }

        // If no name, skip this row
        if (empty($name)) {
            return null;
        }

        // Find or create relations by name
        $jabatan = Jabatan::firstOrCreate(['nama' => $row['jabatan_lihat_sheet_referensi'] ?? $row['jabatan'] ?? '']);
        $tugasPokok = TugasPokok::firstOrCreate(['nama' => $row['tugas_pokok_lihat_sheet_referensi'] ?? $row['tugas_pokok'] ?? '']);

        $tugasTambahan = null;
        $tugasTambahanNama = $row['tugas_tambahan_lihat_sheet_referensi'] ?? $row['tugas_tambahan'] ?? null;
        if ($tugasTambahanNama) {
            $tugasTambahan = TugasTambahan::firstOrCreate(['nama' => $tugasTambahanNama]);
        }

        // Handle NIP keys
        $nip = null;
        $nipKeys = ['nipnik', 'nip', 'nik', 'nip_nik', 'nomor_induk'];
        foreach ($nipKeys as $key) {
            if (!empty($row[$key])) {
                $nip = $row[$key];
                break;
            }
        }

        // Clean NIP (remove single quote prefix if present from export)
        if ($nip && str_starts_with($nip, "'")) {
            $nip = substr($nip, 1);
        }

        return new Teacher([
            'nama_lengkap' => $name,
            'nip' => $nip,
            'jabatan_id' => $jabatan->id,
            'tugas_pokok_id' => $tugasPokok->id,
            'tugas_tambahan_id' => $tugasTambahan?->id,
            'status' => $row['status_pnsnon_pnsp3k'] ?? $row['status'] ?? 'Non PNS',
            'sertifikasi' => $row['sertifikasi_sudahbelum'] ?? $row['sertifikasi'] ?? 'Belum',
            'is_active' => strtolower($row['aktif_yatidak'] ?? $row['aktif'] ?? 'ya') === 'ya',
        ]);
    }

    public function rules(): array
    {
        return [
            // '*.nama_lengkap' => ['required'], // Validation is tricky if keys vary, relying on model() null check is safer for now
        ];
    }
}
