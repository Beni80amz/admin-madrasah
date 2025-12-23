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
            $table->string('stempel_madrasah')->nullable()->after('tanda_tangan_kepala_madrasah');
            $table->string('nip_kepala_madrasah')->nullable()->after('nama_kepala_madrasah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_madrasahs', function (Blueprint $table) {
            $table->dropColumn(['stempel_madrasah', 'nip_kepala_madrasah']);
        });
    }
};
