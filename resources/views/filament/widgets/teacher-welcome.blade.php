<x-filament-widgets::widget>
    @php
        /**
         * @var \App\Models\User $user
         */
        $user = auth()->user();
    @endphp
    <div class="relative overflow-hidden rounded-2xl bg-gray-900 border border-gray-800 shadow-2xl p-8 isolate group">
        {{-- Glow Effect --}}
        <div
            class="absolute top-0 right-0 -mr-16 -mt-16 h-64 w-64 rounded-full bg-emerald-500/5 blur-3xl transition-all duration-500 group-hover:bg-emerald-500/10">
        </div>
        <div
            class="absolute bottom-0 left-0 -ml-16 -mb-16 h-48 w-48 rounded-full bg-blue-500/5 blur-3xl transition-all duration-500 group-hover:bg-blue-500/10">
        </div>

        <div class="relative flex flex-col items-center text-center z-10">
            {{-- Icon Container --}}
            <div class="mb-6 relative">
                <div class="absolute inset-0 rounded-full bg-emerald-500/20 blur-xl animate-pulse"></div>
                <div
                    class="relative flex h-24 w-24 items-center justify-center rounded-full bg-gray-800 border-2 border-gray-700/50 shadow-inner overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent"></div>
                    @if(now()->hour < 18 && now()->hour > 5)
                        <x-heroicon-m-sun class="h-12 w-12 text-yellow-400 drop-shadow-md"
                            style="width: 48px; height: 48px;" />
                    @else
                        <x-heroicon-m-moon class="h-12 w-12 text-blue-400 drop-shadow-md"
                            style="width: 48px; height: 48px;" />
                    @endif
                </div>
                {{-- Status Indicator Badge --}}
                <div class="absolute bottom-1 right-1 rounded-full bg-gray-900 p-1.5 ring-1 ring-black/20">
                    <div
                        class="h-4 w-4 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)] border-2 border-gray-900">
                    </div>
                </div>
            </div>

            <h2 class="text-3xl font-bold tracking-tight text-white mb-2 font-display">
                Selamat {{ match (true) {
    now()->hour < 11 => 'Pagi',
    now()->hour < 15 => 'Siang',
    now()->hour < 18 => 'Sore',
    default => 'Malam',
} }}, <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400">{{ explode(' ', $user->name)[0] }}</span>
            </h2>

            <p class="text-gray-400 max-w-lg mx-auto text-sm leading-relaxed mb-8 font-light tracking-wide">
                "Pendidikan adalah senjata paling mematikan di dunia, karena dengan pendidikan Anda dapat mengubah
                dunia. Semoga Hari ini Berkah untuk semuanya. Amiin"
            </p>

            <div
                class="inline-flex items-center gap-3 rounded-full bg-gray-800/80 px-5 py-2 text-sm font-medium text-gray-300 ring-1 ring-inset ring-white/10 backdrop-blur-sm">
                <x-heroicon-m-calendar class="h-4 w-4 text-emerald-500" style="width: 16px; height: 16px;" />
                <span class="tracking-wide">{{ now()->translatedFormat('l, d F Y') }}</span>
                <span class="mx-1 text-gray-600">|</span>
                <span class="font-mono text-emerald-400">{{ now()->format('H:i') }} WIB</span>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>