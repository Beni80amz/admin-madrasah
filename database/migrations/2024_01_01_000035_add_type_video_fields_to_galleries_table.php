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
        Schema::table('galleries', function (Blueprint $table) {
            $table->enum('type', ['photo', 'video'])->default('photo')->after('id');
            $table->string('video_url')->nullable()->after('image');
            $table->boolean('is_active')->default(true)->after('is_featured');
            $table->integer('urutan')->default(0)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn(['type', 'video_url', 'is_active', 'urutan']);
        });
    }
};
