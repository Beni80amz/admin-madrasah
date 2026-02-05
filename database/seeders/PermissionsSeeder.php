<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // -- Define Roles --
        // Use existing names where possible to avoid duplicates
        $roles = [
            'Superadmin' => [], // Will get all permissions via Gate logic or direct assignment
            'Kurikulum' => [
                'profile_madrasah',
                'tahun_ajaran',
                'jabatan',
                'tugas_pokok',
                'tugas_tambahan',
                'mata_pelajaran',
                'teacher',
                'jadwal_pelajaran',
                'struktur_organisasi',
                'program_unggulan',
                'fasilitas',
                'ekstrakurikuler',
                'academic_calendar',
                'holiday',
                'attendance_setting',
                'operational_hour',
                'attendance',
                'leave_request'
            ],
            'Admin Keuangan' => [
                'student',
                'fee_category',
                'fee_item',
                'student_bill',
                'payment'
            ],
            'Admin PPDB' => [ // Matching existing 'Admin PPDB' role
                'app_setting',
                'ppdb_registration'
            ],
            'Kesiswaan' => [
                'kelas',
                'rombel',
                'student',
                'alumni',
                'siswa_masuk',
                'siswa_keluar',
                'student_promotion'
            ],
            'Konten' => [
                'achievement',
                'gallery'
            ],
            // Preserve existing roles (no specific permissions assigned here, strictly preserving existence)
            'Guru' => [],
            'Teacher' => [],
            'Siswa' => [],
            'Staff' => [],
        ];

        // -- Create Permissions --
        // Map 'resource_key' to specific capabilities
        // Standard Filament generic permissions: view_any, view, create, update, delete, delete_any, restore, force_delete
        // For Pages (like app_setting), we might just need 'view_any' or 'view'

        $capabilities = [
            'view_any',
            'view',
            'create',
            'update',
            'delete',
            'delete_any',
            'restore',
            'force_delete'
        ];

        foreach ($roles as $roleName => $resources) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            if ($roleName === 'Superadmin') {
                // Superadmin gets everything (handled via Gate::before typically)
                continue;
            }

            $permissions = []; // Collect permissions for this role

            foreach ($resources as $resource) {
                foreach ($capabilities as $capability) {
                    $permissionName = "{$capability}_{$resource}";

                    // Create permission if it doesn't exist
                    $permission = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);

                    $permissions[] = $permission;
                }
            }

            // Sync permissions to ensure role ONLY has what is defined here (removes extras)
            if (!empty($permissions)) {
                $role->syncPermissions($permissions);
            }
        }

        // Extra step: Ensure generic permissions exist for all known resources to avoid 'Permission does not exist' errors
        // during policy checks if we add more checks later.
        $allResources = [
            'profile_madrasah',
            'tahun_ajaran',
            'jabatan',
            'tugas_pokok',
            'tugas_tambahan',
            'mata_pelajaran',
            'teacher',
            'jadwal_pelajaran',
            'struktur_organisasi',
            'program_unggulan',
            'fasilitas',
            'ekstrakurikuler',
            'academic_calendar',
            'holiday',
            'attendance_setting',
            'operational_hour',
            'attendance',
            'leave_request',
            'student',
            'fee_category',
            'fee_item',
            'student_bill',
            'payment',
            'app_setting',
            'ppdb_registration',
            'kelas',
            'rombel',
            'alumni',
            'siswa_masuk',
            'siswa_keluar',
            'student_promotion',
            'achievement',
            'gallery',
            'link_pendataan'
        ];

        foreach ($allResources as $res) {
            foreach ($capabilities as $cap) {
                Permission::firstOrCreate(['name' => "{$cap}_{$res}", 'guard_name' => 'web']);
            }
        }

        $this->command->info('Permissions seeded successfully.');
    }
}
