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
            $table->dropColumn(['latitude', 'longitude']);
            $table->text('google_maps_embed')->nullable()->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_madrasahs', function (Blueprint $table) {
            $table->dropColumn('google_maps_embed');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
        });
    }
};
