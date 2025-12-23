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
        // Create siswa_masuk table
        Schema::create('siswa_masuk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->string('nama_lengkap');
            $table->string('sekolah_asal');
            $table->string('kelas_asal')->nullable();
            $table->date('tanggal_masuk');
            $table->text('alasan_pindah')->nullable();
            $table->string('nomor_surat_pindah')->nullable();
            $table->timestamps();
        });

        // Add mutasi_masuk to students status enum
        DB::statement("ALTER TABLE students MODIFY COLUMN status ENUM('aktif', 'lulus', 'mutasi_keluar', 'mutasi_masuk') DEFAULT 'aktif'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_masuk');

        DB::statement("ALTER TABLE students MODIFY COLUMN status ENUM('aktif', 'lulus', 'mutasi_keluar') DEFAULT 'aktif'");
    }
};
