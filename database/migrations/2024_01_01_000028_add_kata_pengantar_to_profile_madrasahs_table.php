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
            $table->string('foto_kepala_madrasah')->nullable()->after('nama_kepala_madrasah');
            $table->text('kata_pengantar')->nullable()->after('foto_kepala_madrasah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_madrasahs', function (Blueprint $table) {
            $table->dropColumn(['foto_kepala_madrasah', 'kata_pengantar']);
        });
    }
};
