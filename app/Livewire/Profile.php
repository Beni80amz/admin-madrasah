<?php

namespace App\Livewire;

use App\Models\ProfileMadrasah;
use App\Models\StrukturOrganisasi;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.public')]
class Profile extends Component
{
    public ?ProfileMadrasah $profile = null;

    public function mount()
    {
        $this->profile = ProfileMadrasah::firstOrNew();
    }

    #[Title('Profil Madrasah')]
    public function render()
    {
        // Level 0: Ketua Yayasan
        $strukturLevel0 = StrukturOrganisasi::with('teacher.rombelWaliKelas.kelas')
            ->active()
            ->where('level', 0)
            ->ordered()
            ->get();

        // Level 1: Kepala Madrasah
        $strukturLevel1 = StrukturOrganisasi::with('teacher.rombelWaliKelas.kelas')
            ->active()
            ->where('level', 1)
            ->ordered()
            ->get();

        // Level 2: Operator & Ketua Komite
        $strukturLevel2 = StrukturOrganisasi::with('teacher.rombelWaliKelas.kelas')
            ->active()
            ->where('level', 2)
            ->ordered()
            ->get();

        // Level 3: Wakamad & Tata Usaha
        $strukturLevel3 = StrukturOrganisasi::with('teacher.rombelWaliKelas.kelas')
            ->active()
            ->where('level', 3)
            ->ordered()
            ->get();

        // Level 4: Wali Kelas & Korlas
        $strukturLevel4 = StrukturOrganisasi::with('teacher.rombelWaliKelas.kelas')
            ->active()
            ->where('level', 4)
            ->ordered()
            ->get();

        // Level 5: Wali Kelas Atas (4,5,6)
        $strukturLevel5 = StrukturOrganisasi::with('teacher.rombelWaliKelas.kelas')
            ->active()
            ->where('level', 5)
            ->ordered()
            ->get();

        // Level 6: Bagian Umum
        $strukturLevel6 = StrukturOrganisasi::with('teacher.rombelWaliKelas.kelas')
            ->active()
            ->where('level', 6)
            ->ordered()
            ->get();

        return view('livewire.profile', [
            'profile' => $this->profile,
            'strukturLevel0' => $strukturLevel0,
            'strukturLevel1' => $strukturLevel1,
            'strukturLevel2' => $strukturLevel2,
            'strukturLevel3' => $strukturLevel3,
            'strukturLevel4' => $strukturLevel4,
            'strukturLevel5' => $strukturLevel5,
            'strukturLevel6' => $strukturLevel6,
        ]);
    }
}


