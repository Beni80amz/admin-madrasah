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
        Schema::create('mata_pelajarans', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // contoh: MTK, BHS, IPA
            $table->string('nama'); // contoh: Matematika, Bahasa Indonesia
            $table->string('kelompok'); // Umum, Agama, Muatan Lokal
            $table->integer('kkm')->default(75); // Kriteria Ketuntasan Minimal
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_pelajarans');
    }
};
