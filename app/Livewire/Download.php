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
    public function render()
    {
        $downloads = DownloadModel::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.download', [
            'downloads' => $downloads,
        ]);
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
