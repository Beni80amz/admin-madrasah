<x-filament-widgets::widget>
    @php
        /**
         * @var \App\Models\User $user
         */
        $user = auth()->user();
    @endphp
    <div
        class="relative overflow-hidden rounded-xl bg-gradient-to-br from-emerald-600 to-teal-700 p-8 text-white shadow-lg">
        {{-- Decorative Background Pattern --}}
        <div class="absolute right-0 top-0 -mt-4 -mr-4 h-48 w-48 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-4 -ml-4 h-32 w-32 rounded-full bg-emerald-500/20 blur-2xl"></div>

        <div class="relative flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="rounded-full bg-white/20 p-1.5 backdrop-blur-sm">
                        @if(now()->hour < 18 && now()->hour > 5)
                            <x-heroicon-m-sun class="h-6 w-6 text-yellow-300" />
                        @else
                            <x-heroicon-m-moon class="h-6 w-6 text-blue-200" />
                        @endif
                    </div>
                    <span class="font-medium text-emerald-100 tracking-wide text-sm uppercase">Dashboard Guru</span>
                </div>

                <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                    Selamat {{ match (true) {
    now()->hour < 11 => 'Pagi',
    now()->hour < 15 => 'Siang',
    now()->hour < 18 => 'Sore',
    default => 'Malam',
} }}, <br>
                    <span class="text-emerald-100">{{ $user->name }}</span>
                </h2>

                <p class="mt-4 max-w-2xl text-lg text-emerald-50/90 font-light">
                    "Semoga hari Anda menyenangkan dan penuh berkah dalam mendidik generasi penerus bangsa."
                </p>

                <div class="mt-6 flex items-center gap-4">
                    <div
                        class="flex items-center gap-2 rounded-lg bg-white/10 px-4 py-2 backdrop-blur-md border border-white/10">
                        <x-heroicon-m-calendar class="h-5 w-5 text-emerald-200" />
                        <span class="font-medium">{{ now()->translatedFormat('l, d F Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Illustration/Icon Side --}}
            <div class="hidden md:block opacity-90">
                <x-heroicon-o-academic-cap class="h-40 w-40 text-white/10 rotate-12 transform" />
            </div>
        </div>
    </div>
</x-filament-widgets::widget>