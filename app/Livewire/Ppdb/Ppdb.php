<?php

namespace App\Livewire\Ppdb;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\PpdbRegistration;
use App\Models\AppSetting;
use App\Models\ProfileMadrasah;

#[Layout('components.layouts.public')]
#[Title('PPDB - Penerimaan Peserta Didik Baru')]
class Ppdb extends Component
{
    use WithPagination;

    public $activeTab = 'informasi';
    public $ppdbInfo;
    public $siteProfile;
    public $search = '';

    public function mount()
    {
        $this->ppdbInfo = AppSetting::getPpdbInfo();
        $this->siteProfile = ProfileMadrasah::first();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $registrations = PpdbRegistration::query()
            ->when($this->search, function ($query) {
                $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    ->orWhere('no_daftar', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.ppdb.ppdb', [
            'registrations' => $registrations,
            'totalRegistrations' => PpdbRegistration::count(),
        ]);
    }
}
