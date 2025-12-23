<div class="min-h-screen pt-24 pb-16 px-5 md:px-10 lg:px-20 xl:px-40 w-full">
    <div class="max-w-[1200px] mx-auto w-full flex flex-col gap-12">

        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-2 text-sm text-text-secondary-light dark:text-text-secondary-dark">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors" wire:navigate>Beranda</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-gray-500">Akademik</span>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-text-primary-light dark:text-text-primary-dark">Kurikulum</span>
            </div>
            <div class="flex flex-col gap-3">
                <h1 class="text-text-primary-light dark:text-text-primary-dark text-4xl md:text-5xl font-bold">Kurikulum
                    Pendidikan</h1>
                <p class="text-text-secondary-light dark:text-text-secondary-dark max-w-3xl text-lg leading-relaxed">
                    Kurikulum Merdeka yang terintegrasi dengan Kurikulum Berbasis Cinta dari Kementerian Agama Republik
                    Indonesia untuk membentuk generasi berkarakter dan berakhlak mulia.
                </p>
            </div>
        </div>

        <!-- Hero Section with Illustration -->
        <div
            class="relative rounded-3xl overflow-hidden border border-border-dark bg-gradient-to-br from-surface-dark to-[#0a1f14]">
            <div class="absolute inset-0 opacity-5">
                <div class="absolute top-10 right-10 w-72 h-72 bg-primary rounded-full blur-3xl"></div>
                <div class="absolute bottom-10 left-10 w-96 h-96 bg-emerald-600 rounded-full blur-3xl"></div>
            </div>
            <div class="relative p-8 md:p-12 flex flex-col lg:flex-row items-center gap-8">
                <div class="flex-1 flex flex-col gap-6">
                    <div class="flex items-center gap-3">
                        <span
                            class="px-4 py-2 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/20">
                            Tahun Ajaran {{ $tahunAjaran->nama ?? '-' }}
                        </span>
                    </div>
                    <h2 class="text-white text-2xl md:text-3xl font-bold leading-tight">
                        Kurikulum Merdeka + Kurikulum Berbasis Cinta
                    </h2>
                    <p class="text-gray-300 leading-relaxed">
                        Integrasi unik antara Kurikulum Merdeka dari Kemendikbudristek dengan Kurikulum Berbasis Cinta
                        dari Kementerian Agama, menghasilkan pendekatan pendidikan yang holistik, berpusat pada siswa,
                        dan bernafaskan nilai-nilai Islam rahmatan lil 'alamin.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <span
                            class="flex items-center gap-2 text-sm text-gray-300 bg-white/5 px-4 py-2 rounded-full border border-white/10">
                            <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                            Merdeka Belajar
                        </span>
                        <span
                            class="flex items-center gap-2 text-sm text-gray-300 bg-white/5 px-4 py-2 rounded-full border border-white/10">
                            <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                            Berbasis Cinta
                        </span>
                        <span
                            class="flex items-center gap-2 text-sm text-gray-300 bg-white/5 px-4 py-2 rounded-full border border-white/10">
                            <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                            Nilai-nilai Islam
                        </span>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <div class="relative w-64 h-64 md:w-80 md:h-80">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-primary/20 to-emerald-600/20 rounded-full animate-pulse">
                        </div>
                        <div
                            class="absolute inset-4 bg-surface-dark rounded-full flex items-center justify-center border border-primary/30 overflow-hidden">
                            @if($profile && $profile->logo)
                                <img src="{{ Storage::url($profile->logo) }}" alt="Logo Madrasah"
                                    class="w-1/2 h-1/2 object-contain">
                            @else
                                <span
                                    class="material-symbols-outlined text-[100px] md:text-[120px] text-primary">menu_book</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kurikulum Merdeka Section -->
        <section class="flex flex-col gap-8">
            <div class="flex items-center gap-4">
                <div
                    class="size-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                    <span
                        class="material-symbols-outlined text-text-primary-light dark:text-text-primary-dark text-3xl">auto_awesome</span>
                </div>
                <div>
                    <h2 class="text-text-primary-light dark:text-text-primary-dark text-2xl md:text-3xl font-bold">
                        Kurikulum Merdeka</h2>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Kemendikbudristek
                        Republik Indonesia</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Pengertian Card -->
                <div
                    class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl p-6 hover:border-blue-500/50 transition-colors">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-blue-400 text-2xl">info</span>
                        <h3 class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg">Pengertian
                        </h3>
                    </div>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark leading-relaxed text-sm">
                        Kurikulum Merdeka adalah kurikulum dengan pembelajaran intrakurikuler yang beragam di mana
                        konten akan lebih optimal agar peserta didik memiliki cukup waktu untuk mendalami konsep dan
                        menguatkan kompetensi. Guru memiliki keleluasaan untuk memilih berbagai perangkat ajar sehingga
                        pembelajaran dapat disesuaikan dengan kebutuhan belajar dan minat peserta didik.
                    </p>
                </div>

                <!-- Karakteristik Card -->
                <div
                    class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl p-6 hover:border-blue-500/50 transition-colors">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-blue-400 text-2xl">category</span>
                        <h3 class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg">Karakteristik
                            Utama</h3>
                    </div>
                    <ul class="space-y-3">
                        <li
                            class="flex items-start gap-3 text-text-secondary-light dark:text-text-secondary-dark text-sm">
                            <span
                                class="material-symbols-outlined text-blue-400 shrink-0 mt-0.5 text-lg">check_circle</span>
                            <span>Pembelajaran berbasis proyek (Project-Based Learning)</span>
                        </li>
                        <li
                            class="flex items-start gap-3 text-text-secondary-light dark:text-text-secondary-dark text-sm">
                            <span
                                class="material-symbols-outlined text-blue-400 shrink-0 mt-0.5 text-lg">check_circle</span>
                            <span>Fokus pada materi esensial dan pengembangan kompetensi</span>
                        </li>
                        <li
                            class="flex items-start gap-3 text-text-secondary-light dark:text-text-secondary-dark text-sm">
                            <span
                                class="material-symbols-outlined text-blue-400 shrink-0 mt-0.5 text-lg">check_circle</span>
                            <span>Fleksibilitas bagi guru dalam pembelajaran berdiferensiasi</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Delapan Dimensi Profil Lulusan (8 DPL) -->
            <div
                class="bg-gradient-to-r from-blue-900/30 to-surface-dark border border-blue-500/20 rounded-2xl p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-blue-400 text-2xl">school</span>
                    <h3 class="text-white font-bold text-xl">Delapan Dimensi
                        Profil Lulusan (8 DPL)</h3>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                    @php
                        $dimensions = [
                            ['icon' => 'self_improvement', 'title' => 'Keimanan & Ketakwaan', 'color' => 'from-amber-500 to-orange-500'],
                            ['icon' => 'flag', 'title' => 'Kewargaan', 'color' => 'from-red-500 to-rose-500'],
                            ['icon' => 'psychology', 'title' => 'Penalaran Kritis', 'color' => 'from-purple-500 to-violet-500'],
                            ['icon' => 'lightbulb', 'title' => 'Kreativitas', 'color' => 'from-yellow-500 to-amber-500'],
                            ['icon' => 'handshake', 'title' => 'Kolaborasi', 'color' => 'from-blue-500 to-cyan-500'],
                            ['icon' => 'rocket_launch', 'title' => 'Kemandirian', 'color' => 'from-green-500 to-emerald-500'],
                            ['icon' => 'favorite', 'title' => 'Kesehatan', 'color' => 'from-pink-500 to-rose-500'],
                            ['icon' => 'forum', 'title' => 'Komunikasi', 'color' => 'from-indigo-500 to-blue-500'],
                        ];
                    @endphp
                    @foreach($dimensions as $dimension)
                        <div
                            class="flex flex-col items-center gap-3 p-4 rounded-xl bg-white/5 border border-white/10 hover:border-blue-400/50 transition-colors group">
                            <div
                                class="size-12 rounded-full bg-gradient-to-br {{ $dimension['color'] }} flex items-center justify-center group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-white text-xl">{{ $dimension['icon'] }}</span>
                            </div>
                            <span
                                class="text-white text-xs font-medium text-center leading-tight">{{ $dimension['title'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Kurikulum Berbasis Cinta Section -->
        <section class="flex flex-col gap-8">
            <div class="flex items-center gap-4">
                <div
                    class="size-14 rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center shadow-lg">
                    <span class="material-symbols-outlined text-white text-3xl">favorite</span>
                </div>
                <div>
                    <h2 class="text-text-primary-light dark:text-text-primary-dark text-2xl md:text-3xl font-bold">
                        Kurikulum Berbasis Cinta</h2>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Kementerian Agama
                        Republik Indonesia</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Pengertian Card -->
                <div
                    class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl p-6 hover:border-rose-500/50 transition-colors">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-rose-400 text-2xl">info</span>
                        <h3 class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg">Pengertian
                        </h3>
                    </div>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark leading-relaxed text-sm">
                        Kurikulum Berbasis Cinta adalah pendekatan pendidikan yang dikembangkan oleh Kementerian Agama
                        yang menempatkan cinta sebagai fondasi utama dalam proses pembelajaran. Kurikulum ini menekankan
                        pada pembentukan karakter peserta didik yang mencintai Allah SWT, Rasulullah SAW, sesama
                        manusia, alam semesta, dan ilmu pengetahuan.
                    </p>
                </div>

                <!-- Panca Cinta (Lima Pilar Cinta) Card -->
                <div
                    class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl p-6 hover:border-rose-500/50 transition-colors">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-rose-400 text-2xl">foundation</span>
                        <h3 class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg">Panca Cinta
                            (Lima Pilar Cinta)</h3>
                    </div>
                    <ul class="space-y-3">
                        <li
                            class="flex items-start gap-3 text-text-secondary-light dark:text-text-secondary-dark text-sm">
                            <span class="material-symbols-outlined text-rose-400 shrink-0 mt-0.5 text-lg">mosque</span>
                            <span><strong class="text-text-primary-light dark:text-text-primary-dark">Cinta Allah SWT
                                    dan Rasul-Nya</strong> - Fondasi spiritual
                                utama</span>
                        </li>
                        <li
                            class="flex items-start gap-3 text-text-secondary-light dark:text-text-secondary-dark text-sm">
                            <span
                                class="material-symbols-outlined text-rose-400 shrink-0 mt-0.5 text-lg">auto_stories</span>
                            <span><strong class="text-text-primary-light dark:text-text-primary-dark">Cinta
                                    Ilmu</strong> - Semangat belajar sepanjang
                                hayat</span>
                        </li>
                        <li
                            class="flex items-start gap-3 text-text-secondary-light dark:text-text-secondary-dark text-sm">
                            <span class="material-symbols-outlined text-rose-400 shrink-0 mt-0.5 text-lg">eco</span>
                            <span><strong class="text-text-primary-light dark:text-text-primary-dark">Cinta
                                    Lingkungan</strong> - Menjaga kelestarian alam</span>
                        </li>
                        <li
                            class="flex items-start gap-3 text-text-secondary-light dark:text-text-secondary-dark text-sm">
                            <span
                                class="material-symbols-outlined text-rose-400 shrink-0 mt-0.5 text-lg">diversity_1</span>
                            <span><strong class="text-text-primary-light dark:text-text-primary-dark">Cinta Diri dan
                                    Sesama Manusia</strong> - Membangun empati
                                dan toleransi</span>
                        </li>
                        <li
                            class="flex items-start gap-3 text-text-secondary-light dark:text-text-secondary-dark text-sm">
                            <span class="material-symbols-outlined text-rose-400 shrink-0 mt-0.5 text-lg">flag</span>
                            <span><strong class="text-text-primary-light dark:text-text-primary-dark">Cinta Tanah
                                    Air</strong> - Patriotisme dan
                                nasionalisme</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Nilai-nilai Inti -->
            <div
                class="bg-gradient-to-r from-rose-900/30 to-surface-dark border border-rose-500/20 rounded-2xl p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-rose-400 text-2xl">stars</span>
                    <h3 class="text-white font-bold text-xl">Nilai-nilai Inti
                        Kurikulum Berbasis Cinta</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @php
                        $values = [
                            ['icon' => 'mosque', 'title' => 'Religius', 'desc' => 'Beriman, bertakwa, dan berakhlak mulia'],
                            ['icon' => 'diversity_1', 'title' => 'Toleransi', 'desc' => 'Menghargai perbedaan dan keragaman'],
                            ['icon' => 'eco', 'title' => 'Peduli Lingkungan', 'desc' => 'Menjaga kelestarian alam ciptaan Allah'],
                            ['icon' => 'auto_stories', 'title' => 'Cinta Ilmu', 'desc' => 'Semangat belajar sepanjang hayat'],
                        ];
                    @endphp
                    @foreach($values as $value)
                        <div
                            class="flex flex-col gap-3 p-5 rounded-xl bg-white/5 border border-white/10 hover:border-rose-400/50 transition-colors group">
                            <div
                                class="size-12 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-white text-2xl">{{ $value['icon'] }}</span>
                            </div>
                            <div>
                                <h4 class="text-white font-bold">
                                    {{ $value['title'] }}
                                </h4>
                                <p class="text-gray-300 text-xs mt-1">
                                    {{ $value['desc'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Integrasi Kurikulum Section -->
        <section class="flex flex-col gap-8">
            <div class="flex items-center gap-4">
                <div
                    class="size-14 rounded-2xl bg-gradient-to-br from-primary to-emerald-600 flex items-center justify-center shadow-lg">
                    <span
                        class="material-symbols-outlined text-text-primary-light dark:text-text-primary-dark text-3xl">join_inner</span>
                </div>
                <div>
                    <h2 class="text-text-primary-light dark:text-text-primary-dark text-2xl md:text-3xl font-bold">
                        Integrasi di Madrasah Kami</h2>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Penerapan kurikulum
                        terpadu yang unik</p>
                </div>
            </div>

            <!-- Integration Diagram -->
            <div
                class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-3xl p-8 md:p-10">
                <div class="flex flex-col items-center gap-8">
                    <!-- Visual Diagram -->
                    <div class="relative w-full max-w-2xl">
                        <div class="flex items-center justify-center gap-4 md:gap-8">
                            <!-- Kurikulum Merdeka Circle -->
                            <div class="relative">
                                <div
                                    class="size-32 md:size-40 rounded-full bg-gradient-to-br from-blue-500/20 to-blue-600/10 border-2 border-blue-500 flex items-center justify-center">
                                    <div class="text-center">
                                        <span
                                            class="material-symbols-outlined text-blue-400 text-3xl md:text-4xl">auto_awesome</span>
                                        <p
                                            class="text-text-primary-light dark:text-text-primary-dark text-xs md:text-sm font-bold mt-2">
                                            Kurikulum<br>Merdeka</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Plus Sign -->
                            <div class="size-10 md:size-12 rounded-full bg-primary flex items-center justify-center">
                                <span
                                    class="material-symbols-outlined text-text-primary-light dark:text-text-primary-dark text-2xl md:text-3xl">add</span>
                            </div>

                            <!-- Kurikulum Berbasis Cinta Circle -->
                            <div class="relative">
                                <div
                                    class="size-32 md:size-40 rounded-full bg-gradient-to-br from-rose-500/20 to-pink-600/10 border-2 border-rose-500 flex items-center justify-center">
                                    <div class="text-center">
                                        <span
                                            class="material-symbols-outlined text-rose-400 text-3xl md:text-4xl">favorite</span>
                                        <p
                                            class="text-text-primary-light dark:text-text-primary-dark text-xs md:text-sm font-bold mt-2">
                                            Kurikulum<br>Berbasis
                                            Cinta</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Arrow Down -->
                        <div class="flex justify-center my-6">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-1 h-8 bg-gradient-to-b from-primary to-emerald-600 rounded-full"></div>
                                <span class="material-symbols-outlined text-primary text-3xl">arrow_downward</span>
                            </div>
                        </div>

                        <!-- Result -->
                        <div class="flex justify-center">
                            <div
                                class="px-8 py-6 rounded-2xl bg-gradient-to-r from-primary/20 to-emerald-600/20 border-2 border-primary">
                                <div class="text-center">
                                    <span
                                        class="material-symbols-outlined text-primary text-4xl md:text-5xl">school</span>
                                    <h3
                                        class="text-text-primary-light dark:text-text-primary-dark text-lg md:text-xl font-bold mt-3">
                                        Kurikulum Terpadu Madrasah
                                    </h3>
                                    <p
                                        class="text-text-secondary-light dark:text-text-secondary-dark text-sm mt-2 max-w-md">
                                        Pendidikan holistik yang menyeimbangkan kompetensi akademik, spiritual, dan
                                        karakter
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Key Features of Integration -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full mt-4">
                        <div
                            class="flex flex-col items-center text-center gap-3 p-6 rounded-xl bg-white/5 border border-white/10">
                            <div
                                class="size-14 rounded-full bg-gradient-to-br from-primary to-emerald-600 flex items-center justify-center">
                                <span
                                    class="material-symbols-outlined text-text-primary-light dark:text-text-primary-dark text-2xl">balance</span>
                            </div>
                            <h4 class="text-text-primary-light dark:text-text-primary-dark font-bold">Keseimbangan</h4>
                            <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Menyeimbangkan
                                aspek kognitif, afektif, dan psikomotorik
                                dalam pembelajaran</p>
                        </div>
                        <div
                            class="flex flex-col items-center text-center gap-3 p-6 rounded-xl bg-white/5 border border-white/10">
                            <div
                                class="size-14 rounded-full bg-gradient-to-br from-primary to-emerald-600 flex items-center justify-center">
                                <span
                                    class="material-symbols-outlined text-text-primary-light dark:text-text-primary-dark text-2xl">diversity_2</span>
                            </div>
                            <h4 class="text-text-primary-light dark:text-text-primary-dark font-bold">Inklusif</h4>
                            <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Mengakomodasi
                                kebutuhan dan potensi setiap peserta didik
                                secara individual</p>
                        </div>
                        <div
                            class="flex flex-col items-center text-center gap-3 p-6 rounded-xl bg-white/5 border border-white/10">
                            <div
                                class="size-14 rounded-full bg-gradient-to-br from-primary to-emerald-600 flex items-center justify-center">
                                <span
                                    class="material-symbols-outlined text-text-primary-light dark:text-text-primary-dark text-2xl">trending_up</span>
                            </div>
                            <h4 class="text-text-primary-light dark:text-text-primary-dark font-bold">Berkelanjutan</h4>
                            <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Pengembangan
                                karakter dan kompetensi secara bertahap dan
                                berkesinambungan</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Struktur Kurikulum Section -->
        <section class="flex flex-col gap-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="size-14 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg">
                        <span
                            class="material-symbols-outlined text-text-primary-light dark:text-text-primary-dark text-3xl">account_tree</span>
                    </div>
                    <div>
                        <h2 class="text-text-primary-light dark:text-text-primary-dark text-2xl md:text-3xl font-bold">
                            Struktur Kurikulum</h2>
                        <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Pembagian mata
                            pelajaran dan beban belajar</p>
                    </div>
                </div>
                <a href="{{ route('akademik.kurikulum.download') }}" title="Export Struktur Kurikulum PDF"
                    class="inline-flex items-center justify-center p-2 bg-primary/10 border border-primary/30 text-primary rounded-lg hover:bg-primary/20 transition-colors shrink-0">
                    <span class="material-symbols-outlined text-base">print</span>
                </a>
            </div>

            <!-- Subject Categories -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Kelompok A: Muatan Nasional -->
                <div
                    class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold bg-violet-500/20 text-violet-400 border border-violet-500/30">Kelompok
                            A</span>
                        <h3 class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg">Mata Pelajaran
                            Wajib</h3>
                    </div>
                    <div class="space-y-3">
                        @forelse($subjectsA as $subject)
                            <div
                                class="flex items-center justify-between py-3 px-4 rounded-xl bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                                <span
                                    class="text-text-primary-light dark:text-text-primary-dark text-sm">{{ $subject->nama }}</span>
                                <span
                                    class="text-violet-400 text-xs font-bold bg-violet-500/10 px-2 py-1 rounded-full">{{ $subject->beban_jam_minggu ? $subject->beban_jam_minggu . ' JP/Minggu' : '-' }}</span>
                            </div>
                        @empty
                            <div class="text-gray-500 text-sm italic text-center py-4">Belum ada data mata pelajaran</div>
                        @endforelse
                    </div>
                </div>

                <!-- Kelompok B: Muatan Kementerian Agama -->
                <div
                    class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">Kelompok
                            B</span>
                        <h3 class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg">Mata Pelajaran
                            Pilihan</h3>
                    </div>
                    <div class="space-y-3">
                        @forelse($subjectsB as $subject)
                            <div
                                class="flex items-center justify-between py-3 px-4 rounded-xl bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                                <span
                                    class="text-text-primary-light dark:text-text-primary-dark text-sm">{{ $subject->nama }}</span>
                                <span
                                    class="text-amber-400 text-xs font-bold bg-amber-500/10 px-2 py-1 rounded-full">{{ $subject->beban_jam_minggu ? $subject->beban_jam_minggu . ' JP/Minggu' : '-' }}</span>
                            </div>
                        @empty
                            <div class="text-gray-500 text-sm italic text-center py-4">Belum ada data mata pelajaran</div>
                        @endforelse
                    </div>
                </div>

                <!-- Kelompok C: Muatan Lokal & Pengembangan Diri -->
                <div
                    class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">Kelompok
                            C</span>
                        <h3 class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg">Muatan Lokal
                        </h3>
                    </div>
                    <div class="space-y-3">
                        @forelse($subjectsC as $subject)
                            <div
                                class="flex items-center justify-between py-3 px-4 rounded-xl bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                                <span
                                    class="text-text-primary-light dark:text-text-primary-dark text-sm">{{ $subject->nama }}</span>
                                <span
                                    class="text-cyan-400 text-xs font-bold bg-cyan-500/10 px-2 py-1 rounded-full">{{ $subject->beban_jam_minggu ? $subject->beban_jam_minggu . ' JP/Minggu' : '-' }}</span>
                            </div>
                        @empty
                            <div class="text-gray-500 text-sm italic text-center py-4">Belum ada data mata pelajaran</div>
                        @endforelse
                    </div>
                </div>

                <!-- Kegiatan Kokurikuler -->
                <div
                    class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold bg-primary/20 text-primary border border-primary/30">Kokurikuler</span>
                        <h3 class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg">Kegiatan
                            Kokurikuler</h3>
                    </div>
                    <div class="space-y-3">
                        @forelse($subjectsKokurikuler as $item)
                            <div
                                class="flex items-center justify-between py-3 px-4 rounded-xl bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                                <span
                                    class="text-text-primary-light dark:text-text-primary-dark text-sm">{{ $item->nama }}</span>
                                <span
                                    class="text-primary text-xs font-bold bg-primary/10 px-2 py-1 rounded-full">{{ $item->beban_jam_minggu ? $item->beban_jam_minggu . ' JP/Minggu' : 'Terintegrasi' }}</span>
                            </div>
                        @empty
                            <div class="text-gray-500 text-sm italic text-center py-4">Belum ada data kegiatan kokurikuler
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-4 p-4 rounded-xl bg-primary/5 border border-primary/20">
                        <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm leading-relaxed">
                            <span class="text-primary font-semibold">Kegiatan Kokurikuler</span> merupakan kegiatan yang
                            terintegrasi atau berbasis <strong
                                class="text-text-primary-light dark:text-text-primary-dark">7 Kebiasaan Anak Hebat
                                Indonesia</strong> dan kegiatan yang berhubungan dengan <strong
                                class="text-text-primary-light dark:text-text-primary-dark">peduli lingkungan
                                sekitar</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section
            class="relative rounded-3xl overflow-hidden border border-primary/30 bg-gradient-to-br from-primary/10 to-emerald-900/20">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-96 h-96 bg-primary rounded-full blur-3xl"></div>
            </div>
            <div class="relative p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex flex-col gap-3">
                    <h2 class="text-text-primary-light dark:text-text-primary-dark text-2xl md:text-3xl font-bold">Ingin
                        Mengetahui Lebih Lanjut?</h2>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark max-w-xl">
                        Hubungi kami untuk informasi lengkap mengenai kurikulum, program pembelajaran, dan jadwal
                        kunjungan madrasah.
                    </p>
                </div>
                <a href="{{ route('contact') }}" wire:navigate
                    class="flex items-center gap-2 px-8 py-4 bg-primary text-white font-bold rounded-full hover:brightness-110 transition-all shadow-lg hover:shadow-xl hover:-translate-y-1 whitespace-nowrap">
                    <span class="material-symbols-outlined">mail</span>
                    Hubungi Kami
                </a>
            </div>
        </section>

    </div>
</div>