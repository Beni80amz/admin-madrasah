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
        Schema::create('ppdb_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('no_daftar')->unique(); // e.g., PPDB-2024-001
            $table->string('nama_lengkap');
            $table->string('nisn')->nullable();
            $table->string('nik')->nullable();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama');
            $table->text('alamat');
            $table->string('asal_sekolah');
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('no_hp_ortu');
            $table->string('email')->nullable();
            $table->json('dokumen')->nullable(); // Paths to KK, Akta, Ijazah, Photo
            $table->enum('status', ['new', 'verified', 'rejected', 'accepted', 'enrolled'])->default('new');
            $table->text('catatan')->nullable(); // Admin verification notes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_registrations');
    }
};
