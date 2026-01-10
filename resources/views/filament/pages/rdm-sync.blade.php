<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        {{-- Info Section --}}
        <x-filament::section>
            <x-slot name="heading">
                Sinkronisasi Data dengan RDM
            </x-slot>
            <x-slot name="description">
                Gunakan tombol di atas untuk men-sinkronkan data Guru dan Siswa dari database RDM.
            </x-slot>

            {{-- Grid using flexbox for fallback --}}
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                {{-- Card 1 --}}
                <div style="padding: 0.75rem; background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 0.5rem;"
                    class="bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                    <div style="font-weight: 500; font-size: 0.85rem; margin-bottom: 0.25rem; color: #059669;"
                        class="text-primary-600 dark:text-primary-400">
                        Sync Semua Data
                    </div>
                    <div style="font-size: 0.8rem; opacity: 0.8;" class="text-gray-600 dark:text-gray-400">
                        Sinkronisasi data Guru dan Siswa sekaligus
                    </div>
                </div>

                {{-- Card 2 --}}
                <div style="padding: 0.75rem; background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 0.5rem;"
                    class="bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                    <div style="font-weight: 500; font-size: 0.85rem; margin-bottom: 0.25rem; color: #0ea5e9;"
                        class="text-info-600 dark:text-info-400">
                        Sync Guru Saja
                    </div>
                    <div style="font-size: 0.8rem; opacity: 0.8;" class="text-gray-600 dark:text-gray-400">
                        Hanya sinkronisasi data Guru
                    </div>
                </div>

                {{-- Card 3 --}}
                <div style="padding: 0.75rem; background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 0.5rem;"
                    class="bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                    <div style="font-weight: 500; font-size: 0.85rem; margin-bottom: 0.25rem; color: #10b981;"
                        class="text-success-600 dark:text-success-400">
                        Sync Siswa Saja
                    </div>
                    <div style="font-size: 0.8rem; opacity: 0.8;" class="text-gray-600 dark:text-gray-400">
                        Hanya sinkronisasi data Siswa
                    </div>
                </div>
            </div>

            {{-- Note Box --}}
            <div style="padding: 0.75rem; background-color: rgba(251, 191, 36, 0.1); border: 1px solid rgba(251, 191, 36, 0.2); border-radius: 0.5rem;"
                class="bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-700">
                <div style="display: flex; gap: 0.5rem; align-items: flex-start;">
                    <x-heroicon-m-information-circle
                        style="width: 20px; height: 20px; color: #f59e0b; flex-shrink: 0;" />
                    <p style="font-size: 0.875rem; color: #d97706; margin: 0;"
                        class="text-amber-700 dark:text-amber-300">
                        <strong>Catatan:</strong> Data dari RDM akan menjadi master. Data yang sudah ada di Admin
                        Madrasah dengan NIP/NIS yang sama akan diperbarui.
                    </p>
                </div>
            </div>
        </x-filament::section>

        {{-- Connection Status --}}
        <x-filament::section>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                    Status Koneksi Database RDM
                </div>

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
                        style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; background-color: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2);">
                        <span
                            style="width: 0.375rem; height: 0.375rem; border-radius: 50%; background-color: #10b981;"></span>
                        Terhubung
                    </span>
                @else
                    <span
                        style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; background-color: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);">
                        <span
                            style="width: 0.375rem; height: 0.375rem; border-radius: 50%; background-color: #ef4444;"></span>
                        Terputus
                    </span>
                @endif
            </div>

            @if($connectionOk)
                <p style="font-size: 0.875rem; opacity: 0.8;" class="text-gray-600 dark:text-gray-400">
                    Koneksi ke database RDM berhasil terjalin. Fitur sinkronisasi siap digunakan.
                </p>
            @else
                <div
                    style="margin-top: 0.75rem; padding: 0.75rem; background-color: rgba(239, 68, 68, 0.1); border-radius: 0.5rem; border: 1px solid rgba(239, 68, 68, 0.2);">
                    <p style="color: #ef4444; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Detail Error:
                    </p>
                    <code
                        style="font-size: 0.75rem; color: #ef4444; display: block; word-break: break-all;">{{ $connectionError ?? 'Unknown error' }}</code>
                    <p style="color: #ef4444; font-size: 0.75rem; margin-top: 0.5rem;">Pastikan kredensial RDM sudah
                        dikonfigurasi di file .env server dan database dapat diakses.</p>
                </div>
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>