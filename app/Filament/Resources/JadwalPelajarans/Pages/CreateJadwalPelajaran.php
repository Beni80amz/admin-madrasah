<?php

namespace App\Filament\Resources\JadwalPelajarans\Pages;

use App\Filament\Resources\JadwalPelajarans\JadwalPelajaranResource;
use App\Models\JadwalPelajaran;
use Filament\Resources\Pages\CreateRecord;

class CreateJadwalPelajaran extends CreateRecord
{
    protected static string $resource = JadwalPelajaranResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected function beforeCreate(): void
    {
        $data = $this->data;

        // Check for teacher conflict
        if (
            JadwalPelajaran::hasTeacherConflict(
                $data['teacher_id'],
                $data['tahun_ajaran_id'],
                $data['semester'],
                $data['hari'],
                $data['jam_ke']
            )
        ) {
            $conflict = JadwalPelajaran::getTeacherConflict(
                $data['teacher_id'],
                $data['tahun_ajaran_id'],
                $data['semester'],
                $data['hari'],
                $data['jam_ke']
            );

            $rombelName = $conflict->rombel?->kelas?->nama . ' - ' . $conflict->rombel?->nama;
            $mapelName = $conflict->mataPelajaran?->nama;

            $this->dispatch('swal:error', [
                'title' => 'Jadwal Bentrok!',
                'text' => "Guru sudah terjadwal mengajar di kelas {$rombelName} untuk mata pelajaran {$mapelName} pada waktu yang sama.",
            ]);

            $this->halt();
        }
    }

    protected function afterCreate(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Jadwal Dibuat!',
            'text' => 'Jadwal pelajaran berhasil ditambahkan.',
        ]);
    }
}
