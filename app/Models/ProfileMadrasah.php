<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfileMadrasah extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_madrasah',
        'jenjang_id', // 1=RA, 2=MI, 3=MTs, 4=MA
        'motto',
        'nsm',
        'npsn',
        'tahun_berdiri',
        'alamat',
        'email',
        'no_hp',
        'whatsapp',
        'facebook',
        'instagram',
        'youtube',
        'google_maps_embed',
        'sejarah_singkat',
        'visi',
        'misi',
        'tujuan_madrasah',
        'logo',
        'nama_kepala_madrasah',
        'nip_kepala_madrasah',
        'foto_kepala_madrasah',
        'kata_pengantar',
        'tanda_tangan_kepala_madrasah',
        'stempel_madrasah',
        'running_text',
    ];

    /**
     * Get the active profile
     */
    public static function getActive()
    {
        return static::first();
    }
}
