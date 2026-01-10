<?php

namespace App\Services;

use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RdmSyncService
{
    /**
     * Sync teachers from RDM database to Admin Madrasah
     */
    public function syncTeachersFromRdm(): array
    {
        $stats = ['created' => 0, 'updated' => 0, 'errors' => 0];

        try {
            // Get all active teachers from RDM
            $rdmTeachers = DB::connection('rdm')
                ->table('e_guru')
                ->where('guru_aktif', 1)
                ->get();

            foreach ($rdmTeachers as $rdmTeacher) {
                try {
                    // Find by rdm_id or create new
                    $teacher = Teacher::where('rdm_id', $rdmTeacher->guru_id)->first();

                    $data = [
                        'rdm_id' => $rdmTeacher->guru_id,
                        'nama_lengkap' => $rdmTeacher->guru_nama,
                        'nip' => $rdmTeacher->guru_nip,
                        'nuptk' => $rdmTeacher->guru_nuptk,
                        'is_active' => $rdmTeacher->guru_aktif == 1,
                    ];

                    // Handle photo if exists
                    if (!empty($rdmTeacher->guru_foto)) {
                        $data['photo'] = $rdmTeacher->guru_foto;
                    }

                    if ($teacher) {
                        $teacher->update($data);
                        $stats['updated']++;
                    } else {
                        // Check if teacher exists by NIP to avoid duplicates
                        $existingTeacher = null;

                        if (!empty($rdmTeacher->guru_nip)) {
                            $existingTeacher = Teacher::where('nip', $rdmTeacher->guru_nip)
                                ->whereNull('rdm_id')
                                ->first();
                        }

                        // Fallback: Check by Name if NIP didn't match (case-insensitive search)
                        if (!$existingTeacher) {
                            $existingTeacher = Teacher::where('nama_lengkap', 'LIKE', $rdmTeacher->guru_nama)
                                ->whereNull('rdm_id')
                                ->first();
                        }

                        if ($existingTeacher) {
                            $existingTeacher->update($data);
                            $stats['updated']++;
                        } else {
                            Teacher::create($data);
                            $stats['created']++;
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('RDM Sync Teacher Error: ' . $e->getMessage(), [
                        'guru_id' => $rdmTeacher->guru_id,
                        'guru_nama' => $rdmTeacher->guru_nama
                    ]);
                    $stats['errors']++;
                }
            }
        } catch (\Exception $e) {
            Log::error('RDM Connection Error: ' . $e->getMessage());
            throw $e;
        }

        return $stats;
    }

    /**
     * Sync students from RDM database to Admin Madrasah
     */
    public function syncStudentsFromRdm(): array
    {
        $stats = ['created' => 0, 'updated' => 0, 'errors' => 0];

        try {
            // Get all active students from RDM
            $rdmStudents = DB::connection('rdm')
                ->table('e_siswa')
                ->where('siswa_aktif', 1)
                ->get();

            foreach ($rdmStudents as $rdmStudent) {
                try {
                    // Find by rdm_id or create new
                    $student = Student::where('rdm_id', $rdmStudent->siswa_id)->first();

                    // Map gender to match Enum ['Laki-laki', 'Perempuan']
                    $gender = null;
                    if (!empty($rdmStudent->siswa_gender)) {
                        $genderLower = strtolower($rdmStudent->siswa_gender);
                        if (str_contains($genderLower, 'laki') || $genderLower === 'l') {
                            $gender = 'Laki-laki';
                        } elseif (str_contains($genderLower, 'perempuan') || $genderLower === 'p') {
                            $gender = 'Perempuan';
                        }
                    }

                    // Get or guess Class/Rombel
                    // You might need to adjust this depending on RDM schema (e.g. siswa_kelas or rombel columns)
                    $kelas = $rdmStudent->kelas ?? '-';

                    $data = [
                        'rdm_id' => $rdmStudent->siswa_id,
                        'nama_lengkap' => $rdmStudent->siswa_nama,
                        'nis_lokal' => $rdmStudent->siswa_nis,
                        'nisn' => $rdmStudent->siswa_nisn ?? '-', // Fallback if missing
                        'gender' => $gender,
                        'tempat_lahir' => $rdmStudent->siswa_tempat,
                        'tanggal_lahir' => $rdmStudent->siswa_tgllahir,
                        'nama_ayah' => $rdmStudent->nama_ayah,
                        'nama_ibu' => $rdmStudent->nama_ibu,
                        'alamat_domisili' => $rdmStudent->siswa_alamat,
                        'alamat_kk' => $rdmStudent->siswa_alamat, // Use domisili as fallback for KK
                        'kelas' => $kelas, // Required field
                        'is_active' => true,
                        'status' => 'aktif',
                    ];

                    // Handle photo if exists
                    if (!empty($rdmStudent->siswa_foto)) {
                        $data['photo'] = $rdmStudent->siswa_foto;
                    }

                    if ($student) {
                        $student->update($data);
                        $stats['updated']++;
                    } else {
                        // Check if student exists by NIS to avoid duplicates
                        $existingByNis = Student::where('nis_lokal', $rdmStudent->siswa_nis)
                            ->whereNull('rdm_id')
                            ->first();

                        // Check equality to avoid unique violation on NISN if checking by NIS
                        if ($existingByNis) {
                            $existingByNis->update($data);
                            $stats['updated']++;
                        } else {
                            // Last check for NISN to prevent unique error
                            $existingByNisn = null;
                            if (!empty($data['nisn']) && $data['nisn'] !== '-') {
                                $existingByNisn = Student::where('nisn', $data['nisn'])
                                    ->whereNull('rdm_id')
                                    ->first();
                            }

                            if ($existingByNisn) {
                                $existingByNisn->update($data);
                                $stats['updated']++;
                            } else {
                                // FINAL FALLBACK: Check by Name AND Date of Birth
                                // This handles cases where NIS/NISN might be missing or different format
                                $existingByNameDob = Student::where('nama_lengkap', 'LIKE', $rdmStudent->siswa_nama)
                                    ->where('tanggal_lahir', $rdmStudent->siswa_tgllahir)
                                    ->whereNull('rdm_id')
                                    ->first();

                                if ($existingByNameDob) {
                                    $existingByNameDob->update($data);
                                    $stats['updated']++;
                                } else {
                                    Student::create($data);
                                    $stats['created']++;
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('RDM Sync Student Error: ' . $e->getMessage(), [
                        'siswa_id' => $rdmStudent->siswa_id,
                        'siswa_nama' => $rdmStudent->siswa_nama
                    ]);
                    $stats['errors']++;
                }
            }
        } catch (\Exception $e) {
            Log::error('RDM Connection Error: ' . $e->getMessage());
            throw $e;
        }

        return $stats;
    }

    /**
     * Run full sync (teachers + students)
     */
    public function syncAll(): array
    {
        return [
            'teachers' => $this->syncTeachersFromRdm(),
            'students' => $this->syncStudentsFromRdm(),
        ];
    }
}
