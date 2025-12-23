<?php

namespace App\Livewire\Ppdb;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use App\Models\PpdbRegistration;
use App\Models\AppSetting;
use Filament\Notifications\Notification;

#[Layout('components.layouts.public')]
class Register extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $totalSteps = 3;

    // Step 1: Data Pribadi
    public $nama_lengkap;
    public $nisn;
    public $nik;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $jenis_kelamin = '';
    public $agama;
    public $alamat;
    public $alamat_kk;
    public $asal_sekolah;
    public $nama_sekolah_asal;

    // Step 2: Data Orang Tua
    public $nama_ayah;
    public $nama_ibu;
    public $no_hp_ortu;
    public $nama_wali;

    // Step 3: Dokumen
    public $file_kk;
    public $file_akta;
    public $file_ijazah;
    public $file_foto;

    // Status
    public $success = false;
    public $registrationCode;

    public function mount()
    {
        if (!AppSetting::isPpdbActive()) {
            return redirect()->route('home');
        }

        $this->agama = 'Islam'; // Default value
    }

    #[Title('Pendaftaran Siswa Baru (PPDB)')]
    public function render()
    {
        return view('livewire.ppdb.register');
    }

    public function nextStep()
    {
        $this->validateStep($this->currentStep);
        $this->currentStep++;
    }

    public function prevStep()
    {
        $this->currentStep--;
    }

    /**
     * Reset nama_sekolah_asal when asal_sekolah changes to 'Orang Tua'
     */
    public function updatedAsalSekolah($value)
    {
        if ($value === 'Orang Tua') {
            $this->nama_sekolah_asal = null;
        }
    }

    public function validateStep($step)
    {
        if ($step == 1) {
            $rules = [
                'nama_lengkap' => 'required|string|max:255',
                'nik' => 'required|string|min:16|max:16',
                'nisn' => 'nullable|string|max:10',
                'tempat_lahir' => 'required|string|max:100',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|string',
                'agama' => 'required|string',
                'alamat' => 'required|string',
                'alamat_kk' => 'nullable|string',
                'asal_sekolah' => 'required|string',
            ];

            $messages = [
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'nik.required' => 'NIK wajib diisi.',
                'nik.min' => 'NIK harus 16 digit.',
                'nik.max' => 'NIK harus 16 digit.',
                'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
                'agama.required' => 'Agama wajib dipilih.',
                'alamat.required' => 'Alamat domisili wajib diisi.',
                'asal_sekolah.required' => 'Asal sekolah wajib dipilih.',
                'nama_sekolah_asal.required' => 'Nama sekolah asal wajib diisi.',
            ];

            // Nama Sekolah Asal wajib jika asal_sekolah bukan "Orang Tua"
            if ($this->asal_sekolah && $this->asal_sekolah !== 'Orang Tua') {
                $rules['nama_sekolah_asal'] = 'required|string|max:255';
            }

            $this->validate($rules, $messages);
        } elseif ($step == 2) {
            $this->validate([
                'nama_ayah' => 'required|string|max:255',
                'nama_ibu' => 'required|string|max:255',
                'no_hp_ortu' => 'required|string|max:20',
                'nama_wali' => 'nullable|string|max:255',
            ], [
                'nama_ayah.required' => 'Nama ayah kandung wajib diisi.',
                'nama_ibu.required' => 'Nama ibu kandung wajib diisi.',
                'no_hp_ortu.required' => 'Nomor HP orang tua wajib diisi.',
            ]);
        }
    }

    public function submit()
    {
        $this->validate([
            'file_kk' => 'required|image|max:2048', // 2MB
            'file_akta' => 'required|image|max:2048',
            'file_ijazah' => 'nullable|image|max:2048', // Ijazah TK/RA might be optional? Let's make it nullable or required based on context. Assume required for now or make nullable.
            'file_foto' => 'required|image|max:2048',
        ]);

        // Generate Registration Number
        $year = date('Y');
        $count = PpdbRegistration::whereYear('created_at', $year)->count() + 1;
        $no_daftar = 'PPDB-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Upload Files
        $documents = [
            'kk' => $this->file_kk->store('ppdb/kk', 'public'),
            'akta' => $this->file_akta->store('ppdb/akta', 'public'),
            'ijazah' => $this->file_ijazah ? $this->file_ijazah->store('ppdb/ijazah', 'public') : null,
            'foto' => $this->file_foto->store('ppdb/foto', 'public'),
        ];

        PpdbRegistration::create([
            'no_daftar' => $no_daftar,
            'nama_lengkap' => $this->nama_lengkap,
            'nisn' => $this->nisn,
            'nik' => $this->nik,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'agama' => $this->agama,
            'alamat' => $this->alamat,
            'alamat_kk' => $this->alamat_kk,
            'asal_sekolah' => $this->asal_sekolah,
            'nama_sekolah_asal' => $this->nama_sekolah_asal,
            'nama_ayah' => $this->nama_ayah,
            'nama_ibu' => $this->nama_ibu,
            'no_hp_ortu' => $this->no_hp_ortu,
            'email' => $this->nama_wali, // nama_wali disimpan ke kolom email (sesuai backend label)
            'dokumen' => $documents,
            'status' => 'new',
        ]);

        $this->success = true;
        $this->registrationCode = $no_daftar;

        Notification::make()
            ->title('Pendaftaran Berhasil')
            ->body("Kode Pendaftaran Anda: h{$no_daftar}")
            ->success()
            ->send();
    }
}
