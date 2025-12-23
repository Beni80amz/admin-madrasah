<?php

namespace App\Livewire;

use App\Models\News as NewsModel;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.public')]
#[Title('Berita')]
class News extends Component
{
    use WithPagination;

    public $activeCategory = 'Semua';

    public function setCategory($category)
    {
        $this->activeCategory = $category;
        $this->resetPage();
    }

    public function render()
    {
        $query = NewsModel::published()->latest();

        if ($this->activeCategory !== 'Semua') {
            $query->where('category', $this->activeCategory);
        }

        // Get unique categories from published news
        $categories = NewsModel::published()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->toArray();

        return view('livewire.news', [
            'news' => $query->paginate(9),
            'categories' => $categories,
        ]);
    }
}


