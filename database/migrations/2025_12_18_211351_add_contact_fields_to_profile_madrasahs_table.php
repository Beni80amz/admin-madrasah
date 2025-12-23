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
            $table->string('email')->nullable()->after('alamat');
            $table->string('no_hp')->nullable()->after('email');
            $table->string('whatsapp')->nullable()->after('no_hp');
            $table->string('facebook')->nullable()->after('whatsapp');
            $table->string('instagram')->nullable()->after('facebook');
            $table->string('youtube')->nullable()->after('instagram');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_madrasahs', function (Blueprint $table) {
            $table->dropColumn(['email', 'no_hp', 'whatsapp', 'facebook', 'instagram', 'youtube']);
        });
    }
};
