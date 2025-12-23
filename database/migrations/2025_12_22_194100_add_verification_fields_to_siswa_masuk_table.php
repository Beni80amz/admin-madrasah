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
        Schema::table('siswa_masuk', function (Blueprint $table) {
            // Status verifikasi
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('nomor_surat_pindah');
            $table->string('nomor_surat_penerimaan')->nullable()->after('status');
            $table->string('kelas_tujuan')->nullable()->after('nomor_surat_penerimaan');
            $table->timestamp('verified_at')->nullable()->after('kelas_tujuan');
            $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at');
            $table->text('catatan_verifikasi')->nullable()->after('verified_by');

            // Data pribadi siswa
            $table->string('nik')->nullable()->after('catatan_verifikasi');
            $table->string('nisn')->nullable()->after('nik');
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable()->after('nisn');
            $table->string('tempat_lahir')->nullable()->after('gender');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('nama_ayah')->nullable()->after('tanggal_lahir');
            $table->string('nama_ibu')->nullable()->after('nama_ayah');
            $table->string('nomor_mobile')->nullable()->after('nama_ibu');
            $table->text('alamat_domisili')->nullable()->after('nomor_mobile');
            $table->string('photo')->nullable()->after('alamat_domisili');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa_masuk', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'nomor_surat_penerimaan',
                'kelas_tujuan',
                'verified_at',
                'verified_by',
                'catatan_verifikasi',
                'nik',
                'nisn',
                'gender',
                'tempat_lahir',
                'tanggal_lahir',
                'nama_ayah',
                'nama_ibu',
                'nomor_mobile',
                'alamat_domisili',
                'photo',
            ]);
        });
    }
};
