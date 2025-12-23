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
        Schema::table('tahun_ajarans', function (Blueprint $table) {
            // Add new columns
            $table->integer('hari_efektif_ganjil')->default(100)->after('hari_efektif');
            $table->integer('hari_efektif_genap')->default(100)->after('hari_efektif_ganjil');

            // Drop old column
            $table->dropColumn('hari_efektif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tahun_ajarans', function (Blueprint $table) {
            $table->integer('hari_efektif')->default(200)->after('is_active');
            $table->dropColumn(['hari_efektif_ganjil', 'hari_efektif_genap']);
        });
    }
};
