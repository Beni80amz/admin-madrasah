<?php

namespace App\Livewire\Ppdb;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\PpdbRegistration;
use App\Models\ProfileMadrasah;
use App\Models\AppSetting;

#[Layout('components.layouts.public')]
class Success extends Component
{
    public $registration;
    public $siteProfile;
    public $ppdbInfo;
    public $whatsappMessage;
    public $whatsappUrl;

    public function mount($id)
    {
        $this->registration = PpdbRegistration::findOrFail($id);
        $this->siteProfile = ProfileMadrasah::first();
        $this->ppdbInfo = AppSetting::getPpdbInfo();

        $this->generateWhatsAppMessage();
    }

    public function generateWhatsAppMessage()
    {
        $madrasahName = $this->siteProfile->nama_madrasah ?? 'Madrasah';
        $tahunAjaran = $this->ppdbInfo['tahun_ajaran'];
        $noDaftar = $this->registration->no_daftar;
        $nama = $this->registration->nama_lengkap;
        $tanggal = $this->registration->created_at->format('d F Y');
        $receiptUrl = route('ppdb.receipt.download', $this->registration->id);

        $message = "*BUKTI PENDAFTARAN PPDB*\n";
        $message .= "{$madrasahName}\n";
        $message .= "Tahun Ajaran {$tahunAjaran}\n\n";
        $message .= "No. Pendaftaran: {$noDaftar}\n";
        $message .= "Nama: {$nama}\n";
        $message .= "Tanggal Daftar: {$tanggal}\n\n";
        $message .= "Download Bukti Pendaftaran:\n";
        $message .= "{$receiptUrl}\n\n";
        $message .= "Terima kasih telah mendaftar di {$madrasahName}.\n";
        $message .= "Mohon simpan bukti pendaftaran ini sebagai arsip.";

        $this->whatsappMessage = $message;

        // Format phone number for WhatsApp
        $phone = $this->registration->no_hp_ortu;
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        $this->whatsappUrl = 'https://wa.me/' . $phone . '?text=' . urlencode($message);
    }

    #[Title('Pendaftaran Berhasil - PPDB')]
    public function render()
    {
        return view('livewire.ppdb.success');
    }
}
