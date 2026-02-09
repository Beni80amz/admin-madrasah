<div class="mb-6">
    <x-filament::section icon="heroicon-o-information-circle" collapsible
        class="overflow-hidden border-none shadow-lg ring-1 ring-emerald-500/30 dark:ring-emerald-400/20 bg-white dark:bg-gray-900">
        <x-slot name="heading">
            <span class="text-xl font-extrabold tracking-tight text-emerald-800 dark:text-emerald-300">
                Petunjuk & Status Dokumen
            </span>
        </x-slot>

        {{-- Vibrant Background Accent --}}
        <div
            class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 dark:bg-emerald-400/5 blur-3xl -z-10 rounded-full -mr-32 -mt-32 pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 left-0 w-64 h-64 bg-amber-500/10 dark:bg-amber-400/5 blur-3xl -z-10 rounded-full -ml-32 -mb-32 pointer-events-none">
        </div>

        <div class="relative grid grid-cols-1 md:grid-cols-2 gap-8 text-sm py-4">
            {{-- Panduan Pengisian Section --}}
            <div
                class="group relative p-6 rounded-2xl bg-gradient-to-br from-emerald-50 to-white dark:from-emerald-950/40 dark:to-gray-900/40 border border-emerald-200/60 dark:border-emerald-700/30 shadow-sm transition-all duration-300 hover:shadow-md hover:scale-[1.01]">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="p-2 rounded-lg bg-emerald-500/10 dark:bg-emerald-400/20 text-emerald-600 dark:text-emerald-400 shadow-inner">
                        <x-heroicon-s-academic-cap class="w-6 h-6" />
                    </div>
                    <span
                        class="text-xs font-black uppercase tracking-widest text-emerald-700 dark:text-emerald-400">Panduan
                        Pengisian:</span>
                </div>

                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div
                            class="mt-1 flex-shrink-0 w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-700">
                            <span class="text-[10px] font-bold">1</span>
                        </div>
                        <p class="text-gray-700 dark:text-emerald-100/90 leading-relaxed">
                            <strong class="text-emerald-950 dark:text-emerald-50 font-bold">Disiplin Waktu:</strong>
                            Segera isi jurnal setelah KBM selesai agar data tetap akurat.
                        </p>
                    </li>
                    <li class="flex items-start gap-3">
                        <div
                            class="mt-1 flex-shrink-0 w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-700">
                            <span class="text-[10px] font-bold">2</span>
                        </div>
                        <p class="text-gray-700 dark:text-emerald-100/90 leading-relaxed">
                            <strong class="text-emerald-950 dark:text-emerald-50 font-bold">Presensi Siswa:</strong>
                            Pastikan total angka S/I/A sesuai dengan daftar nama.
                        </p>
                    </li>
                    <li class="flex items-start gap-3">
                        <div
                            class="mt-1 flex-shrink-0 w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-700">
                            <span class="text-[10px] font-bold">3</span>
                        </div>
                        <p class="text-gray-700 dark:text-emerald-100/90 leading-relaxed">
                            <strong class="text-emerald-950 dark:text-emerald-50 font-bold">Refleksi:</strong> Isian ini
                            krusial untuk supervisi akademik sekolah.
                        </p>
                    </li>
                </ul>
            </div>

            {{-- Integritas Data Section --}}
            <div
                class="group relative p-6 rounded-2xl bg-gradient-to-br from-amber-50 to-white dark:from-amber-950/20 dark:to-gray-900/40 border border-amber-200/60 dark:border-amber-700/20 shadow-sm transition-all duration-300 hover:shadow-md hover:scale-[1.01]">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="p-2 rounded-lg bg-amber-500/10 dark:bg-amber-400/20 text-amber-600 dark:text-amber-500 shadow-inner">
                        <x-heroicon-s-shield-check class="w-6 h-6" />
                    </div>
                    <span
                        class="text-xs font-black uppercase tracking-widest text-amber-700 dark:text-amber-500">Integritas
                        Data:</span>
                </div>

                <div
                    class="relative p-5 bg-white/70 dark:bg-black/20 rounded-xl !border-l-4 border-amber-500 shadow-inner backdrop-blur-sm">
                    <svg class="absolute top-2 right-2 w-8 h-8 text-amber-200/30 dark:text-amber-500/10"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H16.017C14.9124 8 14.017 7.10457 14.017 6V3L21.017 3V21H14.017ZM3.01697 21L3.01697 18C3.01697 16.8954 3.9124 16 5.01697 16H8.01697C8.56925 16 9.01697 15.5523 9.01697 15V9C9.01697 8.44772 8.56925 8 8.01697 8H5.01697C3.9124 8 3.01697 7.10457 3.01697 6V3L10.017 3V21H3.01697Z" />
                    </svg>
                    <p class="italic text-amber-900 dark:text-amber-100 font-medium leading-relaxed">
                        "Dokumen ini merupakan rekam jejak digital resmi aktivitas pembelajaran Anda. Data yang diinput
                        menjadi dasar evaluasi kinerja."
                    </p>
                </div>

                <div class="mt-4 flex items-center gap-2 px-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></div>
                    <p class="text-[11px] font-bold text-amber-800/80 dark:text-amber-400/80 uppercase">
                        Validasi data sebelum simpan
                    </p>
                </div>
            </div>
        </div>
    </x-filament::section>

    <style>
        @keyframes subtleFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .relative.grid>div {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .relative.grid>div:nth-child(2) {
            animation-delay: 0.1s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom scrollbar for dark mode consistency */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(16, 185, 129, 0.2);
            border-radius: 10px;
        }
    </style>
</div>