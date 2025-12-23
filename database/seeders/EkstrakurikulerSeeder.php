<?php

namespace Database\Seeders;

use App\Models\Ekstrakurikuler;
use Illuminate\Database\Seeder;

class EkstrakurikulerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Olahraga',
                'deskripsi' => 'Futsal, Basket, Voli, Badminton',
                'urutan' => 1,
            ],
            [
                'nama' => 'Pramuka',
                'deskripsi' => 'Membentuk karakter mandiri dan disiplin',
                'urutan' => 2,
            ],
            [
                'nama' => 'Seni & Budaya',
                'deskripsi' => 'Marawis, Hadroh, Kaligrafi',
                'urutan' => 3,
            ],
        ];

        foreach ($data as $item) {
            Ekstrakurikuler::updateOrCreate(
                ['nama' => $item['nama']],
                array_merge($item, ['is_active' => true])
            );
        }
    }
}
