<?php

namespace App\Filament\Resources\Teachers\Pages;

use App\Filament\Resources\Teachers\TeacherResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $teacher = $this->record;

        // Determine password and email
        $password = $teacher->nip ?? $teacher->nik ?? '12345678';
        $email = $teacher->nip ? $teacher->nip . '@teacher.com' : ($teacher->nik ? $teacher->nik . '@teacher.com' : uniqid() . '@teacher.com');

        // Check if user already exists
        $existingUser = \App\Models\User::where('email', $email)->first();

        if (!$existingUser) {
            $user = \App\Models\User::create([
                'name' => $teacher->nama_lengkap,
                'email' => $email,
                'password' => \Illuminate\Support\Facades\Hash::make($password),
                'email_verified_at' => now(),
            ]);

            // Assign role
            $user->assignRole('teacher');

            // Link user to teacher
            $teacher->user_id = $user->id;
            $teacher->save();

            $this->dispatch('swal:success', [
                'title' => 'Data Tersimpan!',
                'text' => 'Data guru dan pengguna berhasil ditambahkan. Password: ' . $password,
            ]);
        } else {
            // If user exists, try to link it if not already linked
            if (!$teacher->user_id) {
                $teacher->user_id = $existingUser->id;
                $teacher->save();
            }

            $this->dispatch('swal:success', [
                'title' => 'Data Tersimpan!',
                'text' => 'Data guru berhasil ditambahkan. User sudah ada.',
            ]);
        }
    }
}
