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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_bill_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();  // Admin pencatat
            $table->decimal('amount_paid', 15, 2);
            $table->date('payment_date');
            $table->string('payment_method');  // Cash, Transfer, dll
            $table->string('receipt_number')->unique();
            $table->text('note')->nullable();
            $table->timestamps();

            // Index for common queries
            $table->index(['payment_date']);
            $table->index(['user_id', 'payment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
