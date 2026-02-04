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
        Schema::create('fee_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained()->cascadeOnDelete();
            $table->string('name');           // Nama item biaya (e.g., "SPP Januari 2026")
            $table->decimal('amount', 15, 2); // Nominal biaya
            $table->enum('frequency', ['monthly', 'once'])->default('once');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_items');
    }
};
