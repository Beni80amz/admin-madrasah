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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('nama_lengkap');
            $table->string('nis_lokal')->unique();
            $table->string('nisn')->unique();
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->string('kelas');
            $table->string('nama_ibu');
            $table->string('nama_ayah');
            $table->text('alamat_kk');
            $table->text('alamat_domisili')->nullable();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
