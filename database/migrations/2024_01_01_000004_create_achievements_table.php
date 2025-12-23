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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('nama');
            $table->enum('type', ['siswa', 'guru']);
            $table->string('prestasi');
            $table->string('tingkat'); // Kota, Provinsi, Nasional, Internasional
            $table->string('kategori'); // Akademik, Olahraga, Seni, dll
            $table->string('tahun');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
