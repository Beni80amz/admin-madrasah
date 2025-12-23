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
        Schema::table('profile_madrasahs', function (Blueprint $table) {
            $table->string('nama_kepala_madrasah')->nullable();
            $table->string('tanda_tangan_kepala_madrasah')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_madrasahs', function (Blueprint $table) {
            $table->dropColumn(['nama_kepala_madrasah', 'tanda_tangan_kepala_madrasah']);
        });
    }
};
