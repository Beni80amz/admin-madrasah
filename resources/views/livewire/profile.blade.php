<div class="min-h-screen pt-24 pb-16 px-5 md:px-10 lg:px-20 xl:px-40 w-full" x-data="{ activeSection: 'sejarah' }">
    <div class="max-w-[1200px] mx-auto w-full flex flex-col gap-10">

        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-2 text-sm text-text-secondary-light dark:text-text-secondary-dark">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors" wire:navigate>Beranda</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-text-primary-light dark:text-text-primary-dark">Profil Madrasah</span>
            </div>
            <div class="flex flex-col gap-2">
                <h1 class="text-text-primary-light dark:text-text-primary-dark text-4xl md:text-5xl font-bold">Profil
                    Madrasah</h1>
                <p class="text-text-secondary-light dark:text-text-secondary-dark max-w-2xl text-lg">
                    Mengenal lebih dekat sejarah, visi, dan identitas kami sebagai lembaga pendidikan Islam yang unggul
                    dan berprestasi.
                </p>
            </div>
        </div>

        <!-- Navigation Tabs (Sticky) -->
        <div
            class="sticky top-24 z-40 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md py-4 border-b border-border-light dark:border-border-dark">
            <div class="flex flex-wrap gap-2 md:gap-4 overflow-x-auto no-scrollbar pb-2">
                <a href="#identitas"
                    @click.prevent="document.getElementById('identitas').scrollIntoView({behavior: 'smooth'})"
                    class="px-6 py-2 rounded-full text-sm font-bold transition-all border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark text-text-primary-light dark:text-text-primary-dark hover:border-primary hover:text-primary whitespace-nowrap">
                    Identitas
                </a>
                <a href="#sejarah"
                    @click.prevent="document.getElementById('sejarah').scrollIntoView({behavior: 'smooth'})"
                    class="px-6 py-2 rounded-full text-sm font-bold transition-all border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark text-text-primary-light dark:text-text-primary-dark hover:border-primary hover:text-primary whitespace-nowrap">
                    Sejarah
                </a>
                <a href="#visi-misi"
                    @click.prevent="document.getElementById('visi-misi').scrollIntoView({behavior: 'smooth'})"
                    class="px-6 py-2 rounded-full text-sm font-bold transition-all border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark text-text-primary-light dark:text-text-primary-dark hover:border-primary hover:text-primary whitespace-nowrap">
                    Visi
                </a>
                <a href="#misi"
                    @click.prevent="document.getElementById('misi').scrollIntoView({behavior: 'smooth'})"
                    class="px-6 py-2 rounded-full text-sm font-bold transition-all border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark text-text-primary-light dark:text-text-primary-dark hover:border-primary hover:text-primary whitespace-nowrap">
                    Misi
                </a>
                <a href="#tujuan"
                    @click.prevent="document.getElementById('tujuan').scrollIntoView({behavior: 'smooth'})"
                    class="px-6 py-2 rounded-full text-sm font-bold transition-all border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark text-text-primary-light dark:text-text-primary-dark hover:border-primary hover:text-primary whitespace-nowrap">
                    Tujuan
                </a>
                <a href="#struktur"
                    @click.prevent="document.getElementById('struktur').scrollIntoView({behavior: 'smooth'})"
                    class="px-6 py-2 rounded-full text-sm font-bold transition-all border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark text-text-primary-light dark:text-text-primary-dark hover:border-primary hover:text-primary whitespace-nowrap">
                    Struktur Organisasi
                </a>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="flex flex-col gap-20">

            <!-- Identitas Resmi -->
            <section id="identitas" class="scroll-mt-48 flex flex-col gap-8">
                <!-- Header with animation -->
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="flex flex-col gap-2">
                        <h2 class="text-text-primary-light dark:text-text-primary-dark text-3xl font-bold">Identitas
                            Resmi</h2>
                        <address class="text-text-secondary-light dark:text-text-secondary-dark not-italic">Data pokok
                            lembaga yang terdaftar di Kementerian
                            Agama.
                        </address>
                    </div>
                    <a href="{{ route('profil.download') }}" title="Export Profil PDF"
                        class="inline-flex items-center justify-center p-2 bg-primary/10 border border-primary/30 text-primary rounded-lg hover:bg-primary/20 hover:scale-110 transition-all shrink-0">
                        <span class="material-symbols-outlined text-base">print</span>
                    </a>
                </div>

                <!-- Card without animation -->
                <div
                    class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-3xl p-6 lg:p-8 flex flex-col lg:flex-row gap-10 items-center lg:items-start hover:shadow-lg hover:shadow-primary/5 transition-shadow duration-500">
                    <!-- Logo / Badge with float animation -->
                    <div class="w-full lg:w-1/3 flex justify-center">
                        <div
                            class="size-48 rounded-3xl bg-white flex items-center justify-center shadow-[0_0_40px_rgba(16,185,129,0.1)] overflow-hidden hover:shadow-[0_0_60px_rgba(16,185,129,0.2)] transition-shadow duration-500 animate-float">
                            @if($profile && $profile->logo)
                                <img src="{{ Storage::url($profile->logo) }}" alt="Logo Madrasah"
                                    class="w-full h-full object-contain p-4 hover:scale-105 transition-transform duration-500">
                            @else
                                <span class="material-symbols-outlined text-[80px] text-primary">verified_user</span>
                            @endif
                        </div>
                    </div>

                    <!-- Table Data -->
                    <div class="w-full lg:w-2/3">
                        <div class="flex flex-col divide-y divide-border-light dark:divide-border-dark">
                            <!-- Row 1 -->
                            <div class="flex flex-col sm:flex-row sm:justify-between py-4 gap-1 sm:gap-0">
                                <span
                                    class="text-text-secondary-light dark:text-text-secondary-dark text-sm sm:text-base">Nama
                                    Madrasah</span>
                                <span
                                    class="text-text-primary-light dark:text-text-primary-dark font-bold text-left sm:text-right">{{ $profile->nama_madrasah ?? 'Belum diisi' }}</span>
                            </div>
                            <!-- Row 2 -->
                            <div class="flex flex-col sm:flex-row sm:justify-between py-4 gap-1 sm:gap-0">
                                <span
                                    class="text-text-secondary-light dark:text-text-secondary-dark text-sm sm:text-base">Nomor
                                    Statistik Madrasah (NSM)</span>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-text-primary-light dark:text-text-primary-dark font-mono bg-border-light dark:bg-border-dark px-2 py-0.5 rounded text-sm">{{ $profile->nsm ?? '-' }}</span>
                                </div>
                            </div>
                            <!-- Row 3 -->
                            <div class="flex flex-col sm:flex-row sm:justify-between py-4 gap-1 sm:gap-0">
                                <span
                                    class="text-text-secondary-light dark:text-text-secondary-dark text-sm sm:text-base">NPSN</span>
                                <span
                                    class="text-text-primary-light dark:text-text-primary-dark font-mono bg-border-light dark:bg-border-dark px-2 py-0.5 rounded text-sm w-fit">{{ $profile->npsn ?? '-' }}</span>
                            </div>
                            <!-- Row 4 -->
                            <div class="flex flex-col sm:flex-row sm:justify-between py-4 gap-1 sm:gap-0">
                                <span
                                    class="text-text-secondary-light dark:text-text-secondary-dark text-sm sm:text-base">Tahun
                                    Berdiri</span>
                                <span
                                    class="text-text-primary-light dark:text-text-primary-dark font-bold text-left sm:text-right">{{ $profile->tahun_berdiri ?? '-' }}</span>
                            </div>
                            <!-- Row 5 -->
                            <div class="flex flex-col sm:flex-row sm:justify-between py-4 gap-1 sm:gap-0">
                                <span
                                    class="text-text-secondary-light dark:text-text-secondary-dark text-sm sm:text-base whitespace-nowrap">Alamat
                                    Lengkap</span>
                                <span
                                    class="text-text-primary-light dark:text-text-primary-dark text-left sm:text-right leading-relaxed sm:max-w-xs md:max-w-md mt-1 sm:mt-0">{{ $profile->alamat ?? 'Belum diisi' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sejarah Singkat -->
            <section id="sejarah" class="scroll-mt-48 flex flex-col gap-8">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-3xl">history_edu</span>
                    <h2 class="text-text-primary-light dark:text-text-primary-dark text-3xl font-bold">Sejarah Singkat
                    </h2>
                </div>

                @if($profile && $profile->sejarah_singkat)
                    <article class="news-content text-gray-300 text-lg leading-relaxed">
                        {!! $profile->sejarah_singkat !!}
                    </article>
                @else
                    <div class="text-text-secondary-light dark:text-text-secondary-dark italic">
                        Sejarah madrasah belum diisi. Silakan lengkapi di admin panel.
                    </div>
                @endif
            </section>

            <!-- Visi -->
            <section id="visi-misi" class="scroll-mt-48 flex flex-col gap-8">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-3xl">visibility</span>
                    <h2 class="text-text-primary-light dark:text-text-primary-dark text-3xl font-bold">Visi</h2>
                </div>

                @if($profile && $profile->visi)
                    <article
                        class="visi-content text-xl md:text-2xl font-semibold leading-relaxed bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-3xl p-8">
                        {!! $profile->visi !!}
                    </article>
                @else
                    <div class="text-text-secondary-light dark:text-text-secondary-dark italic">
                        Visi belum diisi. Silakan lengkapi di admin panel.
                    </div>
                @endif
            </section>

            <!-- Misi -->
            <section id="misi" class="scroll-mt-48 flex flex-col gap-8">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-3xl">flight_takeoff</span>
                    <h2 class="text-text-primary-light dark:text-text-primary-dark text-3xl font-bold">Misi</h2>
                </div>

                @if($profile && $profile->misi)
                    <article class="misi-content text-gray-300 text-lg leading-relaxed bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-3xl p-8">
                        {!! $profile->misi !!}
                    </article>
                @else
                    <div class="text-text-secondary-light dark:text-text-secondary-dark italic">
                        Misi belum diisi. Silakan lengkapi di admin panel.
                    </div>
                @endif
            </section>

            <!-- Tujuan Pendidikan -->
            <section id="tujuan" class="scroll-mt-48 flex flex-col gap-8">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-3xl">target</span>
                    <h2 class="text-text-primary-light dark:text-text-primary-dark text-3xl font-bold">Tujuan Pendidikan
                    </h2>
                </div>

                @if($profile && $profile->tujuan_madrasah)
                    <article
                        class="news-content text-gray-300 text-lg leading-relaxed bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-3xl p-8">
                        {!! $profile->tujuan_madrasah !!}
                    </article>
                @else
                    <div class="text-text-secondary-light dark:text-text-secondary-dark italic">
                        Tujuan madrasah belum diisi. Silakan lengkapi di admin panel.
                    </div>
                @endif
            </section>

            <!-- Struktur Organisasi -->
            <section id="struktur" class="scroll-mt-48 flex flex-col gap-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex flex-col gap-2">
                        <h2 class="text-text-primary-light dark:text-text-primary-dark text-3xl font-bold">Struktur
                            Organisasi</h2>
                        <p class="text-text-secondary-light dark:text-text-secondary-dark">Bagan struktur organisasi
                            Madrasah Tahun Ajaran
                            {{ $tahunAjaran->nama ?? '-' }}.
                        </p>
                    </div>
                    <a href="{{ route('profil.struktur-organisasi.download') }}" title="Export Struktur Organisasi PDF"
                        class="inline-flex items-center justify-center p-2 bg-primary/10 border border-primary/30 text-primary rounded-lg hover:bg-primary/20 transition-colors shrink-0">
                        <span class="material-symbols-outlined text-base">print</span>
                    </a>
                </div>

                <div
                    class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-3xl p-8 flex justify-center overflow-x-auto w-full">
                    @if($strukturLevel0->count() > 0 || $strukturLevel1->count() > 0 || $strukturLevel2->count() > 0)
                        <div class="flex flex-col items-center gap-6 min-w-[600px]">

                            <!-- Level 0: Ketua Yayasan -->
                            @if($strukturLevel0->count() > 0)
                                <div class="flex flex-wrap justify-center gap-6">
                                    @foreach($strukturLevel0 as $item)
                                        <div class="flex flex-col items-center gap-2">
                                            <div
                                                class="w-14 h-14 min-w-14 min-h-14 max-w-14 max-h-14 shrink-0 rounded-full bg-amber-900/30 border-2 border-amber-500 flex items-center justify-center overflow-hidden">
                                                @if($item->photo_display)
                                                    <img src="{{ asset('storage/' . $item->photo_display) }}"
                                                        alt="{{ $item->nama_display }}" class="w-14 h-14 object-cover">
                                                @else
                                                    <span class="text-lg font-bold text-amber-400">{{ $item->initials }}</span>
                                                @endif
                                            </div>
                                            <div class="text-center">
                                                <h4 class="text-text-primary-light dark:text-text-primary-dark font-bold text-sm">
                                                    {{ $item->nama_display }}</h4>
                                                <span
                                                    class="text-[10px] px-2 py-1 rounded-full {{ $item->badge_color }}">{{ $item->jabatan_display }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="w-px h-6 bg-gray-600"></div>
                            @endif

                            <!-- Level 1: Kepala Madrasah -->
                            @if($strukturLevel1->count() > 0)
                                <div class="flex flex-wrap justify-center gap-6">
                                    @foreach($strukturLevel1 as $item)
                                        <div class="flex flex-col items-center gap-2">
                                            <div
                                                class="w-14 h-14 min-w-14 min-h-14 max-w-14 max-h-14 shrink-0 rounded-full bg-border-light dark:bg-border-dark border-2 border-primary flex items-center justify-center overflow-hidden">
                                                @if($item->photo_display)
                                                    <img src="{{ asset('storage/' . $item->photo_display) }}"
                                                        alt="{{ $item->nama_display }}" class="w-14 h-14 object-cover">
                                                @else
                                                    <span class="text-lg font-bold text-primary">{{ $item->initials }}</span>
                                                @endif
                                            </div>
                                            <div class="text-center">
                                                <h4 class="text-text-primary-light dark:text-text-primary-dark font-bold text-sm">
                                                    {{ $item->nama_display }}</h4>
                                                <span
                                                    class="text-[10px] px-2 py-1 rounded-full {{ $item->badge_color }}">{{ $item->jabatan_display }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Connecting Lines for Level 2 -->
                            @if($strukturLevel2->count() > 0)
                                <div
                                    class="w-full h-6 border-t-2 border-r-2 border-l-2 border-gray-600 rounded-t-xl relative top-[-10px]">
                                </div>

                                <!-- Level 2: Operator & Ketua Komite -->
                                <div class="flex flex-wrap justify-center gap-4 w-full">
                                    @foreach($strukturLevel2 as $item)
                                        <div class="flex flex-col items-center gap-2">
                                            <div
                                                class="w-14 h-14 min-w-14 min-h-14 max-w-14 max-h-14 shrink-0 rounded-full bg-border-light dark:bg-border-dark border border-gray-600 flex items-center justify-center overflow-hidden">
                                                @if($item->photo_display)
                                                    <img src="{{ asset('storage/' . $item->photo_display) }}"
                                                        alt="{{ $item->nama_display }}" class="w-14 h-14 object-cover">
                                                @else
                                                    <span class="text-sm font-bold text-white/70">{{ $item->initials }}</span>
                                                @endif
                                            </div>
                                            <div class="text-center">
                                                <h4 class="text-text-primary-light dark:text-text-primary-dark font-bold text-sm">
                                                    {{ $item->nama_display }}</h4>
                                                <span
                                                    class="text-[10px] px-2 py-1 rounded-full {{ $item->badge_color }}">{{ $item->jabatan_display }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Level 3: Wakamad & Tata Usaha -->
                            @if($strukturLevel3->count() > 0)
                                <div class="border-t border-gray-700 pt-4 w-full">
                                    <p class="text-center text-gray-500 text-xs mb-3">Wakamad & Tata Usaha</p>
                                    <div class="flex flex-wrap justify-center gap-4">
                                        @foreach($strukturLevel3 as $item)
                                            <div class="flex flex-col items-center gap-1">
                                                <div
                                                    class="w-14 h-14 min-w-14 min-h-14 max-w-14 max-h-14 shrink-0 rounded-full bg-border-light dark:bg-border-dark border border-gray-700 flex items-center justify-center overflow-hidden">
                                                    @if($item->photo_display)
                                                        <img src="{{ asset('storage/' . $item->photo_display) }}"
                                                            alt="{{ $item->nama_display }}" class="w-14 h-14 object-cover">
                                                    @else
                                                        <span class="text-sm font-bold text-white/60">{{ $item->initials }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-center">
                                                    <h4
                                                        class="text-text-primary-light dark:text-text-primary-dark font-bold text-sm">
                                                        {{ $item->nama_display }}</h4>
                                                    <span
                                                        class="text-[10px] px-2 py-0.5 rounded-full {{ $item->badge_color }}">{{ $item->jabatan_display }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Level 4: Wali Kelas Bawah (1,2,3) -->
                            @if($strukturLevel4->count() > 0)
                                <div class="border-t border-gray-700 pt-4 w-full">
                                    <p class="text-center text-gray-500 text-xs mb-3">Wali Kelas Bawah (1,2,3)</p>
                                    <div class="flex flex-wrap justify-center gap-3">
                                        @foreach($strukturLevel4 as $item)
                                            <div class="flex flex-col items-center gap-1">
                                                <div
                                                    class="w-14 h-14 min-w-14 min-h-14 max-w-14 max-h-14 shrink-0 rounded-full bg-border-light dark:bg-border-dark border border-gray-700 flex items-center justify-center overflow-hidden">
                                                    @if($item->photo_display)
                                                        <img src="{{ asset('storage/' . $item->photo_display) }}"
                                                            alt="{{ $item->nama_display }}" class="w-14 h-14 object-cover">
                                                    @else
                                                        <span class="text-sm font-bold text-white/50">{{ $item->initials }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-center">
                                                    <h4
                                                        class="text-text-primary-light dark:text-text-primary-dark font-bold text-sm">
                                                        {{ $item->nama_display }}</h4>
                                                    <span
                                                        class="text-[10px] px-2 py-0.5 rounded-full {{ $item->badge_color }}">{{ $item->jabatan_display }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Level 5: Wali Kelas Atas (4,5,6) -->
                            @if($strukturLevel5->count() > 0)
                                <div class="border-t border-gray-700 pt-4 w-full">
                                    <p class="text-center text-gray-500 text-xs mb-3">Wali Kelas Atas (4,5,6)</p>
                                    <div class="flex flex-wrap justify-center gap-3">
                                        @foreach($strukturLevel5 as $item)
                                            <div class="flex flex-col items-center gap-1">
                                                <div
                                                    class="w-14 h-14 min-w-14 min-h-14 max-w-14 max-h-14 shrink-0 rounded-full bg-border-light dark:bg-border-dark border border-gray-700 flex items-center justify-center overflow-hidden">
                                                    @if($item->photo_display)
                                                        <img src="{{ asset('storage/' . $item->photo_display) }}"
                                                            alt="{{ $item->nama_display }}" class="w-14 h-14 object-cover">
                                                    @else
                                                        <span class="text-sm font-bold text-white/50">{{ $item->initials }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-center">
                                                    <h4
                                                        class="text-text-primary-light dark:text-text-primary-dark font-bold text-sm">
                                                        {{ $item->nama_display }}</h4>
                                                    <span
                                                        class="text-[10px] px-2 py-0.5 rounded-full {{ $item->badge_color }}">{{ $item->jabatan_display }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Level 6: Bagian Umum -->
                            @if($strukturLevel6->count() > 0)
                                <div class="border-t border-gray-700 pt-4 w-full">
                                    <p class="text-center text-gray-500 text-xs mb-3">Bagian Umum</p>
                                    <div class="flex flex-wrap justify-center gap-3">
                                        @foreach($strukturLevel6 as $item)
                                            <div class="flex flex-col items-center gap-1">
                                                <div
                                                    class="w-14 h-14 min-w-14 min-h-14 max-w-14 max-h-14 shrink-0 rounded-full bg-border-light dark:bg-border-dark border border-gray-800 flex items-center justify-center overflow-hidden">
                                                    @if($item->photo_display)
                                                        <img src="{{ asset('storage/' . $item->photo_display) }}"
                                                            alt="{{ $item->nama_display }}" class="w-14 h-14 object-cover">
                                                    @else
                                                        <span class="text-sm font-bold text-white/40">{{ $item->initials }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-center">
                                                    <h4
                                                        class="text-text-primary-light dark:text-text-primary-dark font-bold text-sm">
                                                        {{ $item->nama_display }}</h4>
                                                    <span
                                                        class="text-[10px] px-2 py-0.5 rounded-full {{ $item->badge_color }}">{{ $item->jabatan_display }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="py-12 flex flex-col items-center justify-center text-center gap-4">
                            <div
                                class="size-16 rounded-full bg-white/5 border border-border-dark flex items-center justify-center">
                                <span class="material-symbols-outlined text-3xl text-gray-500">account_tree</span>
                            </div>
                            <div>
                                <p class="text-text-secondary-light dark:text-text-secondary-dark">Struktur organisasi belum
                                    diisi.</p>
                                <p class="text-gray-500 text-sm">Silakan tambahkan data di admin panel.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </section>

        </div>
    </div>
</div>