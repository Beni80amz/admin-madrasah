<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Cache & Optimization -->
        <x-filament::section>
            <x-slot name="heading">
                Performance & Cache
            </x-slot>
            <x-slot name="description">
                Bersihkan cache aplikasi untuk menerapkan perubahan konfigurasi atau kode.
            </x-slot>

            <div class="flex flex-col gap-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Gunakan ini jika Anda baru saja mengubah file .env atau merasa aplikasi tidak memuat perubahan
                    terbaru.
                </div>
                <div>
                    <x-filament::button wire:click="clearCache" color="warning" icon="heroicon-o-trash">
                        Bersihkan Cache (Optimize:Clear)
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>

        <!-- Database -->
        <x-filament::section>
            <x-slot name="heading">
                Database & Storage
            </x-slot>
            <x-slot name="description">
                Manajemen database dan symbolic link storage.
            </x-slot>

            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-800">
                    <div>
                        <h4 class="font-medium">Migrasi Database</h4>
                        <p class="text-xs text-gray-500">Jalankan migrasi untuk update tabel database.</p>
                    </div>
                    <x-filament::button wire:click="migrateDatabase" color="primary" icon="heroicon-o-circle-stack">
                        Migrate
                    </x-filament::button>
                </div>

                <div class="flex items-center justify-between gap-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-800">
                    <div>
                        <h4 class="font-medium">Storage Link</h4>
                        <p class="text-xs text-gray-500">Perbaiki link gambar yang rusak.</p>
                    </div>
                    <x-filament::button wire:click="linkStorage" color="gray" icon="heroicon-o-link">
                        Link Storage
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>

        <!-- System Update (Git) -->
        <x-filament::section class="md:col-span-2 border-primary-500 dark:border-primary-400">
            <x-slot name="heading">
                Update Aplikasi (Git Pull)
            </x-slot>
            <x-slot name="description">
                Tarik perubahan kode terbaru dari repository (GitHub/GitLab).
            </x-slot>

            <div class="space-y-4">
                <div class="p-4 border-l-4 border-info-500 bg-info-50 dark:bg-slate-800 dark:border-info-400">
                    <p class="text-sm text-info-700 dark:text-info-300">
                        <strong>Catatan:</strong> Fitur ini memerlukan Git terinstall di server dan akses write ke
                        folder project.
                        Pastikan tidak ada file yang diubah secara manual di server (conflict).
                    </p>
                </div>

                <div class="flex justify-end gap-3">
                    <x-filament::button wire:click="gitPull" size="lg" icon="heroicon-o-arrow-path" color="success">
                        Update Aplikasi Sekarang (Git Pull)
                    </x-filament::button>
                </div>

                @if($commandOutput)
                    <div class="mt-4 p-4 bg-gray-900 text-gray-100 font-mono text-sm rounded overflow-x-auto">
                        <pre>{{ $commandOutput }}</pre>
                    </div>
                @endif
            </div>
        </x-filament::section>

    </div>
</x-filament-panels::page>