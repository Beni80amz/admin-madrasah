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
        Schema::create('profile_madrasahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_madrasah');
            $table->string('nsm')->nullable(); // Nomor Statistik Madrasah
            $table->string('npsn')->nullable(); // Nomor Pokok Sekolah Nasional
            $table->string('tahun_berdiri')->nullable();
            $table->text('alamat')->nullable();
            $table->longText('sejarah_singkat')->nullable();
            $table->longText('visi_misi')->nullable();
            $table->longText('tujuan_madrasah')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_madrasahs');
    }
};
