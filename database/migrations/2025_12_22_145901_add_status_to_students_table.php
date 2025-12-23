<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->enum('status', ['aktif', 'lulus', 'mutasi_keluar'])->default('aktif')->after('is_active');
        });

        // Migrate existing data
        DB::table('students')->where('is_active', true)->update(['status' => 'aktif']);
        DB::table('students')->where('is_active', false)->update(['status' => 'aktif']); // Keeping as aktif, since we can't determine if they were lulus or mutasi
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
