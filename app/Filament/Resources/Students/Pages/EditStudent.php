<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentResource;
use App\Models\Alumni;
use App\Models\SiswaKeluar;
use App\Models\Student;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        /** @var Student $record */
        $user = auth()->user();

        // If not admin/kurikulum, intercept sensitive changes
        if (!$user->hasAnyRole(['super_admin', 'admin', 'Superadmin', 'Admin', 'Kurikulum'])) {
            $excludeFields = ['rdm_id', 'photo', 'is_active', 'status', 'user_id', 'tahun_ajaran_id', 'kelas', 'created_at', 'updated_at'];
            $pendingChanges = [];

            foreach ($data as $field => $value) {
                // Only track fields that are in fillable and not excluded
                if ($record->isFillable($field) && !in_array($field, $excludeFields)) {
                    $oldValue = $record->getOriginal($field);
                    $newValue = $value;

                    // Normalize for comparison
                    $normalizedOld = is_null($oldValue) ? '' : (string) $oldValue;
                    $normalizedNew = is_null($newValue) ? '' : (string) $newValue;

                    if ($normalizedOld !== $normalizedNew) {
                        $pendingChanges[$field] = [
                            'old' => $oldValue,
                            'new' => $newValue,
                        ];
                        // Revert the field in the data array to original so the record->update($data) doesn't change it yet
                        $data[$field] = $oldValue;
                    }
                }
            }

            if (!empty($pendingChanges)) {
                \App\Models\StudentUpdateAction::create([
                    'student_id' => $record->id,
                    'user_id' => $user->id,
                    'changes' => $pendingChanges,
                    'status' => 'pending',
                ]);

                $this->dispatch('swal:info', [
                    'title' => 'Verifikasi Diperlukan',
                    'text' => 'Perubahan pada data kritis telah diajukan dan menunggu persetujuan Admin.',
                ]);
            }
        }

        $record->update($data);

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->after(function () {
                    $this->dispatch('swal:success', [
                        'title' => 'Data Dihapus!',
                        'text' => 'Data siswa berhasil dihapus dari database.',
                    ]);
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Store additional fields for processing in afterSave
        // Lulus fields
        $this->tahunLulus = $data['tahun_lulus'] ?? null;

        // Mutasi Keluar fields
        $this->tanggalKeluar = $data['tanggal_keluar'] ?? null;
        $this->alasanKeluar = $data['alasan_keluar'] ?? null;
        $this->sekolahTujuan = $data['sekolah_tujuan'] ?? null;
        $this->nomorDokumenEmis = $data['nomor_dokumen_emis'] ?? null;

        // Remove non-database fields
        unset(
            $data['tahun_lulus'],
            $data['tanggal_keluar'],
            $data['alasan_keluar'],
            $data['sekolah_tujuan'],
            $data['nomor_dokumen_emis']
        );

        return $data;
    }

    protected function afterSave(): void
    {
        $student = $this->record;

        // Handle status change to Lulus
        if ($student->status === Student::STATUS_LULUS) {
            // Check if already exists in alumni
            $existingAlumni = Alumni::where('student_id', $student->id)->first();

            if (!$existingAlumni) {
                Alumni::create([
                    'student_id' => $student->id,
                    'photo' => $student->photo,
                    'nama_lengkap' => $student->nama_lengkap,
                    'nis_lokal' => $student->nis_lokal,
                    'nisn' => $student->nisn,
                    'nik' => $student->nik,
                    'gender' => $student->gender,
                    'kelas_terakhir' => $student->kelas,
                    'tempat_lahir' => $student->tempat_lahir,
                    'tanggal_lahir' => $student->tanggal_lahir,
                    'nama_ibu' => $student->nama_ibu,
                    'nama_ayah' => $student->nama_ayah,
                    'tahun_lulus' => $this->tahunLulus ?? date('Y'),
                    'alamat' => $student->alamat_kk,
                    'nomor_mobile' => $student->nomor_mobile,
                ]);

                // Set student as inactive
                $student->update(['is_active' => false]);

                $this->dispatch('swal:success', [
                    'title' => 'Status Diperbarui!',
                    'text' => 'Data siswa berhasil diperbarui dan dipindahkan ke data Alumni.',
                ]);
                return;
            }
        }

        // Handle status change to Mutasi Keluar
        if ($student->status === Student::STATUS_MUTASI_KELUAR) {
            // Check if already exists in siswa_keluar
            $existingSiswaKeluar = SiswaKeluar::where('student_id', $student->id)->first();

            if (!$existingSiswaKeluar) {
                SiswaKeluar::create([
                    'student_id' => $student->id,
                    'photo' => $student->photo,
                    'nama_lengkap' => $student->nama_lengkap,
                    'nis_lokal' => $student->nis_lokal,
                    'nisn' => $student->nisn,
                    'nik' => $student->nik,
                    'gender' => $student->gender,
                    'kelas_terakhir' => $student->kelas,
                    'tempat_lahir' => $student->tempat_lahir,
                    'tanggal_lahir' => $student->tanggal_lahir,
                    'nama_ibu' => $student->nama_ibu,
                    'nama_ayah' => $student->nama_ayah,
                    'nomor_mobile' => $student->nomor_mobile,
                    'alamat' => $student->alamat_kk,
                    'tanggal_keluar' => $this->tanggalKeluar ?? now(),
                    'alasan_keluar' => $this->alasanKeluar,
                    'sekolah_tujuan' => $this->sekolahTujuan,
                    // nomor_surat is auto-generated by SiswaKeluar model
                    'nomor_dokumen_emis' => $this->nomorDokumenEmis,
                ]);

                // Set student as inactive
                $student->update(['is_active' => false]);

                $this->dispatch('swal:success', [
                    'title' => 'Status Diperbarui!',
                    'text' => 'Data siswa berhasil diperbarui dan dipindahkan ke data Siswa Keluar.',
                ]);
                return;
            }
        }

        $this->dispatch('swal:success', [
            'title' => 'Data Diperbarui!',
            'text' => 'Data siswa berhasil diperbarui.',
        ]);
    }
}


