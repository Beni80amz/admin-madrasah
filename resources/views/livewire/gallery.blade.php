<div class="min-h-screen pt-24 pb-16 px-5 md:px-10 lg:px-40"
    x-data="{ activeTab: window.location.hash === '#video' ? 'video' : 'photo' }">
    <div class="max-w-[1440px] mx-auto w-full flex flex-col gap-10">

        <!-- Header -->
        <div class="flex flex-col gap-2 text-center items-center">
            <h1 class="text-text-primary-light dark:text-text-primary-dark text-4xl font-bold">Galeri Kegiatan</h1>
            <p class="text-text-secondary-light dark:text-text-secondary-dark max-w-2xl">
                Dokumentasi aktivitas dan kreativitas Siswa {{ $siteProfile->nama_madrasah ?? 'Madrasah Prototype' }}
                dalam berbagai kegiatan akademik maupun
                non-akademik.
            </p>
        </div>

        <!-- Tabs -->
        <div class="flex justify-center gap-4">
            <button @click="activeTab = 'photo'; window.location.hash = 'photo'"
                :class="activeTab === 'photo' ? 'bg-primary text-white' : 'bg-surface-light dark:bg-surface-dark text-text-secondary-light dark:text-text-secondary-dark hover:text-primary'"
                class="px-8 py-3 rounded-full font-bold transition-all border border-border-light dark:border-border-dark hover:border-primary/50">
                Galeri Foto
            </button>
            <button @click="activeTab = 'video'; window.location.hash = 'video'"
                :class="activeTab === 'video' ? 'bg-primary text-white' : 'bg-surface-light dark:bg-surface-dark text-text-secondary-light dark:text-text-secondary-dark hover:text-primary'"
                class="px-8 py-3 rounded-full font-bold transition-all border border-border-light dark:border-border-dark hover:border-primary/50">
                Galeri Video
            </button>
        </div>

        <!-- Photo Gallery Content -->
        <div x-show="activeTab === 'photo'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
            x-data="{ lightboxOpen: false, activeImage: '', activeTitle: '', activeDesc: '' }">

            @forelse($photos as $photo)
                <div class="group relative rounded-2xl overflow-hidden cursor-pointer h-64 md:h-72"
                    @click="lightboxOpen = true; activeImage = '{{ asset('storage/' . $photo->image) }}'; activeTitle = '{{ $photo->category }}'; activeDesc = '{{ addslashes($photo->title) }}'">
                    <img src="{{ asset('storage/' . $photo->image) }}" alt="{{ $photo->title }}"
                        class="content-center w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-primary text-white w-max mb-2">
                            {{ $photo->category }}
                        </span>
                        <p class="text-white text-sm font-medium line-clamp-2">
                            {{ $photo->title }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 flex flex-col items-center justify-center text-center gap-4">
                    <div
                        class="size-16 rounded-full bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl text-gray-500">photo_library</span>
                    </div>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark">Belum ada foto galeri.</p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="col-span-full mt-8 flex justify-center">
                {{ $photos->links() }}
            </div>

            <!-- Lightbox Modal -->
            <div x-show="lightboxOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-sm flex items-center justify-center p-4"
                @keydown.escape.window="lightboxOpen = false">

                <button @click="lightboxOpen = false"
                    class="absolute top-6 right-6 text-white/50 hover:text-text-primary-light dark:text-text-primary-dark transition-colors">
                    <span class="material-symbols-outlined text-4xl">close</span>
                </button>

                <div class="relative max-w-7xl w-full max-h-[90vh] flex flex-col items-center">
                    <img :src="activeImage" class="max-w-full max-h-[80vh] rounded-lg shadow-2xl object-contain"
                        alt="Full view">
                    <div class="mt-4 text-center">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-primary text-white mb-2"
                            x-text="activeTitle"></span>
                        <p class="text-white text-lg max-w-2xl" x-text="activeDesc"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Gallery Content -->
        <div x-show="activeTab === 'video'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
            x-data="{ videoModalOpen: false, activeVideoUrl: '' }">

            @forelse($videos as $video)
                @php
                    // Extract YouTube video ID from URL
                    $videoId = '';
                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video->video_url ?? '', $matches)) {
                        $videoId = $matches[1];
                    }
                @endphp
                <div class="group cursor-pointer flex flex-col gap-3"
                    @click="videoModalOpen = true; activeVideoUrl = '{{ $video->video_url }}'">
                    <div
                        class="relative rounded-2xl overflow-hidden aspect-video border border-white/10 group-hover:border-primary/50 transition-colors">
                        @if($video->image)
                            <img src="{{ asset('storage/' . $video->image) }}" alt="{{ $video->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @elseif($videoId)
                            <img src="https://img.youtube.com/vi/{{ $videoId }}/maxresdefault.jpg" alt="{{ $video->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-primary/20 to-background-dark flex items-center justify-center">
                                <span class="material-symbols-outlined text-6xl text-primary/30">videocam</span>
                            </div>
                        @endif
                        <div
                            class="absolute inset-0 bg-black/40 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                            <div
                                class="size-12 rounded-full bg-primary/90 text-white flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-3xl ml-1">play_arrow</span>
                            </div>
                        </div>
                    </div>
                    <h3
                        class="text-text-primary-light dark:text-text-primary-dark font-bold group-hover:text-primary transition-colors line-clamp-1">
                        {{ $video->title }}
                    </h3>
                </div>
            @empty
                <div class="col-span-full py-12 flex flex-col items-center justify-center text-center gap-4">
                    <div
                        class="size-16 rounded-full bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl text-gray-500">videocam_off</span>
                    </div>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark">Belum ada video galeri.</p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="col-span-full mt-8 flex justify-center">
                {{ $videos->links() }}
            </div>

            <!-- Video Modal -->
            <div x-show="videoModalOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="fixed inset-0 z-[100] flex items-center justify-center px-4 bg-black/90 backdrop-blur-sm"
                @keydown.escape.window="videoModalOpen = false">

                <div class="relative w-full max-w-5xl aspect-video bg-black rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/10"
                    @click.away="videoModalOpen = false">

                    <button @click="videoModalOpen = false"
                        class="absolute top-4 right-4 z-10 p-2 rounded-full bg-black/50 text-white/70 hover:bg-black/80 hover:text-text-primary-light dark:text-text-primary-dark transition-all backdrop-blur-sm">
                        <span class="material-symbols-outlined">close</span>
                    </button>

                    <iframe
                        x-bind:src="videoModalOpen ? activeVideoUrl.replace('watch?v=', 'embed/').split('&')[0] + '?autoplay=1&rel=0' : ''"
                        class="w-full h-full" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>

        </div>
    </div>
</div>