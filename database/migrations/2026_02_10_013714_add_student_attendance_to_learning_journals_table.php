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
        Schema::table('learning_journals', function (Blueprint $table) {
            $table->json('students_sakit')->nullable();
            $table->json('students_izin')->nullable();
            $table->json('students_alpha')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_journals', function (Blueprint $table) {
            $table->dropColumn(['students_sakit', 'students_izin', 'students_alpha']);
        });
    }
};
