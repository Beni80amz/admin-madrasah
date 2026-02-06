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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('income_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();  // Admin pencatat
            $table->foreignId('fee_category_id')->nullable()->constrained()->nullOnDelete(); // Link ke Madrasah Pay kategori
            $table->string('transaction_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->string('period')->nullable();          // Periode akumulasi (misal: "Januari 2026")
            $table->string('source')->nullable();          // Sumber dana (donatur, instansi, dll)
            $table->text('description')->nullable();
            $table->string('payment_method')->nullable();  // Cash, Transfer, QRIS
            $table->string('receipt_number')->nullable();  // Nomor kwitansi
            $table->string('attachment')->nullable();      // Foto bukti (optional)
            $table->boolean('is_synced')->default(false);  // Dari Madrasah Pay aggregate
            $table->timestamps();

            // Indexes for common queries
            $table->index(['transaction_date']);
            $table->index(['income_category_id', 'transaction_date']);
            $table->index(['is_synced', 'fee_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
