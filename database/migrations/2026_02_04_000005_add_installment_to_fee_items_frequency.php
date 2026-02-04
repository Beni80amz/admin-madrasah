<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alter ENUM to add 'installment' option
        DB::statement("ALTER TABLE fee_items MODIFY COLUMN frequency ENUM('monthly', 'installment', 'once') DEFAULT 'once'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original ENUM
        DB::statement("ALTER TABLE fee_items MODIFY COLUMN frequency ENUM('monthly', 'once') DEFAULT 'once'");
    }
};
