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
            // Add foreign key columns
            $table->foreignId('jabatan_id')->nullable()->after('nip')->constrained('jabatans')->nullOnDelete();
            $table->foreignId('tugas_pokok_id')->nullable()->after('jabatan_id')->constrained('tugas_pokoks')->nullOnDelete();
            $table->foreignId('tugas_tambahan_id')->nullable()->after('tugas_pokok_id')->constrained('tugas_tambahans')->nullOnDelete();

            // Drop old string columns
            $table->dropColumn(['jabatan', 'tugas_pokok', 'tugas_tambahan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Add back old string columns
            $table->string('jabatan')->nullable()->after('nip');
            $table->string('tugas_pokok')->nullable()->after('jabatan');
            $table->string('tugas_tambahan')->nullable()->after('tugas_pokok');

            // Drop foreign key columns
            $table->dropConstrainedForeignId('jabatan_id');
            $table->dropConstrainedForeignId('tugas_pokok_id');
            $table->dropConstrainedForeignId('tugas_tambahan_id');
        });
    }
};
