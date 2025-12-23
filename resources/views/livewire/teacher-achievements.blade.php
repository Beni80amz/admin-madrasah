<div class="min-h-screen pt-24 pb-16 px-5 md:px-10 lg:px-20 xl:px-40 w-full" x-data="{ activeFilter: 'semua' }">
    <div class="max-w-[1200px] mx-auto w-full flex flex-col gap-12">

        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-2 text-sm text-text-secondary-light dark:text-text-secondary-dark">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors" wire:navigate>Beranda</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-gray-500">Akademik</span>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-text-primary-light dark:text-text-primary-dark">Prestasi Guru</span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex flex-col gap-3">
                    <h1 class="text-text-primary-light dark:text-text-primary-dark text-4xl md:text-5xl font-bold">
                        Prestasi Guru</h1>
                    <p
                        class="text-text-secondary-light dark:text-text-secondary-dark max-w-3xl text-lg leading-relaxed">
                        Daftar pencapaian dan prestasi membanggakan dari para guru
                        {{ $siteProfile->nama_madrasah ?? 'Madrasah' }} dalam berbagai bidang
                        kompetisi dan pengembangan profesional
                    </p>
                </div>
                @if($total > 0)
                    <a href="{{ route('akademik.prestasi-guru.download') }}"
                        class="flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full transition-all self-start md:self-center">
                        <span class="material-symbols-outlined text-xl">print</span>
                        Cetak
                    </a>
                @endif
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <div
                class="bg-gradient-to-br from-amber-500/20 to-amber-600/10 border border-amber-500/30 rounded-2xl p-6 text-center">
                <div class="flex justify-center mb-3">
                    <span class="material-symbols-outlined text-amber-400 text-4xl">emoji_events</span>
                </div>
                <p class="text-3xl md:text-4xl font-bold text-text-primary-light dark:text-white">{{ $juara1 }}</p>
                <p class="text-amber-400 text-sm font-medium">Juara 1</p>
            </div>
            <div
                class="bg-gradient-to-br from-gray-400/20 to-gray-500/10 border border-gray-400/30 rounded-2xl p-6 text-center">
                <div class="flex justify-center mb-3">
                    <span class="material-symbols-outlined text-gray-300 text-4xl">military_tech</span>
                </div>
                <p class="text-3xl md:text-4xl font-bold text-text-primary-light dark:text-white">{{ $juara2 }}</p>
                <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">Juara 2</p>
            </div>
            <div
                class="bg-gradient-to-br from-orange-600/20 to-orange-700/10 border border-orange-600/30 rounded-2xl p-6 text-center">
                <div class="flex justify-center mb-3">
                    <span class="material-symbols-outlined text-orange-400 text-4xl">workspace_premium</span>
                </div>
                <p class="text-3xl md:text-4xl font-bold text-text-primary-light dark:text-white">{{ $juara3 }}</p>
                <p class="text-orange-400 text-sm font-medium">Juara 3</p>
            </div>
            <div
                class="bg-gradient-to-br from-primary/20 to-emerald-600/10 border border-primary/30 rounded-2xl p-6 text-center">
                <div class="flex justify-center mb-3">
                    <span class="material-symbols-outlined text-primary text-4xl">star</span>
                </div>
                <p class="text-3xl md:text-4xl font-bold text-text-primary-light dark:text-white">{{ $total }}</p>
                <p class="text-primary text-sm font-medium">Total Prestasi</p>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex flex-wrap gap-3">
            <button @click="activeFilter = 'semua'"
                :class="activeFilter === 'semua' ? 'bg-primary text-white' : 'bg-surface-light dark:bg-surface-dark text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:hover:text-text-primary-dark border border-border-light dark:border-border-dark'"
                class="px-6 py-2.5 rounded-full font-bold text-sm transition-all">
                Semua
            </button>
            <button @click="activeFilter = 'Akademik'"
                :class="activeFilter === 'Akademik' ? 'bg-primary text-white' : 'bg-surface-light dark:bg-surface-dark text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:hover:text-text-primary-dark border border-border-light dark:border-border-dark'"
                class="px-6 py-2.5 rounded-full font-bold text-sm transition-all">
                Akademik
            </button>
            <button @click="activeFilter = 'Keagamaan'"
                :class="activeFilter === 'Keagamaan' ? 'bg-primary text-white' : 'bg-surface-light dark:bg-surface-dark text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:hover:text-text-primary-dark border border-border-light dark:border-border-dark'"
                class="px-6 py-2.5 rounded-full font-bold text-sm transition-all">
                Keagamaan
            </button>
            <button @click="activeFilter = 'Olahraga'"
                :class="activeFilter === 'Olahraga' ? 'bg-primary text-white' : 'bg-surface-light dark:bg-surface-dark text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:hover:text-text-primary-dark border border-border-light dark:border-border-dark'"
                class="px-6 py-2.5 rounded-full font-bold text-sm transition-all">
                Olahraga
            </button>
            <button @click="activeFilter = 'Seni dan Budaya'"
                :class="activeFilter === 'Seni dan Budaya' ? 'bg-primary text-white' : 'bg-surface-light dark:bg-surface-dark text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:hover:text-text-primary-dark border border-border-light dark:border-border-dark'"
                class="px-6 py-2.5 rounded-full font-bold text-sm transition-all">
                Seni & Budaya
            </button>
        </div>

        <!-- Achievements Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($achievements as $achievement)
                @php
                    // Determine icon based on category
                    $icon = match ($achievement->kategori) {
                        'Akademik' => 'school',
                        'Keagamaan' => 'menu_book',
                        'Olahraga' => 'sports_soccer',
                        'Seni dan Budaya' => 'palette',
                        default => 'emoji_events',
                    };
                @endphp
                <div x-show="activeFilter === 'semua' || activeFilter === '{{ $achievement->kategori }}'"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl overflow-hidden hover:border-primary/50 transition-all group">
                    <!-- Header with rank badge -->
                    <div class="relative p-6 pb-4">
                        <div class="absolute top-4 right-4">
                            @if($achievement->peringkat === 1)
                                <div
                                    class="size-12 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg">
                                    <span class="text-white font-bold text-lg">1</span>
                                </div>
                            @elseif($achievement->peringkat === 2)
                                <div
                                    class="size-12 rounded-full bg-gradient-to-br from-gray-300 to-gray-500 flex items-center justify-center shadow-lg">
                                    <span class="text-white font-bold text-lg">2</span>
                                </div>
                            @elseif($achievement->peringkat === 3)
                                <div
                                    class="size-12 rounded-full bg-gradient-to-br from-orange-500 to-orange-700 flex items-center justify-center shadow-lg">
                                    <span class="text-white font-bold text-lg">3</span>
                                </div>
                            @else
                                <div
                                    class="size-12 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center shadow-lg">
                                    <span class="text-white font-bold text-xs">H{{ $achievement->peringkat - 3 }}</span>
                                </div>
                            @endif
                        </div>

                        <div
                            class="size-14 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-2xl">{{ $icon }}</span>
                        </div>

                        <h3
                            class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg pr-14 leading-tight">
                            {{ $achievement->prestasi }}
                        </h3>
                        <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm mt-2">
                            {{ $achievement->event }}
                        </p>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-border-light dark:border-border-dark"></div>

                    <!-- Footer with teacher info -->
                    <div class="p-6 pt-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            @if($achievement->photo_url)
                                <img src="/storage/{{ $achievement->photo_url }}" alt="{{ $achievement->nama }}"
                                    class="size-10 rounded-full object-cover">
                            @else
                                <div class="size-10 rounded-full bg-primary/20 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-lg">badge</span>
                                </div>
                            @endif
                            <div>
                                <p class="text-text-primary-light dark:text-text-primary-dark text-sm font-medium">
                                    {{ $achievement->nama }}
                                </p>
                                <p class="text-gray-500 text-xs">{{ $achievement->kelas ?? 'Guru' }}</p>
                            </div>
                        </div>
                        <span
                            class="text-xs text-text-secondary-light dark:text-text-secondary-dark bg-gray-100 dark:bg-white/5 px-3 py-1 rounded-full border border-border-light dark:border-white/10">{{ $achievement->tahun }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                    <span class="material-symbols-outlined text-6xl text-gray-600 mb-4">emoji_events</span>
                    <h3 class="text-text-primary-light dark:text-text-primary-dark text-xl font-bold mb-2">Belum Ada Data
                        Prestasi</h3>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark">Data prestasi guru akan ditampilkan
                        di sini</p>
                </div>
            @endforelse
        </div>

        <!-- CTA Section -->
        <div
            class="bg-gradient-to-r from-primary/10 to-emerald-900/20 border border-primary/30 rounded-2xl p-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex flex-col gap-2">
                <h3 class="text-text-primary-light dark:text-text-primary-dark font-bold text-xl">Bergabung Bersama Tim
                    Pengajar Kami</h3>
                <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm max-w-lg">
                    Kami selalu mencari guru-guru berdedikasi tinggi untuk bergabung dalam keluarga besar
                    {{ $siteProfile->nama_madrasah ?? 'Madrasah' }}
                </p>
            </div>
            <a href="{{ route('contact') }}" wire:navigate
                class="flex items-center gap-2 px-6 py-3 bg-primary text-white font-bold rounded-full hover:brightness-110 transition-all whitespace-nowrap">
                <span class="material-symbols-outlined">work</span>
                Lihat Lowongan
            </a>
        </div>

    </div>
</div>