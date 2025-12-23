<?php

namespace Database\Seeders;

use App\Models\OperationalHour;
use Illuminate\Database\Seeder;

class OperationalHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hours = [
            [
                'hari' => "Senin - Jum'at",
                'waktu' => '07:00 - 16:00 WIB',
                'is_libur' => false,
                'urutan' => 1,
                'is_active' => true,
            ],
            [
                'hari' => 'Sabtu',
                'waktu' => '07:00 - 12:00 WIB',
                'is_libur' => false,
                'urutan' => 2,
                'is_active' => true,
            ],
            [
                'hari' => 'Minggu',
                'waktu' => 'Libur',
                'is_libur' => true,
                'urutan' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($hours as $hour) {
            OperationalHour::updateOrCreate(
                ['hari' => $hour['hari']],
                $hour
            );
        }
    }
}
