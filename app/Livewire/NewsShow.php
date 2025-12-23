<?php

namespace App\Livewire;

use App\Models\News;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.public')]
class NewsShow extends Component
{
    public $pageTitle;
    public $newsSlug;
    public $newsItem;
    public $relatedNews;

    public function mount($slug)
    {
        $this->newsSlug = $slug;

        // Fetch news item from database
        $this->newsItem = News::where('slug', $slug)
            ->published()
            ->first();

        if (!$this->newsItem) {
            abort(404, 'Berita tidak ditemukan');
        }

        // Get related news (same category, excluding current, limit 3)
        $this->relatedNews = News::published()
            ->where('id', '!=', $this->newsItem->id)
            ->where('category', $this->newsItem->category)
            ->latest()
            ->take(3)
            ->get();

        // If not enough related in same category, fill with other news
        if ($this->relatedNews->count() < 3) {
            $existingIds = $this->relatedNews->pluck('id')->push($this->newsItem->id);
            $additionalNews = News::published()
                ->whereNotIn('id', $existingIds)
                ->latest()
                ->take(3 - $this->relatedNews->count())
                ->get();
            $this->relatedNews = $this->relatedNews->concat($additionalNews);
        }

        $this->pageTitle = $this->newsItem->title;
    }

    public function render()
    {
        return view('livewire.news-show');
    }
}

