<!DOCTYPE html>
@php
    $themeClass = match ($themeMode ?? 'dark') {
        'light' => '',
        'custom' => '', // Alpine.js will handle this based on user preference
        default => 'dark',
    };
@endphp
<html class="{{ $themeClass }} scroll-smooth" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        {{ isset($title) ? $title . ' | ' . ($siteProfile->nama_madrasah ?? 'Madrasah') : ($siteProfile->nama_madrasah ?? 'Madrasah') }}
    </title>

    {{-- Favicon --}}
    @if($siteProfile && $siteProfile->logo)
        <link rel="icon" type="image/png" href="{{ Storage::url($siteProfile->logo) }}">
    @else
        <link rel="icon" type="image/svg+xml"
            href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏫</text></svg>">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])



    @if(($themeMode ?? 'dark') === 'custom')
        <script>
            // Initialize theme before Alpine loads to prevent flash
            (function () {
                const stored = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const isDark = stored ? stored === 'dark' : prefersDark;

                if (isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            })();

            // Store global for Alpine to access
            window.themeManager = {
                isDark: localStorage.getItem('theme') === 'dark' ||
                    (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
                toggle() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');

                    if (this.isDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }

                    // Dispatch event for any Alpine components listening
                    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: this.isDark } }));
                }
            };
        </script>
    @endif
</head>

<body
    class="bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark font-display overflow-x-hidden selection:bg-primary selection:text-white transition-colors duration-300">

    {{-- Navigation Progress Bar --}}
    <div x-data="{ loading: false, progress: 0 }" x-init="
            document.addEventListener('livewire:navigate:start', () => { loading = true; progress = 0; });
            document.addEventListener('livewire:navigate:progress', (e) => { progress = e.detail.progress * 100; });
            document.addEventListener('livewire:navigate:end', () => { progress = 100; setTimeout(() => { loading = false; progress = 0; }, 200); });
        " x-show="loading" x-transition:enter="transition-opacity duration-150"
        x-transition:leave="transition-opacity duration-300"
        class="fixed top-0 left-0 right-0 z-[100] h-1 bg-primary/20">
        <div class="h-full bg-gradient-to-r from-primary via-primary-light to-primary transition-all duration-150 ease-out"
            :style="'width: ' + progress + '%'">
        </div>
    </div>

    {{-- Page Content with Fade Transition --}}
    <div x-data="{ visible: true }" x-init="
            document.addEventListener('livewire:navigate:start', () => { visible = false; });
            document.addEventListener('livewire:navigate:end', () => { setTimeout(() => visible = true, 100); });
        " :class="visible ? 'opacity-100' : 'opacity-0'" class="transition-opacity duration-500 ease-in-out">

        @include('components.partials.header')

        <main class="flex flex-col w-full">
            {{ $slot }}
        </main>

        @include('components.partials.footer')
    </div>

    <!-- Back to Top Button -->
    <button x-data="{ show: false }" @scroll.window="show = (window.pageYOffset > 300)"
        @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-show="show" x-cloak
        x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed bottom-8 right-8 z-50 flex h-12 w-12 items-center justify-center rounded-full bg-primary text-white shadow-lg hover:bg-primary-dark hover:scale-110 hover:shadow-xl transition-all focus:outline-none"
        aria-label="Back to top">
        <span class="material-symbols-outlined text-2xl font-bold">arrow_upward</span>
    </button>

</body>

</html>