<?php

namespace App\Observers;

use App\Models\Jabatan;
use App\Models\ProfileMadrasah;
use App\Models\Teacher;
use App\Models\TugasPokok;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ProfileMadrasahObserver
{
    /**
     * Handle the ProfileMadrasah "saved" event.
     */
    public function saved(ProfileMadrasah $profileMadrasah): void
    {
        if (!empty($profileMadrasah->nama_kepala_madrasah) && !empty($profileMadrasah->nip_kepala_madrasah)) {
            $this->syncUser($profileMadrasah);
            $this->syncTeacher($profileMadrasah);
        }
    }

    protected function syncUser(ProfileMadrasah $profile): void
    {
        $user = User::firstOrNew(['email' => $profile->nip_kepala_madrasah]);

        if (!$user->exists) {
            $user->name = $profile->nama_kepala_madrasah;
            $user->password = Hash::make($profile->nip_kepala_madrasah); // NIP as default password
            $user->save();

            // Ensure role exists before assigning
            $role = Role::firstOrCreate(['name' => 'teacher']);
            $user->assignRole($role);
        } else {
            if ($user->name !== $profile->nama_kepala_madrasah) {
                $user->name = $profile->nama_kepala_madrasah;
                $user->save();
            }
        }
    }

    protected function syncTeacher(ProfileMadrasah $profile): void
    {
        // 1. Ensure Jabatan exists
        $jabatan = Jabatan::firstOrCreate(
            ['nama' => 'Kepala Madrasah'],
            ['keterangan' => 'Pimpinan Madrasah']
        );

        // 2. Ensure Tugas Pokok exists
        $tugasPokok = TugasPokok::firstOrCreate(
            ['nama' => 'Kepala Madrasah'],
            ['keterangan' => 'Tugas Utama Memimpin Madrasah']
        );

        // 3. Find or Create Teacher
        $teacher = Teacher::firstOrNew(['nip' => $profile->nip_kepala_madrasah]);

        $teacher->nama_lengkap = $profile->nama_kepala_madrasah;
        $teacher->jabatan_id = $jabatan->id;
        $teacher->tugas_pokok_id = $tugasPokok->id;

        // Only update photo if the profile has one, otherwise keep existing or null
        if ($profile->foto_kepala_madrasah) {
            $teacher->photo = $profile->foto_kepala_madrasah;
        }

        // Set defaults for new records
        if (!$teacher->exists) {
            $teacher->status = 'Non PNS'; // Default
            $teacher->sertifikasi = 'Belum'; // Default
            $teacher->is_active = true;
        }

        // Link to User if exists based on NIP (email)
        $user = User::where('email', $profile->nip_kepala_madrasah)->first();
        if ($user) {
            $teacher->user_id = $user->id;
        }

        $teacher->save();
    }
}
