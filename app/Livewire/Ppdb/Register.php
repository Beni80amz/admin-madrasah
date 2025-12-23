<?php

namespace App\Livewire\Ppdb;

use Livewire\Component;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use App\Models\PpdbRegistration;
use App\Models\AppSetting;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

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

    // Step 3: Dokumen - Dynamic based on persyaratan
    public $dokumen = [];
    public $persyaratanDokumen = [];

    // Status
    public $success = false;
    public $registrationCode;

    public function mount()
    {
        if (!AppSetting::isPpdbActive()) {
            return redirect()->route('home');
        }

        $this->agama = 'Islam'; // Default value

        // Load persyaratan from settings
        $this->persyaratanDokumen = AppSetting::getPpdbPersyaratan();

        // Initialize dokumen array with null values
        foreach ($this->persyaratanDokumen as $index => $item) {
            $this->dokumen[$index] = null;
        }
    }

    public function getTahunAjaranProperty()
    {
        return AppSetting::getValue('ppdb_tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
    }

    #[Title('Pendaftaran Siswa Baru (PPDB)')]
    public function render()
    {
        return view('livewire.ppdb.register', [
            'tahunAjaran' => $this->tahunAjaran,
        ]);
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
        // Validate all required documents
        $rules = [];
        $messages = [];

        foreach ($this->persyaratanDokumen as $index => $item) {
            // Make all documents required (you can add "opsional" keyword check if needed)
            $isOptional = Str::contains(strtolower($item), 'opsional') || Str::contains(strtolower($item), 'jika ada');
            $rules["dokumen.{$index}"] = ($isOptional ? 'nullable' : 'required') . '|image|max:2048';
            $messages["dokumen.{$index}.required"] = "Dokumen {$item} wajib diupload.";
            $messages["dokumen.{$index}.image"] = "Dokumen {$item} harus berupa gambar.";
            $messages["dokumen.{$index}.max"] = "Dokumen {$item} maksimal 2MB.";
        }

        $this->validate($rules, $messages);

        // Generate Registration Number
        $year = date('Y');
        $count = PpdbRegistration::whereYear('created_at', $year)->count() + 1;
        $no_daftar = 'PPDB-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Upload Files with dynamic keys
        $documents = [];
        foreach ($this->persyaratanDokumen as $index => $item) {
            $key = Str::slug($item, '_');
            if (isset($this->dokumen[$index]) && is_object($this->dokumen[$index])) {
                $documents[$key] = $this->dokumen[$index]->store('ppdb/' . $key, 'public');
            }
        }

        $registration = PpdbRegistration::create([
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
            'email' => $this->nama_wali,
            'dokumen' => $documents,
            'status' => 'new',
        ]);

        // Redirect to success page
        return redirect()->route('ppdb.success', $registration->id);
    }
}
