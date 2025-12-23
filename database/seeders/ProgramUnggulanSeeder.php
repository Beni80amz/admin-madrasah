<?php

namespace Database\Seeders;

use App\Models\ProgramUnggulan;
use Illuminate\Database\Seeder;

class ProgramUnggulanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'nama' => "Tahfidz Al-Qur'an",
                'deskripsi' => 'Program intensif menghafal Al-Quran dengan target 30 Juz didampingi oleh mentor bersanad.',
                'icon' => 'menu_book',
                'urutan' => 1,
                'is_active' => true,
            ],
            [
                'nama' => 'Sains & Robotik',
                'deskripsi' => 'Mengembangkan logika dan kreativitas melalui pembelajaran coding, IoT, dan rekayasa robotika.',
                'icon' => 'smart_toy',
                'urutan' => 2,
                'is_active' => true,
            ],
            [
                'nama' => 'Kelas Bilingual',
                'deskripsi' => 'Penerapan Bahasa Arab dan Inggris dalam kegiatan sehari-hari untuk persiapan global.',
                'icon' => 'translate',
                'urutan' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($programs as $program) {
            ProgramUnggulan::updateOrCreate(
                ['nama' => $program['nama']],
                $program
            );
        }
    }
}
