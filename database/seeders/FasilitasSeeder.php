<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use Illuminate\Database\Seeder;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fasilitas = [
            ['nama' => 'Lab Komputer', 'icon' => 'computer', 'urutan' => 1],
            ['nama' => 'Lab IPA', 'icon' => 'science', 'urutan' => 2],
            ['nama' => "Masjid Jami'", 'icon' => 'mosque', 'urutan' => 3],
            ['nama' => 'Gedung Olahraga', 'icon' => 'sports_soccer', 'urutan' => 4],
            ['nama' => 'Perpustakaan Digital', 'icon' => 'library_books', 'urutan' => 5],
            ['nama' => 'Kantin Sehat', 'icon' => 'restaurant', 'urutan' => 6],
            ['nama' => 'UKS & Klinik', 'icon' => 'local_hospital', 'urutan' => 7],
            ['nama' => 'Free Wi-Fi Area', 'icon' => 'wifi', 'urutan' => 8],
        ];

        foreach ($fasilitas as $item) {
            Fasilitas::updateOrCreate(
                ['nama' => $item['nama']],
                array_merge($item, ['is_active' => true])
            );
        }
    }
}
