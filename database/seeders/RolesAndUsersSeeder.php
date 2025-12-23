<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Superadmin']);
        $ppdbAdminRole = Role::firstOrCreate(['name' => 'Admin PPDB']);

        // 2. Assign Superadmin role to existing admin
        $admin = User::where('email', 'admin@madrasah.sch.id')->first();
        if ($admin) {
            $admin->assignRole($superAdminRole);
            $this->command->info("Role 'Superadmin' assigned to {$admin->email}");
        } else {
            $this->command->error("User admin@madrasah.sch.id not found!");
        }

        // 3. Create Admin PPDB User and assign role
        $ppdbUser = User::updateOrCreate(
            ['email' => 'adminppdb@madrasah.sch.id'],
            [
                'name' => 'Admin PPDB',
                'password' => Hash::make('@#AdminPpdb@#'),
                'email_verified_at' => now(),
            ]
        );

        $ppdbUser->assignRole($ppdbAdminRole);
        $this->command->info("User '{$ppdbUser->email}' created/updated with role 'Admin PPDB'");
    }
}
