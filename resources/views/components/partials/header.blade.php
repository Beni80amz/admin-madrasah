<header x-data="{ 
        mobileMenuOpen: false, 
        scrolled: false,
        searchOpen: false
    }" @scroll.window="scrolled = (window.pageYOffset > 20)"
    :class="{ 'bg-surface-light/90 dark:bg-surface-dark/80 backdrop-blur-md border-b border-border-light dark:border-border-dark py-4': scrolled, 'bg-transparent py-6': !scrolled }"
    class="sticky top-0 z-50 w-full transition-all duration-300">

    <div class="layout-container flex justify-center w-full">
        <div class="px-5 md:px-10 lg:px-40 flex w-full max-w-[1440px] items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" wire:navigate
                class="flex items-center gap-3 text-gray-800 dark:text-white group">
                <div
                    class="flex items-center justify-center size-10 rounded-full bg-border-light dark:bg-surface-dark text-primary group-hover:bg-primary group-hover:text-white transition-colors overflow-hidden">
                    @if($siteProfile && $siteProfile->logo)
                        <img src="{{ Storage::url($siteProfile->logo) }}" alt="Logo" class="w-full h-full object-cover">
                    @else
                        <span class="material-symbols-outlined text-[24px]">school</span>
                    @endif
                </div>
                <h2 class="text-gray-800 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">
                    {{ $siteProfile->nama_madrasah ?? 'Madrasah Prototype' }}
                </h2>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-8">
                <a class="relative text-sm font-medium transition-colors group py-2 {{ request()->routeIs('home') ? 'text-primary dark:text-primary-light font-bold' : 'text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white' }}"
                    href="{{ route('home') }}" wire:navigate>
                    Beranda
                    <span
                        class="absolute bottom-0 left-0 h-0.5 bg-primary transition-all duration-300 {{ request()->routeIs('home') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>

                <!-- Dropdown: Profil -->
                <a class="relative text-sm font-medium transition-colors group py-2 {{ request()->routeIs('profile') ? 'text-primary dark:text-primary-light font-bold' : 'text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white' }}"
                    href="{{ route('profile') }}" wire:navigate>
                    Profil
                    <span
                        class="absolute bottom-0 left-0 h-0.5 bg-primary transition-all duration-300 {{ request()->routeIs('profile') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>

                <!-- PPDB Menu (Conditional) -->
                @if(\App\Models\AppSetting::isPpdbActive())
                    <a class="relative text-sm font-medium transition-colors group py-2 {{ request()->routeIs('ppdb*') ? 'text-primary dark:text-primary-light font-bold' : 'text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white' }}"
                        href="{{ route('ppdb') }}" wire:navigate>
                        PPDB
                        <span
                            class="absolute bottom-0 left-0 h-0.5 bg-primary transition-all duration-300 {{ request()->routeIs('ppdb.*') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                    </a>
                @endif

                <!-- Dropdown: Akademik -->
                <div class="relative group" x-data="{ open: false }" @mouseenter="open = true"
                    @mouseleave="open = false">
                    <button
                        class="flex items-center gap-1 text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white text-sm font-medium transition-colors py-2 focus:outline-none">
                        Akademik
                        <span class="material-symbols-outlined text-[18px] transition-transform duration-300"
                            :class="{ 'rotate-180': open }">expand_more</span>
                    </button>
                    <span
                        class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>

                    <div x-show="open" x-cloak style="display: none;"
                        x-transition:enter="transition-opacity ease-in-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity ease-in-out duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="absolute top-full left-0 mt-2 min-w-[200px] w-max bg-surface-light dark:bg-surface-dark/95 backdrop-blur-md border border-border-light dark:border-border-dark rounded-xl shadow-xl overflow-hidden py-2 z-50 ring-1 ring-black/5 dark:ring-white/5">
                        <a href="{{ route('akademik.data-siswa') }}" wire:navigate
                            class="block px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-primary transition-colors whitespace-nowrap">Data
                            Siswa</a>
                        <a href="{{ route('akademik.data-guru') }}" wire:navigate
                            class="block px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-primary transition-colors whitespace-nowrap">Data
                            Guru dan Staff</a>
                        <a href="{{ route('akademik.kurikulum') }}" wire:navigate
                            class="block px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-primary transition-colors whitespace-nowrap">Kurikulum</a>
                        <a href="{{ route('akademik.kalender') }}" wire:navigate
                            class="block px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-primary transition-colors whitespace-nowrap">Kalender
                            Akademik</a>
                        <a href="{{ route('akademik.prestasi-siswa') }}" wire:navigate
                            class="block px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-primary transition-colors whitespace-nowrap">Prestasi
                            Siswa</a>
                        <a href="{{ route('akademik.prestasi-guru') }}" wire:navigate
                            class="block px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-primary transition-colors whitespace-nowrap">Prestasi
                            Guru</a>
                        <a href="{{ route('akademik.data-alumni') }}" wire:navigate
                            class="block px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-primary transition-colors whitespace-nowrap">Data
                            Alumni</a>
                    </div>
                </div>

                <a class="relative text-sm font-medium transition-colors group py-2 {{ request()->routeIs('news*') ? 'text-primary dark:text-primary-light font-bold' : 'text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white' }}"
                    href="{{ route('news') }}" wire:navigate>
                    Berita
                    <span
                        class="absolute bottom-0 left-0 h-0.5 bg-primary transition-all duration-300 {{ request()->routeIs('news*') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
                <a class="relative text-sm font-medium transition-colors group py-2 {{ request()->routeIs('gallery') ? 'text-primary dark:text-primary-light font-bold' : 'text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white' }}"
                    href="{{ route('gallery') }}" wire:navigate>
                    Galeri
                    <span
                        class="absolute bottom-0 left-0 h-0.5 bg-primary transition-all duration-300 {{ request()->routeIs('gallery') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
                <a class="relative text-sm font-medium transition-colors group py-2 {{ request()->routeIs('contact') ? 'text-primary dark:text-primary-light font-bold' : 'text-gray-700 dark:text-white/80 hover:text-gray-900 dark:hover:text-white' }}"
                    href="{{ route('contact') }}" wire:navigate>
                    Kontak
                    <span
                        class="absolute bottom-0 left-0 h-0.5 bg-primary transition-all duration-300 {{ request()->routeIs('contact') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                @if(($themeMode ?? 'dark') === 'custom')
                    <!-- Theme Toggle Button -->
                    <button x-data="{ isDark: window.themeManager?.isDark ?? true }"
                        x-init="$el.addEventListener('click', () => { window.themeManager?.toggle(); isDark = window.themeManager?.isDark ?? !isDark; })"
                        @theme-changed.window="isDark = $event.detail.isDark"
                        class="flex items-center justify-center size-10 rounded-full bg-border-light dark:bg-surface-dark hover:bg-primary hover:text-white text-text-secondary-light dark:text-text-primary-dark transition-all duration-300"
                        title="Toggle tema">
                        <span x-show="isDark" x-cloak class="material-symbols-outlined text-xl">dark_mode</span>
                        <span x-show="!isDark" x-cloak class="material-symbols-outlined text-xl">light_mode</span>
                    </button>
                @endif

                <!-- Admin Button -->
                <a href="/admin/login"
                    class="hidden lg:flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-6 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary-dark transition-all shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:shadow-[0_0_30px_rgba(16,185,129,0.5)] transform hover:-translate-y-0.5">
                    <span class="truncate">Admin</span>
                </a>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden">
                    <button @click="mobileMenuOpen = true"
                        class="text-white hover:text-primary transition-colors focus:outline-none">
                        <span class="material-symbols-outlined text-3xl">menu</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer -->
    <div x-show="mobileMenuOpen" class="fixed inset-0 z-[60] lg:hidden" role="dialog" aria-modal="true"
        style="display: none;">
        <!-- Backdrop -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm"
            @click="mobileMenuOpen = false"></div>

        <!-- Drawer Content -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed inset-y-0 right-0 z-[70] w-full max-w-xs overflow-y-auto bg-surface-light dark:bg-background-dark border-l border-border-light dark:border-border-dark px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">

            <div class="flex items-center justify-between mb-8">
                <a href="#" class="-m-1.5 p-1.5 flex items-center gap-2">
                    <div
                        class="flex items-center justify-center size-8 rounded-full bg-border-light text-primary overflow-hidden">
                        @if($siteProfile && $siteProfile->logo)
                            <img src="{{ Storage::url($siteProfile->logo) }}" alt="Logo" class="w-full h-full object-cover">
                        @else
                            <span class="material-symbols-outlined text-[20px]">school</span>
                        @endif
                    </div>
                    <span
                        class="text-lg font-bold text-text-primary-light dark:text-white">{{ $siteProfile->nama_madrasah ?? 'Madrasah Prototype' }}</span>
                </a>
                <button @click="mobileMenuOpen = false" type="button"
                    class="-m-2.5 rounded-md p-2.5 text-gray-400 hover:text-white">
                    <span class="sr-only">Close menu</span>
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="flex flex-col gap-y-4">
                <a href="{{ route('home') }}" wire:navigate
                    class="text-base font-semibold leading-7 text-text-primary-light dark:text-white hover:text-primary transition-colors border-b border-border-light dark:border-white/5 pb-2">Beranda</a>

                <a href="{{ route('profile') }}" wire:navigate
                    class="text-base font-semibold leading-7 text-text-primary-light dark:text-white hover:text-primary transition-colors border-b border-border-light dark:border-white/5 pb-2">Profil</a>

                <!-- Mobile PPDB (Conditional) -->
                @if(\App\Models\AppSetting::isPpdbActive())
                    <a href="{{ route('ppdb') }}" wire:navigate
                        class="text-base font-bold leading-7 text-primary hover:text-primary-dark transition-colors border-b border-border-light dark:border-white/5 pb-2">PPDB</a>
                @endif

                <!-- Mobile Dropdown: Akademik -->
                <div x-data="{ subOpen: false }" class="border-b border-border-light dark:border-white/5 pb-2">
                    <button @click="subOpen = !subOpen"
                        class="flex w-full items-center justify-between text-base font-semibold leading-7 text-text-primary-light dark:text-white hover:text-primary transition-colors">
                        Akademik
                        <span class="material-symbols-outlined text-sm transition-transform duration-200"
                            :class="{ 'rotate-180': subOpen }">expand_more</span>
                    </button>
                    <div x-show="subOpen" class="mt-2 space-y-2 pl-4">
                        <a href="{{ route('akademik.data-siswa') }}" wire:navigate
                            class="block text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark hover:text-primary">Data
                            Siswa</a>
                        <a href="{{ route('akademik.data-guru') }}" wire:navigate
                            class="block text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark hover:text-primary">Data
                            Guru dan Staff</a>
                        <a href="{{ route('akademik.kurikulum') }}" wire:navigate
                            class="block text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark hover:text-primary">Kurikulum</a>
                        <a href="{{ route('akademik.kalender') }}" wire:navigate
                            class="block text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark hover:text-primary">Kalender
                            Akademik</a>
                        <a href="{{ route('akademik.prestasi-siswa') }}" wire:navigate
                            class="block text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark hover:text-primary">Prestasi
                            Siswa</a>
                        <a href="{{ route('akademik.prestasi-guru') }}" wire:navigate
                            class="block text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark hover:text-primary">Prestasi
                            Guru</a>
                        <a href="{{ route('akademik.data-alumni') }}" wire:navigate
                            class="block text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark hover:text-primary">Data
                            Alumni</a>
                    </div>
                </div>

                <a href="{{ route('news') }}" wire:navigate
                    class="text-base font-semibold leading-7 text-text-primary-light dark:text-white hover:text-primary transition-colors border-b border-border-light dark:border-white/5 pb-2">Berita</a>
                <a href="{{ route('gallery') }}" wire:navigate
                    class="text-base font-semibold leading-7 text-text-primary-light dark:text-white hover:text-primary transition-colors border-b border-border-light dark:border-white/5 pb-2">Galeri</a>
                <a href="{{ route('contact') }}" wire:navigate
                    class="text-base font-semibold leading-7 text-text-primary-light dark:text-white hover:text-primary transition-colors border-b border-border-light dark:border-white/5 pb-2">Kontak</a>
            </div>

            <div class="mt-8">
                <a href="/admin/login"
                    class="flex w-full items-center justify-center rounded-full bg-primary px-3 py-2.5 text-center text-sm font-bold text-white shadow-sm hover:bg-primary-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all">
                    Masuk sebagai Admin
                </a>
            </div>
        </div>
    </div>
</header>