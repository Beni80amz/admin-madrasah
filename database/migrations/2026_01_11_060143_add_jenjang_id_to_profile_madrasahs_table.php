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
            // Jenjang ID from RDM e_tingkat: 1=RA, 2=MI, 3=MTs, 4=MA
            $table->unsignedTinyInteger('jenjang_id')->default(2)->after('nama_madrasah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_madrasahs', function (Blueprint $table) {
            $table->dropColumn('jenjang_id');
        });
    }
};
