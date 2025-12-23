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
            $table->foreignId('kelas_id')->nullable()->after('tugas_tambahan_id')->constrained('kelas')->nullOnDelete();
            $table->foreignId('rombel_id')->nullable()->after('kelas_id')->constrained('rombels')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['rombel_id']);
            $table->dropForeign(['kelas_id']);
            $table->dropColumn(['rombel_id', 'kelas_id']);
        });
    }
};
