<div class="min-h-screen pt-24 pb-16 px-5 md:px-10 lg:px-20 xl:px-40">
    <div class="max-w-[800px] mx-auto w-full flex flex-col gap-8">

        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-sm text-text-secondary-light dark:text-text-secondary-dark">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors" wire:navigate>Beranda</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="{{ route('news') }}" class="hover:text-primary transition-colors" wire:navigate>Berita</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span
                class="text-primary bg-primary/10 px-2 py-0.5 rounded text-xs font-bold">{{ $newsItem->category }}</span>
        </div>

        <!-- Article Header -->
        <div class="flex flex-col gap-4">
            <h1
                class="text-text-primary-light dark:text-text-primary-dark text-3xl md:text-5xl font-bold leading-tight">
                {{ $newsItem->title }}</h1>

            <div
                class="flex flex-wrap items-center gap-6 text-sm text-text-secondary-light dark:text-text-secondary-dark border-b border-border-light dark:border-border-dark pb-6">
                <div class="flex items-center gap-2">
                    <div
                        class="size-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                        <span
                            class="material-symbols-outlined text-text-secondary-light dark:text-text-secondary-dark">person</span>
                    </div>
                    <span
                        class="font-medium text-text-primary-light dark:text-text-primary-dark">{{ $newsItem->author?->name ?? 'Admin' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                    <span>{{ $newsItem->published_at?->translatedFormat('d F Y') ?? $newsItem->created_at->translatedFormat('d F Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">schedule</span>
                    <span>{{ $newsItem->reading_time }} Menit Baca</span>
                </div>
            </div>
        </div>

        <!-- Featured Image -->
        <div
            class="w-full aspect-video rounded-2xl overflow-hidden bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark">
            @if($newsItem->featured_image)
                <img src="{{ asset('storage/' . $newsItem->featured_image) }}" alt="{{ $newsItem->title }}"
                    class="w-full h-full object-cover">
            @else
                <div
                    class="w-full h-full bg-gradient-to-br from-primary/20 to-background-dark flex items-center justify-center">
                    <span class="material-symbols-outlined text-8xl text-primary/30">article</span>
                </div>
            @endif
        </div>

        <!-- Article Content -->
        <article class="news-content text-text-primary-light dark:text-gray-300 text-lg leading-relaxed">
            {!! $newsItem->content !!}
        </article>

        <!-- Tags & Share -->
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 py-8 border-y border-border-light dark:border-border-dark">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-sm text-text-secondary-light dark:text-text-secondary-dark mr-2">Tags:</span>
                @if($newsItem->tags && count($newsItem->tags) > 0)
                    @foreach ($newsItem->tags as $tag)
                        <span
                            class="px-3 py-1 rounded-full bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark text-xs text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:hover:text-text-primary-dark transition-colors cursor-pointer">{{ $tag }}</span>
                    @endforeach
                @else
                    <span class="text-gray-500 text-sm">Tidak ada tags</span>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <span class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Bagikan:</span>

                <!-- Facebook -->
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank"
                    class="size-9 rounded-full bg-[#1877F2] text-white flex items-center justify-center hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 0 1 1.141.195v3.325a8.623 8.623 0 0 0-.653-.036c-2.148 0-2.971.956-2.971 3.594v.803h4.215l-.42 3.667h-3.795v7.98h-4.844Z">
                        </path>
                    </svg>
                </a>

                <!-- WhatsApp -->
                <a href="https://wa.me/?text={{ urlencode($newsItem->title . ' ' . url()->current()) }}" target="_blank"
                    class="size-9 rounded-full bg-[#25D366] text-white flex items-center justify-center hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                    </svg>
                </a>

                <!-- Copy Link / Instagram -->
                <button x-data="{ copied: false }"
                    @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)"
                    class="relative size-9 rounded-full bg-gradient-to-tr from-[#f09433] via-[#dc2743] to-[#bc1888] text-white flex items-center justify-center hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069ZM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0Zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324ZM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881Z" />
                    </svg>

                    <!-- Tooltip -->
                    <div x-show="copied" x-transition.opacity.duration.300ms
                        class="absolute -top-10 left-1/2 -translate-x-1/2 bg-white text-black text-xs font-bold px-2 py-1 rounded shadow-lg whitespace-nowrap z-10 pointer-events-none">
                        Link Disalin!
                        <div
                            class="absolute -bottom-1 left-1/2 -translate-x-1/2 border-l-4 border-r-4 border-t-4 border-l-transparent border-r-transparent border-t-white">
                        </div>
                    </div>
                </button>

            </div>
        </div>

        <!-- Related News -->
        @if($relatedNews->count() > 0)
            <div class="flex flex-col gap-8 pt-8">
                <div class="flex justify-between items-center border-l-4 border-primary pl-4">
                    <h2 class="text-text-primary-light dark:text-text-primary-dark text-2xl font-bold">Berita Terkait</h2>
                    <a href="{{ route('news') }}" wire:navigate class="text-sm font-bold text-primary hover:underline">Lihat
                        Semua</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($relatedNews as $item)
                        <a href="{{ route('news.show', ['slug' => $item->slug]) }}" wire:navigate class="block group">
                            <article
                                class="bg-surface-light dark:bg-surface-dark rounded-xl overflow-hidden border border-border-light dark:border-border-dark group-hover:border-primary/50 transition-colors shadow-lg h-full flex flex-col">
                                <div class="relative h-48 overflow-hidden">
                                    @if($item->featured_image)
                                        <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-primary/20 to-background-dark flex items-center justify-center">
                                            <span class="material-symbols-outlined text-4xl text-primary/30">article</span>
                                        </div>
                                    @endif
                                    <div class="absolute bottom-3 left-3">
                                        <span
                                            class="bg-primary/90 text-white px-2 py-0.5 rounded text-[10px] font-bold">{{ $item->category }}</span>
                                    </div>
                                </div>
                                <div class="p-4 flex flex-col gap-2 flex-grow">
                                    <h3
                                        class="text-text-primary-light dark:text-text-primary-dark text-lg font-bold leading-tight group-hover:text-primary transition-colors line-clamp-2">
                                        {{ $item->title }}
                                    </h3>
                                    <div class="mt-auto flex justify-between items-center">
                                        <span
                                            class="text-xs text-gray-500">{{ $item->published_at?->translatedFormat('d F Y') ?? $item->created_at->translatedFormat('d F Y') }}</span>
                                        <span
                                            class="text-xs text-primary opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1">
                                            Baca <span class="material-symbols-outlined text-[14px]">arrow_right_alt</span>
                                        </span>
                                    </div>
                                </div>
                            </article>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>