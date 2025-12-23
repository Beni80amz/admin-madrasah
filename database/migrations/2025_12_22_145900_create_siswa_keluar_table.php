<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('siswa_keluar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->string('photo')->nullable();
            $table->string('nama_lengkap');
            $table->string('nis_lokal');
            $table->string('nisn');
            $table->string('nik')->nullable();
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->string('kelas_terakhir');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('nama_ibu');
            $table->string('nama_ayah');
            $table->string('nomor_mobile')->nullable();
            $table->text('alamat');
            $table->date('tanggal_keluar');
            $table->text('alasan_keluar')->nullable();
            $table->string('sekolah_tujuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_keluar');
    }
};
