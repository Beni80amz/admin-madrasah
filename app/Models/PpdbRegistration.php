<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbRegistration extends Model
{
    protected $fillable = [
        'no_daftar',
        'nama_lengkap',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'alamat_kk',
        'asal_sekolah',
        'nama_sekolah_asal',
        'nama_ayah',
        'nama_ibu',
        'no_hp_ortu',
        'email',
        'dokumen',
        'status',
        'catatan'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'dokumen' => 'array',
    ];
}
