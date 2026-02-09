<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">
                Kelola Form Unduhan
            </x-slot>

            <p class="text-gray-600 dark:text-gray-400">
                Halaman ini digunakan untuk mengelola file unduhan yang akan ditampilkan di menu "Unduhan" pada
                frontend.
                Anda dapat menambahkan, menghapus, atau memperbarui file-file seperti formulir pendaftaran, brosur, atau
                dokumen penting lainnya.
            </p>

            <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="material-symbols-outlined text-yellow-500">info</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 dark:text-yellow-400">
                            Fitur unggah file akan segera hadir dalam pembaruan berikutnya.
                            Gunakan halaman ini sebagai placeholder untuk persiapan konten unduhan.
                        </p>
                    </div>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>