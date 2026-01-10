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
                        $this->ensureUserExists($teacher, 'guru', $teacher->nip);
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
                            $this->ensureUserExists($existingTeacher, 'guru', $existingTeacher->nip);
                        } else {
                            $newTeacher = Teacher::create($data);
                            $stats['created']++;
                            $this->ensureUserExists($newTeacher, 'guru', $newTeacher->nip);
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
        set_time_limit(300); // 5 Minutes max execution
        $batchId = 'student_' . now()->format('YmdHis');
        $stats = ['created' => 0, 'updated' => 0, 'errors' => 0, 'error_details' => [], 'batch_id' => $batchId];

        try {
            // GET JENJANG FROM PROFILE
            $profile = \App\Models\ProfileMadrasah::getActive();
            $jenjangId = $profile?->jenjang_id ?? 2; // Default to MI if not set
            $jenjangNames = [1 => 'RA', 2 => 'MI', 3 => 'MTs', 4 => 'MA'];
            $jenjangName = $jenjangNames[$jenjangId] ?? 'Unknown';
            Log::info("RDM Sync: Using Jenjang ID: {$jenjangId} ({$jenjangName})");

            // DETERMINE CURRENT ACADEMIC YEAR
            $currentYear = DB::connection('rdm')->table('e_siswa')->max('tahunajaran_id');
            Log::info("RDM Sync: Detected Current Year: {$currentYear}");

            // FIXED QUERY:
            // 1. Join 'e_kelas' to get 'kelas_alias'. Use INNER JOIN to ensure student has a valid class.
            // 2. Filter by Current Year to remove alumni/history.
            $rdmStudents = DB::connection('rdm')
                ->table('e_siswa')
                ->join('e_kelas', 'e_siswa.kelas_id', '=', 'e_kelas.kelas_id') // Inner Join to enforce class existence
                ->leftJoin('e_tingkat', 'e_kelas.tingkat_id', '=', 'e_tingkat.tingkat_id') // Join to get Roman Grade Name
                ->select(
                    'e_siswa.*',
                    'e_kelas.kelas_alias as rdm_kelas_alias', // Explicit alias to avoid collision
                    'e_kelas.kelas_nama as rdm_kelas_nama',
                    'e_kelas.tingkat_id as rdm_tingkat_id',
                    'e_tingkat.tingkat_nama as rdm_tingkat_nama' // e.g. "I", "VI"
                )
                ->where('e_siswa.tahunajaran_id', $currentYear) // Validation: Must be this year's student
                // Filter by Jenjang from Profile (1=RA, 2=MI, 3=MTs, 4=MA)
                ->where('e_tingkat.jenjang_id', $jenjangId)
                ->where(function ($q) {
                    $q->whereNull('e_siswa.siswa_statuskel')
                        ->orWhere('e_siswa.siswa_statuskel', '')
                        ->orWhere('e_siswa.siswa_statuskel', '0');
                })
                ->get();

            Log::info("RDM Sync: Found {$rdmStudents->count()} active students after filter.");

            // Helper for Roman to Arabic
            $romanToArabic = function ($roman) {
                $romans = ['I' => 1, 'V' => 5, 'X' => 10, 'L' => 50, 'C' => 100];
                $roman = strtoupper(trim($roman));
                $result = 0;
                $length = strlen($roman);
                for ($i = 0; $i < $length; $i++) {
                    $current = $romans[$roman[$i]] ?? 0;
                    $next = ($i + 1 < $length) ? ($romans[$roman[$i + 1]] ?? 0) : 0;
                    if ($current < $next) {
                        $result -= $current;
                    } else {
                        $result += $current;
                    }
                }
                return $result > 0 ? (string) $result : $roman;
            };

            foreach ($rdmStudents as $rdmStudent) {
                try {
                    // Find by rdm_id or create new
                    $student = Student::where('rdm_id', $rdmStudent->siswa_id)->first();

                    // Map gender
                    $gender = null;
                    if (!empty($rdmStudent->siswa_gender)) {
                        $genderLower = strtolower($rdmStudent->siswa_gender);
                        if (str_contains($genderLower, 'laki') || $genderLower === 'l') {
                            $gender = 'Laki-laki';
                        } elseif (str_contains($genderLower, 'perempuan') || $genderLower === 'p') {
                            $gender = 'Perempuan';
                        }
                    }

                    // Get Class Name Logic:
                    // Priority 1: Use alias IF it looks complete (contains dash, e.g. "1-A")
                    // Priority 2: Construct from Tingkat Roman Name (e.g. "VI" -> "6" + "-" + "A" -> "6-A")
                    // Priority 3: Fallback
                    $kelas = $rdmStudent->rdm_kelas_alias;

                    if (empty($kelas) || !str_contains($kelas, '-')) {
                        // Use Roman Name (e.g. "VI") if available
                        if (!empty($rdmStudent->rdm_tingkat_nama) && !empty($rdmStudent->rdm_kelas_nama)) {
                            // Convert Roman "VI" to "6"
                            $gradeArabic = $romanToArabic($rdmStudent->rdm_tingkat_nama);
                            $kelas = "{$gradeArabic}-{$rdmStudent->rdm_kelas_nama}";
                        } elseif (!is_null($rdmStudent->rdm_tingkat_id)) {
                            // Fallback to ID-based logic if Roman name missing (ID 3 = Grade 1)
                            $grade = max(1, $rdmStudent->rdm_tingkat_id - 2);
                            $kelas = "{$grade}-{$rdmStudent->rdm_kelas_nama}";
                        } else {
                            // Fallback if construction fails
                            $kelas = $rdmStudent->rdm_kelas_nama ?? $rdmStudent->kelas ?? '-';
                        }
                    }

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
                        $this->ensureUserExists($student, 'siswa', $student->nis_lokal);
                    } else {
                        // Check if student exists by NIS to avoid duplicates
                        $existingByNis = Student::where('nis_lokal', $rdmStudent->siswa_nis)
                            ->whereNull('rdm_id')
                            ->first();

                        // Check equality to avoid unique violation on NISN if checking by NIS
                        if ($existingByNis) {
                            $existingByNis->update($data);
                            $stats['updated']++;
                            $this->ensureUserExists($existingByNis, 'siswa', $existingByNis->nis_lokal);
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
                                $this->ensureUserExists($existingByNisn, 'siswa', $existingByNisn->nis_lokal);
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
                                    $this->ensureUserExists($existingByNameDob, 'siswa', $existingByNameDob->nis_lokal);
                                } else {
                                    $newStudent = Student::create($data);
                                    $stats['created']++;
                                    $this->ensureUserExists($newStudent, 'siswa', $newStudent->nis_lokal);
                                }
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    $studentName = $rdmStudent->siswa_nama ?? 'N/A';
                    $studentNis = $rdmStudent->siswa_nis ?? 'N/A';
                    $studentKelas = $kelas ?? 'N/A';
                    $errorMsg = $e->getMessage();

                    Log::error('RDM Sync Student Error: ' . $errorMsg, [
                        'siswa_id' => $rdmStudent->siswa_id ?? 'N/A',
                        'siswa_nama' => $studentName
                    ]);

                    $stats['errors']++;

                    // Save to database for detailed view
                    try {
                        \App\Models\SyncErrorLog::create([
                            'sync_type' => 'student',
                            'batch_id' => $batchId,
                            'rdm_id' => $rdmStudent->siswa_id ?? null,
                            'nama' => $studentName,
                            'nis_nip' => $studentNis,
                            'kelas' => $studentKelas,
                            'error_type' => \App\Models\SyncErrorLog::parseErrorType($errorMsg),
                            'error_column' => \App\Models\SyncErrorLog::parseErrorColumn($errorMsg),
                            'error_message' => substr($errorMsg, 0, 500), // Limit length
                        ]);
                    } catch (\Throwable $logError) {
                        // Silently fail if logging fails
                    }

                    // Collect first 10 errors for notification display
                    if (count($stats['error_details']) < 10) {
                        $stats['error_details'][] = "{$studentName}: {$errorMsg}";
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('RDM Fatal Error: ' . $e->getMessage());
            // Do not throw, return error stats so UI doesn't crash
            $stats['errors']++;
            $stats['message'] = $e->getMessage();
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

    /**
     * Sync alumni from RDM database (students with kelas_id = -1)
     */
    public function syncAlumniFromRdm(): array
    {
        set_time_limit(300);
        $batchId = 'alumni_' . now()->format('YmdHis');
        $stats = ['created' => 0, 'updated' => 0, 'errors' => 0, 'error_details' => [], 'batch_id' => $batchId];

        try {
            // GET JENJANG FROM PROFILE
            $profile = \App\Models\ProfileMadrasah::getActive();
            $jenjangId = $profile?->jenjang_id ?? 2;
            $jenjangNames = [1 => 'RA', 2 => 'MI', 3 => 'MTs', 4 => 'MA'];
            $jenjangName = $jenjangNames[$jenjangId] ?? 'Unknown';
            Log::info("RDM Alumni Sync: Using Jenjang ID: {$jenjangId} ({$jenjangName})");

            // Query RDM for alumni (kelas_id = -1)
            $rdmAlumni = DB::connection('rdm')
                ->table('e_siswa')
                ->where('e_siswa.kelas_id', -1)
                ->where('e_siswa.tingkat_id', -1) // Double check: also tingkat_id = -1
                ->get();

            Log::info("RDM Alumni Sync: Found {$rdmAlumni->count()} alumni records.");

            foreach ($rdmAlumni as $rdmStudent) {
                try {
                    // Check if already exists in alumni table by RDM siswa_id
                    // We'll use nis_lokal as identifier since alumni table doesn't have rdm_id
                    $alumni = \App\Models\Alumni::where('nis_lokal', $rdmStudent->siswa_nis)->first();

                    // Map gender
                    $gender = null;
                    if (!empty($rdmStudent->siswa_gender)) {
                        $genderLower = strtolower($rdmStudent->siswa_gender);
                        if (str_contains($genderLower, 'laki') || $genderLower === 'l') {
                            $gender = 'Laki-laki';
                        } elseif (str_contains($genderLower, 'perempuan') || $genderLower === 'p') {
                            $gender = 'Perempuan';
                        }
                    }

                    // Extract tahun lulus from tahunajaran_id if available
                    // e.g., tahunajaran_id = 2025 means graduated in 2025
                    $tahunLulus = $rdmStudent->tahunajaran_id ?? null;

                    $data = [
                        'nama_lengkap' => $rdmStudent->siswa_nama,
                        'nis_lokal' => $rdmStudent->siswa_nis,
                        'nisn' => $rdmStudent->siswa_nisn ?? null,
                        'nik' => $rdmStudent->siswa_nik ?? null,
                        'gender' => $gender,
                        'kelas_terakhir' => 'VI', // Alumni from MI are usually from Grade 6
                        'tempat_lahir' => $rdmStudent->siswa_tempat ?? null,
                        'tanggal_lahir' => $rdmStudent->siswa_tgllahir ?? null,
                        'nama_ibu' => $rdmStudent->nama_ibu ?? null,
                        'nama_ayah' => $rdmStudent->nama_ayah ?? null,
                        'tahun_lulus' => $tahunLulus,
                        'alamat' => $rdmStudent->siswa_alamat ?? null,
                        'nomor_mobile' => $rdmStudent->siswa_hp ?? null,
                    ];

                    // Handle photo if exists
                    if (!empty($rdmStudent->siswa_foto)) {
                        $data['photo'] = $rdmStudent->siswa_foto;
                    }

                    if ($alumni) {
                        $alumni->update($data);
                        $stats['updated']++;
                    } else {
                        // Check if exists by name + tanggal_lahir to avoid duplicates
                        $existingByNameDob = \App\Models\Alumni::where('nama_lengkap', $rdmStudent->siswa_nama)
                            ->where('tanggal_lahir', $rdmStudent->siswa_tgllahir)
                            ->first();

                        if ($existingByNameDob) {
                            $existingByNameDob->update($data);
                            $stats['updated']++;
                        } else {
                            \App\Models\Alumni::create($data);
                            $stats['created']++;
                        }
                    }
                } catch (\Throwable $e) {
                    $alumniName = $rdmStudent->siswa_nama ?? 'N/A';
                    $alumniNis = $rdmStudent->siswa_nis ?? 'N/A';
                    $errorMsg = $e->getMessage();

                    Log::error('RDM Alumni Sync Error: ' . $errorMsg, [
                        'siswa_id' => $rdmStudent->siswa_id ?? 'N/A',
                        'siswa_nama' => $alumniName
                    ]);

                    $stats['errors']++;

                    // Save to error log
                    try {
                        \App\Models\SyncErrorLog::create([
                            'sync_type' => 'alumni',
                            'batch_id' => $batchId,
                            'rdm_id' => $rdmStudent->siswa_id ?? null,
                            'nama' => $alumniName,
                            'nis_nip' => $alumniNis,
                            'kelas' => 'Alumni',
                            'error_type' => \App\Models\SyncErrorLog::parseErrorType($errorMsg),
                            'error_column' => \App\Models\SyncErrorLog::parseErrorColumn($errorMsg),
                            'error_message' => substr($errorMsg, 0, 500),
                        ]);
                    } catch (\Throwable $logError) {
                        // Silently fail
                    }

                    if (count($stats['error_details']) < 10) {
                        $stats['error_details'][] = "{$alumniName}: {$errorMsg}";
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('RDM Alumni Fatal Error: ' . $e->getMessage());
            $stats['errors']++;
            $stats['message'] = $e->getMessage();
        }

        return $stats;
    }

    /**
     * Ensure a User account exists for the given model
     */
    private function ensureUserExists($model, string $role, string $username): void
    {
        try {
            if (empty($username) || $username === '-')
                return;

            // Create Role if not exists
            $roleModel = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $role]);

            // Find or Create User
            // Check by email (username)
            $user = \App\Models\User::where('email', $username)->first();

            if (!$user) {
                $user = \App\Models\User::create([
                    'name' => $model->nama_lengkap ?? $username,
                    'email' => $username,
                    'password' => \Illuminate\Support\Facades\Hash::make($username), // Default password = username
                    'email_verified_at' => now(),
                ]);
            }

            // Assign Role
            if (!$user->hasRole($role)) {
                $user->assignRole($role);
            }

            // Link to Model if not already linked
            if ($model->user_id !== $user->id) {
                $model->user_id = $user->id;
                $model->saveQuietly(); // Avoid triggering observers again
            }
        } catch (\Throwable $e) {
            Log::error("RDM User Auto-create failed for {$username}: " . $e->getMessage());
            // Do not re-throw, just log and continue syncing data
        }
    }
}
