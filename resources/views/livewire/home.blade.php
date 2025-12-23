<div>
    <!-- Hero Section -->
    @php
        // Prepare slides array for Alpine.js
        $slidesArray = $heroSliders->count() > 0
            ? $heroSliders->map(fn($s) => asset('storage/' . $s->image))->toArray()
            : [
                'https://lh3.googleusercontent.com/aida-public/AB6AXuAxjre_kr2Y-C7xuBC6V0UorlIh-rp44OG0gCBY2g7dtLoAVtqoB8fflqVDQorFBof6VNjc8g-lxtI-GB--T9KOBPUXFJsBktoz1vYqwq7qJb4IrErDMfwZB3tIbaCgVx3kuX0_MHzJSqI61gLqzYE9y-V0rUYSWviVsCuHxzKhy_FiXqu23fWzUMUo2MbFdMy7PhzVg5CI2lnYagPIx2AU0iJi92tm9EK7ujD7svRXgVtofgrLMBi4sLUa_8BzZDj6fKH2ZRCWtuQ',
                'https://lh3.googleusercontent.com/aida-public/AB6AXuCtlOTh4WSIUFfHPGMQEOjx8OmNQBl94yogTfTpsZQn5j3baB9i92nlbz0IO2gEWeSLolfKUwuykBDpaegMYDbxA2nhpl5vbo9_qheznVxdS5PcNE8Bh89BQ25EMlg-0dAVXnTiDrdQWOjNsI8qyOXNr5bxNvSrNIKzJe46hns9wQtpm-X7r_jMq4_HtkQ_Rhv_0tn2KGdFj7OUy54UAAZJZIA1FmeH1ZLCzYwY9zZoX0EDF6QTDCOd5sJTBHimB4UZ_FwhRUn8ZgE',
                'https://lh3.googleusercontent.com/aida-public/AB6AXuAsE3uzK1IkUlq_SEbxNIkO4jIfHaH1FUF6-tP7AJaPI6OX-L1gdbXRe93NJkd6zwfSDGzH3LsUF6BPwFe7gCs6C1xZ07BhEjidMr_bHBV8bQ9bjyRt_sREGmRO7r16JfNZUS-tUNpjLgH_xmvN1kTYN--QaBCN7kg6Bff57JckTedLUZGqx-aQDVuF31V8dS8P-Rl9xqZdh1f1QQnLuT--lqFks3jOZWxHEKqVnWSVc4tIPsKipe4geLpKJV46YXGqD4aCs6efOQg'
            ];
    @endphp
    <section class="w-full relative" x-data="{ 
            activeSlide: 0, 
            slides: @js($slidesArray)
        }" x-init="setInterval(() => { activeSlide = activeSlide === slides.length - 1 ? 0 : activeSlide + 1 }, 5000)">

        <div class="w-full h-[600px] relative flex items-center justify-center overflow-hidden">
            <!-- Slider Images -->
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="activeSlide === index" x-transition:enter="transition ease-in-out duration-1000"
                    x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in-out duration-1000"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-100"
                    class="absolute inset-0 bg-cover bg-center"
                    :style="`background-image: linear-gradient(rgba(17, 33, 23, 0.7) 0%, rgba(17, 33, 23, 0.5) 100%), url('${slide}')`">
                </div>
            </template>

            <div class="absolute inset-0 bg-gradient-to-t from-background-dark to-transparent opacity-90 z-10"></div>

            <div
                class="layout-content-container flex flex-col max-w-[960px] px-5 relative z-20 text-center items-center gap-6">
                <div class="flex flex-col gap-4">
                    <h1
                        class="text-text-primary-light dark:text-text-primary-dark text-4xl md:text-6xl font-black leading-[1.1] tracking-[-0.033em]">
                        @if($siteProfile?->motto)
                            @php
                                $mottoParts = explode(' ', $siteProfile->motto, 2);
                            @endphp
                            {{ $mottoParts[0] ?? '' }} <br /> <span class="text-primary">{{ $mottoParts[1] ?? '' }}</span>
                        @else
                            Membangun Generasi <br /> <span class="text-primary">Islami Berprestasi</span>
                        @endif
                    </h1>
                    <p class="text-gray-200 text-lg md:text-xl font-normal leading-relaxed max-w-[700px] mx-auto">
                        {{ $siteProfile?->visi ? strip_tags($siteProfile->visi) : 'Mewujudkan pendidikan berkualitas yang berlandaskan nilai-nilai Islam, akhlak mulia, dan teknologi modern untuk masa depan yang gemilang.' }}
                    </p>
                </div>
                <div class="flex gap-4 mt-4">
                    <a href="#sambutan"
                        class="flex cursor-pointer items-center justify-center overflow-hidden rounded-full h-12 px-8 bg-primary text-text-primary-light dark:text-text-primary-dark text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary-dark hover:scale-105 transition-all">
                        <span class="truncate">Jelajahi Kami</span>
                    </a>
                    <a href="#video-terbaru"
                        class="flex cursor-pointer items-center justify-center overflow-hidden rounded-full h-12 px-8 bg-white/10 backdrop-blur-sm border border-white/20 text-text-primary-light dark:text-text-primary-dark text-base font-bold leading-normal tracking-[0.015em] hover:bg-white/20 transition-colors">
                        <span class="truncate">Video Profil</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="w-full py-10 -mt-16 relative z-20 px-5 md:px-10 lg:px-40">
        <div class="max-w-[1200px] mx-auto w-full grid grid-cols-2 md:grid-cols-4 gap-4" x-data="{ shown: false }"
            x-intersect:enter="shown = true">
            <!-- Siswa Card -->
            <div x-show="shown" x-transition:enter="transition-opacity ease-out duration-500 delay-100"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                class="flex flex-col items-center justify-center gap-2 rounded-xl p-6 bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark shadow-lg backdrop-blur-xl hover:shadow-xl hover:shadow-primary/10 hover:-translate-y-1 hover:border-primary/50 transition-all duration-300 cursor-default">
                <span class="material-symbols-outlined text-primary text-4xl animate-bounce"
                    style="animation-duration: 2s;">groups</span>
                <p
                    class="text-text-primary-light dark:text-text-primary-dark tracking-tight text-3xl font-bold leading-tight">
                    {{ $totalSiswa }}+
                </p>
                <p
                    class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium uppercase tracking-wider">
                    Siswa</p>
            </div>
            <!-- Guru Card -->
            <div x-show="shown" x-transition:enter="transition-opacity ease-out duration-500 delay-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                class="flex flex-col items-center justify-center gap-2 rounded-xl p-6 bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark shadow-lg backdrop-blur-xl hover:shadow-xl hover:shadow-primary/10 hover:-translate-y-1 hover:border-primary/50 transition-all duration-300 cursor-default">
                <span class="material-symbols-outlined text-primary text-4xl animate-bounce"
                    style="animation-duration: 2.2s;">school</span>
                <p
                    class="text-text-primary-light dark:text-text-primary-dark tracking-tight text-3xl font-bold leading-tight">
                    {{ $totalGuru }}+
                </p>
                <p
                    class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium uppercase tracking-wider">
                    Guru & Staff</p>
            </div>
            <!-- Kelas Card -->
            <div x-show="shown" x-transition:enter="transition-opacity ease-out duration-500 delay-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                class="flex flex-col items-center justify-center gap-2 rounded-xl p-6 bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark shadow-lg backdrop-blur-xl hover:shadow-xl hover:shadow-primary/10 hover:-translate-y-1 hover:border-primary/50 transition-all duration-300 cursor-default">
                <span class="material-symbols-outlined text-primary text-4xl animate-bounce"
                    style="animation-duration: 2.4s;">meeting_room</span>
                <p
                    class="text-text-primary-light dark:text-text-primary-dark tracking-tight text-3xl font-bold leading-tight">
                    {{ $totalKelas }}+
                </p>
                <p
                    class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium uppercase tracking-wider">
                    Kelas</p>
            </div>
            <!-- Alumni Card -->
            <div x-show="shown" x-transition:enter="transition-opacity ease-out duration-500 delay-[400ms]"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                class="flex flex-col items-center justify-center gap-2 rounded-xl p-6 bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark shadow-lg backdrop-blur-xl hover:shadow-xl hover:shadow-primary/10 hover:-translate-y-1 hover:border-primary/50 transition-all duration-300 cursor-default">
                <span class="material-symbols-outlined text-primary text-4xl animate-bounce"
                    style="animation-duration: 2.6s;">diversity_3</span>
                <p
                    class="text-text-primary-light dark:text-text-primary-dark tracking-tight text-3xl font-bold leading-tight">
                    {{ $totalAlumni }}+
                </p>
                <p
                    class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium uppercase tracking-wider">
                    Alumni</p>
            </div>
        </div>
    </section>
    <!-- Welcome / Principal Section -->
    <section id="sambutan" class="w-full py-16 px-5 md:px-10 lg:px-40">
        <div class="max-w-[1200px] mx-auto w-full flex flex-col md:flex-row items-center gap-12"
            x-data="{ shown: false }" x-intersect:enter="shown = true">
            <!-- Photo with animation -->
            <div class="w-full md:w-1/3 flex justify-center" x-show="shown"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 -translate-x-12" x-transition:enter-end="opacity-100 translate-x-0">
                <div
                    class="relative w-64 h-64 md:w-80 md:h-80 rounded-full overflow-hidden border-4 border-primary shadow-[0_0_40px_rgba(16,185,129,0.15)] hover:shadow-[0_0_60px_rgba(16,185,129,0.3)] transition-shadow duration-500 animate-float">
                    @if($siteProfile?->foto_kepala_madrasah)
                        <img alt="Foto Kepala Madrasah {{ $siteProfile->nama_kepala_madrasah }}"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                            src="{{ asset('storage/' . $siteProfile->foto_kepala_madrasah) }}" />
                    @else
                        <div class="w-full h-full bg-primary/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-8xl">person</span>
                        </div>
                    @endif
                    <!-- Decorative ring animation -->
                    <div class="absolute inset-0 rounded-full border-4 border-primary/30 animate-ping"
                        style="animation-duration: 3s;"></div>
                </div>
            </div>
            <!-- Text content with animation -->
            <div class="w-full md:w-2/3 flex flex-col gap-6 text-center md:text-left" x-show="shown"
                x-transition:enter="transition ease-out duration-700 delay-300"
                x-transition:enter-start="opacity-0 translate-x-12" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="flex flex-col gap-2">
                    <span class="text-primary font-bold tracking-widest uppercase text-sm">Kata Pengantar Kepala
                        Madrasah</span>
                    <h2
                        class="text-text-primary-light dark:text-text-primary-dark text-3xl md:text-4xl font-bold leading-tight">
                        Mencetak Generasi Unggul Berakhlakul Karimah
                    </h2>
                </div>
                <div class="relative">
                    <!-- Quote Icon SVG -->
                    <svg class="absolute -top-4 -left-6 w-16 h-16 text-border-light dark:text-surface-dark opacity-30 select-none"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z" />
                    </svg>
                    <div
                        class="text-text-secondary-light dark:text-text-secondary-dark text-lg leading-relaxed relative z-10 prose prose-invert max-w-none">
                        @if($siteProfile?->kata_pengantar)
                            {!! $siteProfile->kata_pengantar !!}
                        @else
                            <p>"Selamat datang di portal resmi {{ $siteProfile->nama_madrasah ?? 'Madrasah Prototype' }}.
                                Kami berkomitmen untuk memberikan pendidikan
                                terbaik yang mengintegrasikan ilmu pengetahuan umum dengan nilai-nilai keislaman. Visi kami
                                adalah melahirkan pemimpin masa depan yang tidak hanya cerdas secara intelektual, tetapi
                                juga
                                matang secara spiritual dan emosional."</p>
                        @endif
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-text-primary-light dark:text-text-primary-dark text-lg font-bold">
                        {{ $siteProfile?->nama_kepala_madrasah ?? 'Kepala Madrasah' }}
                    </p>
                    <p class="text-primary text-sm">Kepala Madrasah</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Unggulan (Programs) Section -->
    <section class="w-full py-16 px-5 md:px-10 lg:px-40 bg-surface-light dark:bg-surface-dark/30">
        <div class="max-w-[1200px] mx-auto w-full flex flex-col gap-10">
            <div class="flex flex-col gap-2 border-b border-border-light dark:border-border-dark pb-6">
                <h2 class="text-text-primary-light dark:text-text-primary-dark text-3xl font-bold">Program Unggulan</h2>
                <p class="text-text-secondary-light dark:text-text-secondary-dark">Kurikulum terpadu untuk memaksimalkan
                    potensi siswa.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($programs as $program)
                    <!-- Program Card -->
                    <div
                        class="group relative overflow-hidden rounded-xl bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark hover:border-primary/50 transition-all duration-300 hover:-translate-y-1 shadow-sm">
                        <div class="h-48 w-full bg-cover bg-center relative" @if($program->gambar)
                        style="background-image: url('{{ asset('storage/' . $program->gambar) }}');" @else
                            style="background: linear-gradient(135deg, #1a3a2a 0%, #0d1f15 100%);" @endif>
                            <div class="absolute inset-0 bg-black/40 group-hover:bg-black/20 transition-all"></div>
                            @if(!$program->gambar)
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-6xl text-primary/30">{{ $program->icon }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-6 flex flex-col gap-3">
                            <div
                                class="size-12 rounded-full bg-primary/20 flex items-center justify-center text-primary mb-2">
                                <span class="material-symbols-outlined">{{ $program->icon }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-text-primary-light dark:text-text-primary-dark">
                                {{ $program->nama }}
                            </h3>
                            <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm leading-relaxed">
                                {{ $program->deskripsi }}
                            </p>
                        </div>
                    </div>
                @empty
                    <!-- Fallback when no programs -->
                    <div class="col-span-3 text-center py-12">
                        <span class="material-symbols-outlined text-6xl text-gray-600 mb-4">school</span>
                        <p class="text-text-secondary-light dark:text-text-secondary-dark">Belum ada program unggulan yang
                            ditambahkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Facilities Section -->
    <section class="w-full py-16 px-5 md:px-10 lg:px-40">
        <div class="max-w-[1200px] mx-auto w-full flex flex-col gap-10" x-data="{ shown: false }"
            x-intersect:enter="shown = true">
            <!-- Header with animation -->
            <div class="text-center max-w-2xl mx-auto" x-show="shown"
                x-transition:enter="transition-opacity ease-out duration-500" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100">
                <h2 class="text-text-primary-light dark:text-text-primary-dark text-3xl font-bold mb-4">Fasilitas
                    Penunjang</h2>
                <p class="text-text-secondary-light dark:text-text-secondary-dark">Lingkungan belajar modern dan nyaman
                    untuk mendukung aktivitas siswa.</p>
            </div>
            <!-- Facility Cards with staggered animation -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @forelse($fasilitas as $index => $item)
                    <div x-show="shown" x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                        :style="'transition-delay: ' + ({{ $loop->index }} * 100 + 200) + 'ms'"
                        class="flex flex-col items-center text-center gap-4 p-6 rounded-xl bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark hover:bg-primary/10 dark:hover:bg-primary/10 hover:border-primary/50 hover:-translate-y-1 hover:shadow-lg hover:shadow-primary/10 transition-all duration-300 cursor-default group">
                        <span
                            class="material-symbols-outlined text-4xl text-primary group-hover:scale-110 transition-transform duration-300">{{ $item->icon }}</span>
                        <h4 class="text-text-primary-light dark:text-text-primary-dark font-bold">{{ $item->nama }}</h4>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-12">
                        <span class="material-symbols-outlined text-6xl text-gray-600 mb-4">business</span>
                        <p class="text-text-secondary-light dark:text-text-secondary-dark">Belum ada fasilitas yang
                            ditambahkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Extracurriculars -->
    <section class="w-full py-16 px-5 md:px-10 lg:px-40 bg-surface-light dark:bg-surface-dark/30">
        <div class="max-w-[1200px] mx-auto w-full flex flex-col gap-10">
            <h2
                class="text-text-primary-light dark:text-text-primary-dark text-3xl font-bold px-4 border-l-4 border-primary">
                Ekstrakurikuler
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse($ekstrakurikuler as $ekskul)
                    <div class="relative group overflow-hidden rounded-xl h-64 cursor-pointer">
                        @if($ekskul->gambar)
                            <img alt="{{ $ekskul->nama }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                src="{{ asset('storage/' . $ekskul->gambar) }}" />
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-primary/20 to-background-dark flex items-center justify-center">
                                <span class="material-symbols-outlined text-8xl text-primary/30">sports_soccer</span>
                            </div>
                        @endif
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent flex items-end p-6">
                            <div>
                                <h3 class="text-text-primary-light dark:text-text-primary-dark text-xl font-bold">
                                    {{ $ekskul->nama }}
                                </h3>
                                @if($ekskul->deskripsi)
                                    <p
                                        class="text-gray-300 text-sm mt-1 opacity-0 group-hover:opacity-100 transition-opacity transform translate-y-2 group-hover:translate-y-0">
                                        {{ $ekskul->deskripsi }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <span class="material-symbols-outlined text-6xl text-gray-600 mb-4">sports_soccer</span>
                        <p class="text-text-secondary-light dark:text-text-secondary-dark">Belum ada ekstrakurikuler yang
                            ditambahkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Latest News -->
    <section class="w-full py-16 px-5 md:px-10 lg:px-40">
        <div class="max-w-[1200px] mx-auto w-full flex flex-col gap-10">
            <div class="flex justify-between items-center">
                <h2 class="text-text-primary-light dark:text-text-primary-dark text-3xl font-bold">Berita Terbaru</h2>
                <a href="{{ route('news') }}" wire:navigate
                    class="text-sm font-bold text-primary border border-primary/30 rounded-full px-4 py-2 hover:bg-primary/10 transition-colors">Lihat
                    Semua</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($news as $item)
                    <article class="flex flex-col gap-4 group">
                        <div class="aspect-video w-full rounded-xl overflow-hidden bg-gray-800">
                            @if($item->featured_image)
                                <img alt="{{ $item->title }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    src="{{ asset('storage/' . $item->featured_image) }}" />
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-primary/20 to-background-dark flex items-center justify-center">
                                    <span class="material-symbols-outlined text-6xl text-primary/30">article</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col gap-2">
                            <div
                                class="flex items-center gap-3 text-xs text-text-secondary-light dark:text-text-secondary-dark">
                                <span
                                    class="bg-primary/20 text-primary px-2 py-1 rounded-md font-bold">{{ $item->category }}</span>
                                <span>{{ $item->published_at?->translatedFormat('d F Y') ?? $item->created_at->translatedFormat('d F Y') }}</span>
                            </div>
                            <h3
                                class="text-text-primary-light dark:text-text-primary-dark text-xl font-bold leading-tight group-hover:text-primary transition-colors">
                                {{ $item->title }}
                            </h3>
                            <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm line-clamp-2">
                                {{ $item->excerpt ?? Str::limit(strip_tags($item->content), 120) }}
                            </p>
                            <a class="text-primary text-sm font-bold mt-2 flex items-center gap-1"
                                href="{{ route('news.show', ['slug' => $item->slug]) }}" wire:navigate>Baca
                                Selengkapnya <span class="material-symbols-outlined text-[16px]">arrow_right_alt</span></a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <span class="material-symbols-outlined text-6xl text-gray-600 mb-4">article</span>
                        <p class="text-text-secondary-light dark:text-text-secondary-dark">Belum ada berita yang
                            dipublikasikan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Gallery & Video Section -->
    <section class="w-full py-16 px-5 md:px-10 lg:px-40 bg-surface-light dark:bg-surface-dark/30" x-data="{ 
            lightboxOpen: false, 
            activeImage: '',
            videoModalOpen: false,
            activeVideoUrl: '',
            openLightbox(url) {
                this.activeImage = url;
                this.lightboxOpen = true;
            },
            openVideo(videoId) {
                this.activeVideoUrl = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
                this.videoModalOpen = true;
            },
            closeVideo() {
                this.videoModalOpen = false;
                this.activeVideoUrl = '';
            }
        }">
        <div class="max-w-[1200px] mx-auto w-full flex flex-col gap-12">
            <!-- Photos Grid -->
            <div class="flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-text-primary-light dark:text-text-primary-dark text-3xl font-bold">Galeri Kegiatan
                    </h2>
                    <a href="{{ route('gallery') }}#photo" wire:navigate
                        class="hidden md:flex items-center gap-2 text-primary hover:text-primary-light transition-colors group">
                        <span class="font-bold">Lihat Semua Photo</span>
                        <span
                            class="material-symbols-outlined transition-transform duration-300 group-hover:translate-x-1">arrow_forward</span>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @forelse($galleryPhotos as $photo)
                        <div class="rounded-xl overflow-hidden shadow-lg cursor-pointer group hover:scale-[1.02] transition-transform duration-300 relative h-64 md:h-72"
                            @click="openLightbox('{{ asset('storage/' . $photo->image) }}')">
                            <img alt="{{ $photo->title }}"
                                class="w-full h-full object-cover group-hover:brightness-110 transition-all"
                                src="{{ asset('storage/' . $photo->image) }}" />
                            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                                <span
                                    class="inline-block px-2 py-1 mb-1 text-xs font-bold text-text-primary-light dark:text-text-primary-dark bg-primary rounded">{{ $photo->category }}</span>
                                <p
                                    class="text-text-primary-light dark:text-text-primary-dark text-sm font-medium line-clamp-2">
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
                </div>
                <div class="flex justify-center md:hidden mt-4">
                    <a href="{{ route('gallery') }}#photo" wire:navigate
                        class="flex items-center gap-2 text-primary hover:text-primary-light transition-colors group">
                        <span class="font-bold">Lihat Semua Photo</span>
                        <span
                            class="material-symbols-outlined transition-transform duration-300 group-hover:translate-x-1">arrow_forward</span>
                    </a>
                </div>
            </div>
            <!-- Video Grid -->
            <div id="video-terbaru" class="flex flex-col gap-6 mt-10 scroll-mt-24">
                <div class="flex items-center justify-between">
                    <h2 class="text-text-primary-light dark:text-text-primary-dark text-3xl font-bold">Video Terbaru
                    </h2>
                    <a href="{{ route('gallery') }}#video" wire:navigate
                        class="hidden md:flex items-center gap-2 text-primary hover:text-primary-light transition-colors group">
                        <span class="font-bold">Lihat Semua Video</span>
                        <span
                            class="material-symbols-outlined transition-transform duration-300 group-hover:translate-x-1">arrow_forward</span>
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @forelse($galleryVideos as $video)
                        @php
                            // Extract YouTube video ID from URL
                            $videoId = '';
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video->video_url ?? '', $matches)) {
                                $videoId = $matches[1];
                            }
                        @endphp
                        <div class="group relative rounded-xl overflow-hidden aspect-video bg-black cursor-pointer"
                            @click="openVideo('{{ $videoId }}')">
                            @if($video->image)
                                <img src="{{ asset('storage/' . $video->image) }}" alt="{{ $video->title }}"
                                    class="w-full h-full object-cover opacity-60 group-hover:opacity-40 transition-opacity" />
                            @elseif($videoId)
                                <img src="https://img.youtube.com/vi/{{ $videoId }}/maxresdefault.jpg" alt="{{ $video->title }}"
                                    class="w-full h-full object-cover opacity-60 group-hover:opacity-40 transition-opacity" />
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-primary/20 to-background-dark flex items-center justify-center">
                                    <span class="material-symbols-outlined text-6xl text-primary/30">videocam</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div
                                    class="size-16 rounded-full bg-primary/90 text-text-primary-light dark:text-text-primary-dark flex items-center justify-center pl-1 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-4xl">play_arrow</span>
                                </div>
                            </div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <p class="text-text-primary-light dark:text-text-primary-dark font-bold truncate">
                                    {{ $video->title }}
                                </p>
                            </div>
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
                </div>
                <div class="flex justify-center md:hidden mt-4">
                    <a href="{{ route('gallery') }}#video" wire:navigate
                        class="flex items-center gap-2 text-primary hover:text-primary-light transition-colors group">
                        <span class="font-bold">Lihat Semua Video</span>
                        <span
                            class="material-symbols-outlined transition-transform duration-300 group-hover:translate-x-1">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Lightbox Modal -->
        <div x-show="lightboxOpen"
            class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
            <div @click.outside="lightboxOpen = false"
                class="relative max-w-5xl w-full max-h-screen flex items-center justify-center">
                <img :src="activeImage" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl"
                    alt="Lightbox Image">
                <button @click="lightboxOpen = false"
                    class="absolute -top-12 right-0 text-white hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-4xl">close</span>
                </button>
            </div>
        </div>

        <!-- Video Modal -->
        <div x-show="videoModalOpen"
            class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
            <div @click.outside="closeVideo()"
                class="relative w-full max-w-4xl aspect-video bg-black rounded-2xl overflow-hidden shadow-2xl">
                <iframe :src="activeVideoUrl" class="w-full h-full" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
                <button @click="closeVideo()"
                    class="absolute -top-12 right-0 text-white hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-4xl">close</span>
                </button>
            </div>
        </div>
    </section>
</div>