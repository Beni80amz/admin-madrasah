<div class="mb-6">
    <x-filament::section icon="heroicon-o-information-circle" collapsible
        class="overflow-hidden border-none shadow-sm ring-1 ring-emerald-100 dark:ring-emerald-900/50">
        <x-slot name="heading">
            <span class="text-lg font-bold text-emerald-950 dark:text-emerald-50">Petunjuk & Status Dokumen</span>
        </x-slot>

        {{-- Premium Background Layer --}}
        <div
            class="absolute inset-0 bg-gradient-to-br from-emerald-50/80 via-transparent to-transparent dark:from-emerald-950/30 -z-10 pointer-events-none">
        </div>

        <div class="relative grid grid-cols-1 md:grid-cols-2 gap-6 text-sm py-2 px-1">
            <div
                class="space-y-3 bg-white/40 dark:bg-gray-900/40 p-4 rounded-xl border border-emerald-100/50 dark:border-emerald-800/30 backdrop-blur-sm shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center gap-2 text-emerald-700 dark:text-emerald-400 font-bold tracking-tight">
                    <x-heroicon-m-academic-cap class="w-5 h-5" />
                    <span class="uppercase text-xs">Panduan Pengisian:</span>
                </div>
                <ul class="space-y-3">
                    <li class="flex gap-3">
                        <span
                            class="flex-shrink-0 w-1.5 h-1.5 mt-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                        <p class="text-gray-600 dark:text-emerald-200/70 leading-snug">
                            <strong class="text-emerald-900 dark:text-emerald-100 font-semibold">Disiplin
                                Waktu:</strong> Segera isi jurnal setelah KBM selesai agar data tetap akurat.
                        </p>
                    </li>
                    <li class="flex gap-3">
                        <span
                            class="flex-shrink-0 w-1.5 h-1.5 mt-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                        <p class="text-gray-600 dark:text-emerald-200/70 leading-snug">
                            <strong class="text-emerald-900 dark:text-emerald-100 font-semibold">Presensi
                                Siswa:</strong> Pastikan total angka S/I/A sesuai dengan daftar nama yang dipilih.
                        </p>
                    </li>
                    <li class="flex gap-3">
                        <span
                            class="flex-shrink-0 w-1.5 h-1.5 mt-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                        <p class="text-gray-600 dark:text-emerald-200/70 leading-snug">
                            <strong class="text-emerald-900 dark:text-emerald-100 font-semibold">Refleksi:</strong>
                            Sangat penting untuk supervisi akademik dan akreditasi sekolah.
                        </p>
                    </li>
                </ul>
            </div>

            <div
                class="space-y-3 bg-amber-50/30 dark:bg-amber-950/10 p-4 rounded-xl border border-amber-100/50 dark:border-amber-900/20 backdrop-blur-sm shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center gap-2 text-amber-700 dark:text-amber-500 font-bold tracking-tight">
                    <x-heroicon-m-shield-check class="w-5 h-5" />
                    <span class="uppercase text-xs">Integritas Data:</span>
                </div>
                <div
                    class="p-3 bg-white/50 dark:bg-black/20 rounded-lg italic text-amber-900/80 dark:text-amber-200/70 border-l-2 border-amber-400 leading-relaxed shadow-inner">
                    "Dokumen ini merupakan rekam jejak digital resmi aktivitas pembelajaran Anda. Seluruh informasi
                    digunakan untuk laporan bulanan dan evaluasi kinerja."
                </div>
                <p class="text-xs text-amber-800/60 dark:text-amber-400/50 px-1 italic">
                    * Pastikan data valid sebelum menekan tombol simpan.
                </p>
            </div>
        </div>
    </x-filament::section>

    <style>
        /* Fade-in animation for the premium card components */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .relative.grid>div {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .relative.grid>div:nth-child(2) {
            animation-delay: 0.15s;
        }
    </style>
</div>