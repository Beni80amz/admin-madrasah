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
        Schema::disableForeignKeyConstraints();

        // Drop index and foreign key first
        try {
            DB::statement('ALTER TABLE jadwal_pelajarans DROP FOREIGN KEY jadwal_pelajarans_teacher_id_foreign');
        } catch (\Exception $e) {
        }

        try {
            DB::statement('ALTER TABLE jadwal_pelajarans DROP INDEX jadwal_teacher_index');
        } catch (\Exception $e) {
        }

        try {
            DB::statement('ALTER TABLE jadwal_pelajarans DROP INDEX jadwal_pelajarans_teacher_id_foreign');
        } catch (\Exception $e) {
        }

        // Modify column
        DB::statement('ALTER TABLE jadwal_pelajarans MODIFY teacher_id BIGINT UNSIGNED NULL');

        // Re-add foreign key and index
        DB::statement('ALTER TABLE jadwal_pelajarans ADD CONSTRAINT jadwal_pelajarans_teacher_id_foreign FOREIGN KEY (teacher_id) REFERENCES teachers (id) ON DELETE CASCADE');
        DB::statement('CREATE INDEX jadwal_teacher_index ON jadwal_pelajarans (tahun_ajaran_id, teacher_id, semester, hari, jam_ke)');

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pelajarans', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable(false)->change();
        });
    }
};
