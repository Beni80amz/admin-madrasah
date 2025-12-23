<div class="min-h-screen pt-24 pb-16 px-5 md:px-10 lg:px-20 xl:px-40">
    <div class="max-w-[1200px] mx-auto w-full flex flex-col gap-10">

        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-2 text-sm text-text-secondary-light dark:text-text-secondary-dark">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors" wire:navigate>Beranda</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-text-primary-light dark:text-text-primary-dark">Berita</span>
            </div>
            <div class="flex flex-col gap-2">
                <h1 class="text-text-primary-light dark:text-text-primary-dark text-4xl md:text-5xl font-bold">Berita &
                    Artikel</h1>
                <p class="text-text-secondary-light dark:text-text-secondary-dark max-w-2xl text-lg">
                    Ikuti perkembangan terbaru, prestasi, dan kegiatan seru di
                    {{ $siteProfile->nama_madrasah ?? 'Madrasah' }}.
                </p>
            </div>
        </div>

        <!-- Filter / Tabs -->
        <div class="flex flex-wrap gap-2 pb-4 border-b border-border-light dark:border-border-dark">
            <!-- Semua Button -->
            <button wire:click="setCategory('Semua')"
                class="px-4 py-2 rounded-full text-sm font-bold border transition-colors {{ $activeCategory === 'Semua' ? 'bg-primary text-white border-primary' : 'bg-surface-light dark:bg-surface-dark text-text-secondary-light dark:text-text-secondary-dark hover:text-primary border-border-light dark:border-border-dark hover:border-primary/50' }}">
                Semua
            </button>

            <!-- Dynamic Categories from Database -->
            @foreach ($categories as $category)
                <button wire:click="setCategory('{{ $category }}')"
                    class="px-4 py-2 rounded-full text-sm font-bold border transition-colors {{ $activeCategory === $category ? 'bg-primary text-white border-primary' : 'bg-surface-light dark:bg-surface-dark text-text-secondary-light dark:text-text-secondary-dark hover:text-primary border-border-light dark:border-border-dark hover:border-primary/50' }}">
                    {{ $category }}
                </button>
            @endforeach
        </div>

        <!-- News Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($news as $index => $item)
                <article
                    class="bg-surface-light dark:bg-surface-dark rounded-2xl overflow-hidden border border-border-light dark:border-border-dark group hover:border-primary/50 hover:-translate-y-1 hover:shadow-lg hover:shadow-primary/10 transition-all duration-300 shadow-lg">
                    <div class="relative h-56 overflow-hidden">
                        @if($item->featured_image)
                            <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-primary/20 to-background-dark flex items-center justify-center">
                                <span class="material-symbols-outlined text-6xl text-primary/30">article</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 flex flex-col items-start gap-1">
                            <span
                                class="bg-primary/20 text-primary px-2 py-1 rounded-md font-bold text-xs backdrop-blur-sm">{{ $item->category }}</span>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col gap-3">
                        <div
                            class="text-xs text-text-secondary-light dark:text-text-secondary-dark flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            <span>{{ $item->published_at?->translatedFormat('d F Y') ?? $item->created_at->translatedFormat('d F Y') }}</span>
                        </div>
                        <h3
                            class="text-text-primary-light dark:text-text-primary-dark text-xl font-bold leading-tight group-hover:text-primary transition-colors line-clamp-2">
                            {{ $item->title }}
                        </h3>
                        <p
                            class="text-text-secondary-light dark:text-text-secondary-dark text-sm line-clamp-3 leading-relaxed">
                            {{ $item->excerpt ?? Str::limit(strip_tags($item->content), 150) }}
                        </p>
                        <a class="text-primary text-sm font-bold mt-auto flex items-center gap-1 hover:underline underline-offset-4"
                            href="{{ route('news.show', ['slug' => $item->slug]) }}" wire:navigate>
                            Baca Selengkapnya
                            <span class="material-symbols-outlined text-[16px]">arrow_right_alt</span>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-12 flex flex-col items-center justify-center text-center gap-4">
                    <div
                        class="size-16 rounded-full bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl text-gray-500">newspaper</span>
                    </div>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark">Belum ada berita untuk kategori ini.
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($news->hasPages())
            <div class="pt-8">
                {{ $news->links() }}
            </div>
        @endif

    </div>
</div>