<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">
                Sinkronisasi Data dengan RDM
            </x-slot>
            <x-slot name="description">
                Gunakan tombol di atas untuk men-sinkronkan data Guru dan Siswa dari database RDM.
            </x-slot>

            <div class="prose dark:prose-invert max-w-none">
                <h3>Informasi Sync</h3>
                <ul>
                    <li><strong>Sync Semua Data</strong>: Men-sync data Guru dan Siswa sekaligus</li>
                    <li><strong>Sync Guru Saja</strong>: Hanya men-sync data Guru dari RDM</li>
                    <li><strong>Sync Siswa Saja</strong>: Hanya men-sync data Siswa dari RDM</li>
                </ul>

                <div
                    class="mt-4 p-4 bg-warning-50 dark:bg-warning-900/20 rounded-lg border border-warning-200 dark:border-warning-800">
                    <p class="text-warning-700 dark:text-warning-300 text-sm">
                        <strong>Catatan:</strong> Data dari RDM akan menjadi master. Data yang sudah ada di Admin
                        Madrasah dengan NIP/NIS yang sama akan diperbarui.
                    </p>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Status Koneksi
            </x-slot>

            @php
                $connectionOk = false;
                try {
                    \Illuminate\Support\Facades\DB::connection('rdm')->getPdo();
                    $connectionOk = true;
                } catch (\Exception $e) {
                    $connectionError = $e->getMessage();
                }
            @endphp

            @if($connectionOk)
                <div class="flex items-center gap-2 text-success-600 dark:text-success-400">
                    <x-heroicon-o-check-circle class="w-5 h-5" />
                    <span>Koneksi ke database RDM berhasil!</span>
                </div>
            @else
                <div class="flex items-center gap-2 text-danger-600 dark:text-danger-400">
                    <x-heroicon-o-x-circle class="w-5 h-5" />
                    <span>Gagal koneksi ke database RDM: {{ $connectionError ?? 'Unknown error' }}</span>
                </div>
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>