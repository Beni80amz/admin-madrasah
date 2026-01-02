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
        if (Schema::hasColumn('profile_madrasahs', 'visi_misi')) {
            Schema::table('profile_madrasahs', function (Blueprint $table) {
                if (Schema::hasColumn('profile_madrasahs', 'visi')) {
                    $table->dropColumn('visi_misi');
                } else {
                    $table->renameColumn('visi_misi', 'visi');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_madrasahs', function (Blueprint $table) {
            $table->renameColumn('visi', 'visi_misi');
        });
    }
};
