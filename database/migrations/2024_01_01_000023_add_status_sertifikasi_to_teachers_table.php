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
        Schema::table('teachers', function (Blueprint $table) {
            $table->enum('status', ['PNS', 'Non PNS', 'P3K'])->default('Non PNS')->after('tugas_tambahan');
            $table->enum('sertifikasi', ['Sudah', 'Belum'])->default('Belum')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['status', 'sertifikasi']);
        });
    }
};
