@php
    $isConnected = $status === 'connected';
    $primaryColor = $isConnected ? 'emerald' : 'amber';
    $icon = $isConnected ? 'heroicon-o-check-badge' : 'heroicon-o-exclamation-circle';
    $title = $isConnected ? 'Google Drive Terhubung' : 'Google Drive Terputus';
@endphp

<div
    class="relative overflow-hidden rounded-3xl border border-{{ $primaryColor }}-500/20 bg-white p-8 shadow-2xl transition-all duration-500 hover:shadow-{{ $primaryColor }}-500/10 dark:bg-gray-900/50 dark:backdrop-blur-xl">
    <!-- Background Glow Effect -->
    <div
        class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-{{ $primaryColor }}-500/10 blur-[100px] transition-all duration-700 group-hover:bg-{{ $primaryColor }}-500/20">
    </div>
    <div class="absolute -bottom-20 -left-20 h-48 w-48 rounded-full bg-{{ $primaryColor }}-500/5 blur-[80px]"></div>

    <div class="relative flex flex-col items-center text-center">
        <!-- Animated Icon Container -->
        <div class="relative">
            <div
                class="absolute inset-0 animate-ping rounded-full bg-{{ $primaryColor }}-500/20 opacity-75 duration-[3000ms]">
            </div>
            <div
                class="relative flex h-24 w-24 items-center justify-center rounded-2xl bg-{{ $primaryColor }}-500/10 transition-transform duration-500 hover:scale-105">
                <x-dynamic-component :component="$icon" style="width: 48px; height: 48px;"
                    class="text-{{ $primaryColor }}-600 dark:text-{{ $primaryColor }}-400" />
            </div>
        </div>

        <div class="mt-8">
            <h3
                class="bg-gradient-to-r from-gray-950 to-gray-600 bg-clip-text text-2xl font-black tracking-tight text-transparent dark:from-white dark:to-gray-400">
                {{ $title }}
            </h3>

            <p class="mx-auto mt-4 max-w-sm text-sm leading-relaxed text-gray-500 dark:text-gray-400">
                @if($isConnected)
                    Sistem siap mengamankan berkas administrasi Anda secara otomatis di penyimpanan awan Google.
                @else
                    Hubungkan akun Google Drive Anda untuk mulai menggunakan fitur pencadangan berkas otomatis.
                @endif
            </p>
        </div>

        @if($isConnected)
            <div class="mt-8 flex items-center gap-x-2 rounded-full bg-emerald-500/10 px-4 py-1.5 dark:bg-emerald-500/20">
                <span class="relative flex h-2 w-2">
                    <span
                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                </span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-700 dark:text-emerald-300">Active
                    Sync</span>
            </div>
        @endif
    </div>
</div>