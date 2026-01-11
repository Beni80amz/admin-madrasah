<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetRdmData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rdm:reset {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate Teachers, Students, and their associated Users (Role: Guru/Siswa) for a clean sync.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->confirm('PERINGATAN: Perintah ini akan MENGHAPUS SEMUA data Guru, Siswa, dan User (Guru/Siswa) di database lokal. Data akan hilang permanen sampai Anda melakukan Sync ulang dari RDM. Apakah Anda yakin?', $this->option('force'))) {
            $this->info('Operasi dibatalkan.');
            return;
        }

        $this->info('Memulai proses reset data...');

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // 1. Truncate Tables
            $this->info('Mengosongkan tabel Teachers...');
            Teacher::truncate();

            $this->info('Mengosongkan tabel Students...');
            Student::truncate();

            // 2. Delete Users with specific roles (safeguard Admin)
            $this->info('Menghapus User dengan role Guru dan Siswa...');
            // Ensure we don't delete users who might be admins but accidentally have other roles
            // Better strategy: Delete users who ONLY have 'guru' or 'siswa' roles, or just delete by role match
            // Safest: Delete users where ID is linked to the truncated teachers/students?
            // But the links might be broken (that's why we are resetting).
            // So relying on Roles is best.

            $count = 0;
            User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['guru', 'siswa']);
            })->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['super_admin', 'admin', 'operator']); // Exclude privileged roles
            })->chunk(100, function ($users) use (&$count) {
                foreach ($users as $user) {
                    $user->delete();
                    $count++;
                }
            });

            $this->info("Berhasil menghapus {$count} User (Guru/Siswa).");

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->info('Reset Data Selesai! Silakan jalankan Sinkronisasi RDM ulang.');

        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
