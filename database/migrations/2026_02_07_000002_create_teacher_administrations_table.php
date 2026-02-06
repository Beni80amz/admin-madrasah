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
        Schema::create('teacher_administrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Kategori utama: perencanaan_pembelajaran, pelaksanaan_evaluasi, administrasi_pendukung
            $table->string('category');

            // Sub-kategori: rpp, prota, prosem, jurnal, absensi, dll
            $table->string('subcategory');

            // Informasi File
            $table->string('file_name');
            $table->string('google_drive_file_id');
            $table->text('file_url')->nullable();
            $table->text('web_view_link')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            // Status: draft, submitted, verified, rejected
            $table->string('status')->default('submitted');

            // Verifikasi
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();

            // Tahun Ajaran (opsional)
            $table->string('academic_year')->nullable();

            $table->timestamps();

            // Index untuk pencarian cepat
            $table->index(['user_id', 'category']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_administrations');
    }
};
