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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();           // Admin pencatat
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete(); // Approver
            $table->string('transaction_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->string('recipient')->nullable();               // Penerima (vendor, karyawan, dll)
            $table->text('description')->nullable();
            $table->string('payment_method')->nullable();          // Cash, Transfer
            $table->string('reference_number')->nullable();        // Nomor invoice/referensi
            $table->string('attachment')->nullable();              // Foto bukti (optional)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->boolean('requires_approval')->default(false);  // > Rp 5.000.000
            $table->datetime('approved_at')->nullable();
            $table->timestamps();

            // Indexes for common queries
            $table->index(['transaction_date']);
            $table->index(['expense_category_id', 'transaction_date']);
            $table->index(['status', 'requires_approval']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
