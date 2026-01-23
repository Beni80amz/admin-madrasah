<div class="min-h-screen pt-24 pb-16 px-5 md:px-10 lg:px-20 xl:px-40 w-full flex items-center justify-center">
    <div class="max-w-lg w-full">
        {{-- Verification Card --}}
        <div
            class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-3xl p-8 shadow-xl">

            {{-- Header with Check Icon --}}
            <div class="flex flex-col items-center gap-4 mb-8">
                <div
                    class="size-16 rounded-full bg-primary/10 border-2 border-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-4xl text-primary">verified</span>
                </div>
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-text-primary-light dark:text-text-primary-dark">Verifikasi
                        Dokumen</h1>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm mt-1">Dokumen resmi
                        terverifikasi</p>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-border-light dark:border-border-dark mb-8"></div>

            @if($profile)
                {{-- Logo --}}
                @if($profile->logo)
                    <div class="flex justify-center mb-6">
                        <div class="size-24 rounded-2xl bg-white flex items-center justify-center shadow-lg overflow-hidden">
                            <img src="{{ Storage::url($profile->logo) }}" alt="Logo Madrasah"
                                class="w-full h-full object-contain p-2">
                        </div>
                    </div>
                @endif

                {{-- Nama Madrasah --}}
                <div class="text-center mb-8">
                    <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark mb-1">Nama Madrasah</p>
                    <h2 class="text-xl font-bold text-primary">{{ $profile->nama_madrasah ?? '-' }}</h2>
                </div>

                {{-- Tanda Tangan Kepala Madrasah --}}
                <div class="flex flex-col items-center mb-6">
                    <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark mb-3">Tanda Tangan Kepala
                        Madrasah</p>
                    <div class="relative inline-block">
                        @if($profile->stempel_madrasah)
                            <img src="{{ Storage::url($profile->stempel_madrasah) }}"
                                class="absolute -left-12 top-1/2 -translate-y-1/2 w-24 h-24 opacity-80 object-contain transform -rotate-12 pointer-events-none z-10"
                                alt="Stempel Madrasah">
                        @endif

                        @if($profile->tanda_tangan_kepala_madrasah)
                            <div
                                class="bg-white rounded-xl p-4 border border-border-light dark:border-border-dark shadow-inner relative z-0">
                                <img src="{{ Storage::url($profile->tanda_tangan_kepala_madrasah) }}"
                                    alt="Tanda Tangan Kepala Madrasah" class="max-h-24 w-auto">
                            </div>
                        @else
                            <div
                                class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 border border-border-light dark:border-border-dark relative z-0">
                                <p class="text-text-secondary-light dark:text-text-secondary-dark italic text-sm">Tanda tangan
                                    belum
                                    tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Nama Kepala Madrasah --}}
                <div class="text-center">
                    <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark mb-1">Kepala Madrasah</p>
                    <h3 class="text-lg font-bold text-text-primary-light dark:text-text-primary-dark">
                        {{ $profile->nama_kepala_madrasah ?? '-' }}
                    </h3>
                </div>

                {{-- Divider --}}
                <div class="border-t border-border-light dark:border-border-dark my-8"></div>

                {{-- Footer Info --}}
                <div class="text-center">
                    <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">
                        Dokumen ini diverifikasi secara digital
                    </p>
                    <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark mt-1">
                        {{ now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB
                    </p>
                </div>
            @else
                {{-- No Profile Data --}}
                <div class="text-center py-8">
                    <span class="material-symbols-outlined text-5xl text-gray-400 mb-4">error_outline</span>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark">Data profil madrasah tidak ditemukan
                    </p>
                </div>
            @endif
        </div>

        {{-- Back to Home Link --}}
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-primary hover:underline text-sm" wire:navigate>
                ← Kembali ke Beranda
            </a>
        </div>
    </div>
</div>