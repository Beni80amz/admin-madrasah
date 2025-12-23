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
        Schema::table('alumni', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')->nullable()->after('id');
            $table->string('nis_lokal')->nullable()->after('nama_lengkap');
            $table->string('nisn')->nullable()->after('nis_lokal');
            $table->string('nik')->nullable()->after('nisn');
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable()->after('nik');
            $table->string('kelas_terakhir')->nullable()->after('gender');
            $table->string('tempat_lahir')->nullable()->after('kelas_terakhir');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('nama_ibu')->nullable()->after('tanggal_lahir');
            $table->string('nama_ayah')->nullable()->after('nama_ibu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropColumn([
                'student_id',
                'nis_lokal',
                'nisn',
                'nik',
                'gender',
                'kelas_terakhir',
                'tempat_lahir',
                'tanggal_lahir',
                'nama_ibu',
                'nama_ayah',
            ]);
        });
    }
};
