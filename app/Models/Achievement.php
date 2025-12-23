<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo',
        'nama',
        'kelas',
        'type',
        'prestasi',
        'event',
        'peringkat',
        'tingkat',
        'kategori',
        'jenis',
        'tahun',
        'deskripsi',
    ];
}
