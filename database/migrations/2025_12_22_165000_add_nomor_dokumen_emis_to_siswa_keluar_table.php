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
        Schema::table('siswa_keluar', function (Blueprint $table) {
            $table->string('nomor_dokumen_emis')->nullable()->after('nomor_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa_keluar', function (Blueprint $table) {
            $table->dropColumn('nomor_dokumen_emis');
        });
    }
};
