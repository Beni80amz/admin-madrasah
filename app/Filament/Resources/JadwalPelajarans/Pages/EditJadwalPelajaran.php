<?php

namespace App\Filament\Resources\JadwalPelajarans\Pages;

use App\Filament\Resources\JadwalPelajarans\JadwalPelajaranResource;
use App\Models\JadwalPelajaran;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJadwalPelajaran extends EditRecord
{
    protected static string $resource = JadwalPelajaranResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->after(function () {
                    $this->dispatch('swal:success', [
                        'title' => 'Jadwal Dihapus!',
                        'text' => 'Jadwal pelajaran berhasil dihapus.',
                    ]);
                }),
        ];
    }

    protected function beforeSave(): void
    {
        $data = $this->data;

        // Check for teacher conflict (excluding current record)
        if (
            JadwalPelajaran::hasTeacherConflict(
                $data['teacher_id'],
                $data['tahun_ajaran_id'],
                $data['semester'],
                $data['hari'],
                $data['jam_ke'],
                $this->record->id
            )
        ) {
            $conflict = JadwalPelajaran::getTeacherConflict(
                $data['teacher_id'],
                $data['tahun_ajaran_id'],
                $data['semester'],
                $data['hari'],
                $data['jam_ke'],
                $this->record->id
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

    protected function afterSave(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Jadwal Diperbarui!',
            'text' => 'Jadwal pelajaran berhasil diperbarui.',
        ]);
    }
}
