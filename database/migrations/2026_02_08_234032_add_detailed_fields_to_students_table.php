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
        Schema::table('students', function (Blueprint $table) {
            $table->string('no_kk')->nullable()->after('nik');
            $table->string('nama_kepala_keluarga_diKK')->nullable()->after('no_kk');

            // Data Ayah
            $table->string('status_ayah')->nullable()->after('nama_ayah');
            $table->string('nik_ayah_kandung')->nullable()->after('status_ayah');
            $table->string('tempat_lahir_ayah_kandung')->nullable()->after('nik_ayah_kandung');
            $table->date('tgl_lahir_ayah_kandung')->nullable()->after('tempat_lahir_ayah_kandung');
            $table->string('pendidikan_ayah_kandung')->nullable()->after('tgl_lahir_ayah_kandung');
            $table->string('pekerjaan_ayah_kandung')->nullable()->after('pendidikan_ayah_kandung');
            $table->string('pekerjaan_ayah_kandung_lainnya')->nullable()->after('pekerjaan_ayah_kandung');

            // Data Ibu
            $table->string('status_ibu')->nullable()->after('nama_ibu');
            $table->string('nik_ibu')->nullable()->after('status_ibu');
            $table->string('tempat_lahir_ibu')->nullable()->after('nik_ibu');
            $table->date('tanggal_lahir_ibu')->nullable()->after('tempat_lahir_ibu');
            $table->string('pendidikan_ibu')->nullable()->after('tanggal_lahir_ibu');
            $table->string('pekerjaan_ibu')->nullable()->after('pendidikan_ibu');
            $table->string('pekerjaan_ibu_lainnya')->nullable()->after('pekerjaan_ibu');

            // Data Wali
            $table->string('nik_wali')->nullable()->after('pekerjaan_ibu_lainnya');
            $table->string('tempat_lahir_wali')->nullable()->after('nik_wali');
            $table->date('tanggal_lahir_wali')->nullable()->after('tempat_lahir_wali');
            $table->string('pendidikan_wali')->nullable()->after('tanggal_lahir_wali');
            $table->string('pekerjaan_wali')->nullable()->after('pendidikan_wali');
            $table->string('pekerjaan_wali_lainnya')->nullable()->after('pekerjaan_wali');

            // Ekonomi & Rumah
            $table->string('penghasilan_orangtua')->nullable()->after('pekerjaan_wali_lainnya');
            $table->string('status_rumah')->nullable()->after('penghasilan_orangtua');
            $table->string('status_rumah_lainnya')->nullable()->after('status_rumah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'no_kk',
                'nama_kepala_keluarga_diKK',
                'status_ayah',
                'nik_ayah_kandung',
                'tempat_lahir_ayah_kandung',
                'tgl_lahir_ayah_kandung',
                'pendidikan_ayah_kandung',
                'pekerjaan_ayah_kandung',
                'pekerjaan_ayah_kandung_lainnya',
                'status_ibu',
                'nik_ibu',
                'tempat_lahir_ibu',
                'tanggal_lahir_ibu',
                'pendidikan_ibu',
                'pekerjaan_ibu',
                'pekerjaan_ibu_lainnya',
                'nik_wali',
                'tempat_lahir_wali',
                'tanggal_lahir_wali',
                'pendidikan_wali',
                'pekerjaan_wali',
                'pekerjaan_wali_lainnya',
                'penghasilan_orangtua',
                'status_rumah',
                'status_rumah_lainnya',
            ]);
        });
    }
};
