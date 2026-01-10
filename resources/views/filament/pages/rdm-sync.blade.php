<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Info Section --}}
        <div class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Sinkronisasi Data dengan RDM
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Gunakan tombol di atas untuk men-sinkronkan data Guru dan Siswa dari database RDM.
            </p>

            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-primary-500"></span>
                    <span class="text-gray-700 dark:text-gray-300"><strong>Sync Semua Data</strong>: Men-sync data Guru
                        dan Siswa sekaligus</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-info-500"></span>
                    <span class="text-gray-700 dark:text-gray-300"><strong>Sync Guru Saja</strong>: Hanya men-sync data
                        Guru dari RDM</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-success-500"></span>
                    <span class="text-gray-700 dark:text-gray-300"><strong>Sync Siswa Saja</strong>: Hanya men-sync data
                        Siswa dari RDM</span>
                </div>
            </div>

            <div
                class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-700">
                <p class="text-amber-700 dark:text-amber-300 text-sm">
                    <strong>Catatan:</strong> Data dari RDM akan menjadi master. Data yang sudah ada di Admin Madrasah
                    dengan NIP/NIS yang sama akan diperbarui.
                </p>
            </div>
        </div>

        {{-- Connection Status --}}
        <div class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Status Koneksi Database RDM
            </h3>

            @php
                $connectionOk = false;
                $connectionError = null;
                try {
                    \Illuminate\Support\Facades\DB::connection('rdm')->getPdo();
                    $connectionOk = true;
                } catch (\Exception $e) {
                    $connectionError = $e->getMessage();
                }
            @endphp

            @if($connectionOk)
                <div
                    class="flex items-center gap-3 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-700">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-green-700 dark:text-green-300 font-medium">Koneksi ke database RDM berhasil!</p>
                        <p class="text-green-600 dark:text-green-400 text-sm">Anda dapat menjalankan sinkronisasi data.</p>
                    </div>
                </div>
            @else
                <div
                    class="flex items-start gap-3 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-700">
                    <div class="flex-shrink-0 mt-0.5">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-red-700 dark:text-red-300 font-medium">Gagal koneksi ke database RDM</p>
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $connectionError ?? 'Unknown error' }}</p>
                        <p class="text-red-500 dark:text-red-500 text-xs mt-2">Pastikan kredensial RDM sudah dikonfigurasi
                            di file .env server.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>