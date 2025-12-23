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
        Schema::create('struktur_organisasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->string('nama')->nullable(); // Untuk anggota non-guru (misal: Ketua Komite)
            $table->string('jabatan_struktural'); // Kepala Madrasah, Ketua Komite, Waka Kurikulum, dll
            $table->string('photo')->nullable(); // Untuk anggota non-guru
            $table->integer('level')->default(1); // 1 = Top (Kepala), 2 = Staff (Waka, TU, Komite)
            $table->integer('urutan')->default(0); // Urutan dalam level yang sama
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('struktur_organisasis');
    }
};
