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
    use \Livewire\WithPagination;

    public function render()
    {
        return view('livewire.gallery', [
            'photos' => GalleryModel::active()->photos()->ordered()->paginate(9, ['*'], 'photo_page'),
            'videos' => GalleryModel::active()->videos()->ordered()->paginate(8, ['*'], 'video_page'),
            'siteProfile' => ProfileMadrasah::first(),
        ]);
    }
}

