<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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

    protected static function booted()
    {
        static::saved(function ($profile) {
            if (!empty($profile->nama_kepala_madrasah) && !empty($profile->nip_kepala_madrasah)) {
                $user = User::firstOrNew(['email' => $profile->nip_kepala_madrasah]);

                if (!$user->exists) {
                    $user->name = $profile->nama_kepala_madrasah;
                    $user->password = \Illuminate\Support\Facades\Hash::make($profile->nip_kepala_madrasah); // NIP as default password
                    $user->save();

                    // Ensure role exists before assigning
                    $role = Role::firstOrCreate(['name' => 'teacher']);
                    $user->assignRole($role);
                } else {
                    // Update name if user exists but name might be different? 
                    // Let's just insure the name is updated if it matches the NIP
                    if ($user->name !== $profile->nama_kepala_madrasah) {
                        $user->name = $profile->nama_kepala_madrasah;
                        $user->save();
                    }
                }
            }
        });
    }
}
