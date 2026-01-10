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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="font-medium text-xs text-primary-600 dark:text-primary-400 mb-1">Sync Semua Data</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Sinkronisasi data Guru dan Siswa sekaligus
                    </div>
                </div>
                <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="font-medium text-xs text-info-600 dark:text-info-400 mb-1">Sync Guru Saja</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Hanya sinkronisasi data Guru</div>
                </div>
                <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="font-medium text-xs text-success-600 dark:text-success-400 mb-1">Sync Siswa Saja</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Hanya sinkronisasi data Siswa</div>
                </div>
            </div>

            <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-700">
                <div class="flex gap-2">
                    <x-heroicon-m-information-circle class="w-5 h-5 text-amber-500" />
                    <p class="text-amber-700 dark:text-amber-300 text-sm">
                        <strong>Catatan:</strong> Data dari RDM akan menjadi master. Data yang sudah ada di Admin
                        Madrasah dengan NIP/NIS yang sama akan diperbarui.
                    </p>
                </div>
            </div>
        </div>

        {{-- Connection Status --}}
        <div class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
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
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        Terhubung
                    </span>
                @else
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                        Terputus
                    </span>
                @endif
            </div>

            @if($connectionOk)
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Koneksi ke database RDM berhasil terjalin. Fitur sinkronisasi siap digunakan.
                </p>
            @else
                <div class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-700">
                    <p class="text-red-700 dark:text-red-300 text-sm font-medium mb-1">Detail Error:</p>
                    <code class="text-xs text-red-600 dark:text-red-400">{{ $connectionError ?? 'Unknown error' }}</code>
                    <p class="text-red-500 dark:text-red-500 text-xs mt-2">Pastikan kredensial RDM sudah dikonfigurasi di
                        file .env server dan database dapat diakses.</p>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>