<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('mata_pelajarans')->insert([
            ['nama' => 'Upacara Bendera', 'kode' => 'UBR', 'kelompok' => 'Umum', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Shalat Dluha', 'kode' => 'SHD', 'kelompok' => 'Umum', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Istirahat', 'kode' => 'IST', 'kelompok' => 'Umum', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Soliskan', 'kode' => 'SOL', 'kelompok' => 'Umum', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('mata_pelajarans')->whereIn('nama', [
            'Upacara Bendera',
            'Shalat Dluha',
            'Istirahat',
            'Soliskan'
        ])->delete();
    }
};
