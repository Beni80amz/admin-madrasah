<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\AttendanceSetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AttendanceTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Attendance Settings
        $settings = [
            ['key' => 'office_lat', 'value' => '-6.4025', 'description' => 'Latitude Lokasi Kantor/Sekolah'],
            ['key' => 'office_long', 'value' => '106.7942', 'description' => 'Longitude Lokasi Kantor/Sekolah'],
            ['key' => 'radius_meter', 'value' => '100', 'description' => 'Radius Jangkauan Absensi (Meter)'],
            ['key' => 'work_start_time', 'value' => '07:00:00', 'description' => 'Jam Masuk Kerja/Sekolah'],
            ['key' => 'work_end_time', 'value' => '14:00:00', 'description' => 'Jam Pulang Kerja/Sekolah'],
            ['key' => 'late_tolerance_minutes', 'value' => '15', 'description' => 'Toleransi Keterlambatan (Menit)'],
            ['key' => 'awal_absen_masuk', 'value' => '06:00:00', 'description' => 'Waktu Mulai Absen Masuk'],
            ['key' => 'akhir_absen_masuk', 'value' => '11:30:00', 'description' => 'Batas Akhir Absen Masuk'],
            ['key' => 'awal_absen_pulang', 'value' => '14:30:00', 'description' => 'Waktu Mulai Absen Pulang'],
            ['key' => 'akhir_absen_pulang', 'value' => '20:00:00', 'description' => 'Batas Akhir Absen Pulang'],
        ];

        foreach ($settings as $setting) {
            AttendanceSetting::firstOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'description' => $setting['description']]
            );
        }
        $this->command->info('Attendance settings seeded/updated.');

        // 2. Create Roles if not exists
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            // Using firstOrCreate to avoid duplication and errors
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Siswa', 'guard_name' => 'web']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Guru', 'guard_name' => 'web']);
        }

        // 3. Create Users for Students
        $students = Student::whereNull('user_id')->get();
        if ($students->count() > 0) {
            $this->command->info('Creating users for ' . $students->count() . ' students...');
            foreach ($students as $student) {
                // Determine unique username/email
                $username = $student->nis_lokal ?? $student->nisn ?? 's_' . $student->id;

                // Check if user already exists
                $existingUser = User::where('name', $username)->first();

                if (!$existingUser) {
                    $user = User::create([
                        'name' => $username, // Login using NIS/NISN
                        'email' => $username . '@student.com', // Dummy email
                        'password' => Hash::make('password'), // Default password
                        // Add role assignment here if you use Spatie Permission or simple role column
                        // 'role' => 'siswa' 
                    ]);

                    // Assign Role if using Spatie
                    if (method_exists($user, 'assignRole')) {
                        $user->assignRole('Siswa');
                    }

                    $student->update(['user_id' => $user->id]);
                } else {
                    $student->update(['user_id' => $existingUser->id]);
                }
            }
        }

        // 3. Create Users for Teachers
        $teachers = Teacher::whereNull('user_id')->get();
        if ($teachers->count() > 0) {
            $this->command->info('Creating users for ' . $teachers->count() . ' teachers...');
            foreach ($teachers as $teacher) {
                $username = $teacher->nip ?? 't_' . $teacher->id;

                $existingUser = User::where('name', $username)->first();

                if (!$existingUser) {
                    $user = User::create([
                        'name' => $username, // Login using NIP
                        'email' => $username . '@teacher.com',
                        'password' => Hash::make('password'),
                        // 'role' => 'guru'
                    ]);

                    if (method_exists($user, 'assignRole')) {
                        $user->assignRole('Guru'); // Ensure role name matches your system
                    }

                    $teacher->update(['user_id' => $user->id]);
                } else {
                    $teacher->update(['user_id' => $existingUser->id]);
                }
            }
        }

        $this->command->info('Attendance seeding completed.');
    }
}
