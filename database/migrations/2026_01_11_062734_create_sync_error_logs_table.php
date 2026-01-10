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
        Schema::create('sync_error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('sync_type')->default('student'); // student, teacher
            $table->string('batch_id')->nullable(); // Group errors by sync session
            $table->string('rdm_id')->nullable(); // ID from RDM
            $table->string('nama')->nullable(); // Student/Teacher name
            $table->string('nis_nip')->nullable(); // NIS for student, NIP for teacher
            $table->string('kelas')->nullable(); // Class (for students)
            $table->string('error_type')->nullable(); // null_column, duplicate, validation, etc.
            $table->string('error_column')->nullable(); // Which column caused error
            $table->text('error_message'); // Full error message
            $table->boolean('is_resolved')->default(false); // Mark as resolved after fixing
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_error_logs');
    }
};
