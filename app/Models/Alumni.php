<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumni';

    protected $fillable = [
        'student_id',
        'photo',
        'nama_lengkap',
        'nis_lokal',
        'nisn',
        'nik',
        'gender',
        'kelas_terakhir',
        'tempat_lahir',
        'tanggal_lahir',
        'nama_ibu',
        'nama_ayah',
        'tahun_lulus',
        'alamat',
        'nomor_mobile',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
