<?php

namespace App\Livewire;

use App\Models\Download as DownloadModel;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.public')]
#[Title('Unduhan - Madrasah Portal')]
class Download extends Component
{
    public $selectedCategory = 'Semua';

    public function render()
    {
        $query = DownloadModel::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc');

        if ($this->selectedCategory !== 'Semua') {
            $query->where('category', $this->selectedCategory);
        }

        $downloads = $query->get();

        $categories = DownloadModel::where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values()
            ->toArray();

        // Ensure 'Semua' is at the beginning
        array_unshift($categories, 'Semua');

        return view('livewire.download', [
            'downloads' => $downloads,
            'categories' => $categories,
        ]);
    }

    public function setCategory($category)
    {
        $this->selectedCategory = $category;
    }

    public function downloadFile($id)
    {
        $download = DownloadModel::findOrFail($id);

        // Increment download count
        $download->increment('download_count');

        if (!Storage::disk('public')->exists($download->file_path)) {
            session()->flash('error', 'File tidak ditemukan.');
            return;
        }

        return Storage::disk('public')->download($download->file_path, $download->title . '.' . pathinfo($download->file_path, PATHINFO_EXTENSION));
    }
}
