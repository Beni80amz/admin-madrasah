<?php

namespace App\Livewire;

use App\Models\Gallery as GalleryModel;
use App\Models\ProfileMadrasah;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.public')]
#[Title('Galeri')]
class Gallery extends Component
{
    public function render()
    {
        return view('livewire.gallery', [
            'photos' => GalleryModel::active()->photos()->ordered()->get(),
            'videos' => GalleryModel::active()->videos()->ordered()->get(),
            'siteProfile' => ProfileMadrasah::first(),
        ]);
    }
}

