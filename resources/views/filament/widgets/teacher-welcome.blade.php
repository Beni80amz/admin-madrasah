<x-filament-widgets::widget>
    @php
        /**
         * @var \App\Models\User $user
         */
        $user = auth()->user();
    @endphp
    <div class="fi-section p-6 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 shadow-sm text-white">
        <h2 class="text-2xl font-bold">
            Selamat {{ match (true) {
    now()->hour < 11 => 'Pagi',
    now()->hour < 15 => 'Siang',
    now()->hour < 18 => 'Sore',
    default => 'Malam',
} }}, {{ $user->name }}! 👋
        </h2>
        <p class="mt-2 text-emerald-50 text-sm">
            Semoga hari Anda menyenangkan dan penuh berkah mengajar di Madrasah.
        </p>
        <div class="mt-4 text-xs font-mono bg-white/20 inline-block px-3 py-1 rounded-lg">
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>
</x-filament-widgets::widget>