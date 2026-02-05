<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleTestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define users to create
        $users = [
            [
                'name' => 'Waka Kurikulum',
                'email' => 'kurikulum@madrasah.sch.id',
                'role' => 'Kurikulum',
            ],
            [
                'name' => 'Waka Kesiswaan',
                'email' => 'kesiswaan@madrasah.sch.id',
                'role' => 'Kesiswaan',
            ],
            [
                'name' => 'Tim Konten',
                'email' => 'konten@madrasah.sch.id',
                'role' => 'Konten',
            ],
        ];

        $defaultPassword = Hash::make('password'); // Simple password for testing

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $defaultPassword,
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );

            // Ensure role exists before assigning
            $role = Role::firstOrCreate(['name' => $userData['role'], 'guard_name' => 'web']);

            $user->assignRole($role);

            $this->command->info("User '{$user->email}' created/updated with role '{$userData['role']}'");
        }
    }
}
