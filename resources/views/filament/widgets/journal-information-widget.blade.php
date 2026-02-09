<div>
    <x-filament::section icon="heroicon-o-information-circle" collapsible>
        <x-slot name="heading">
            <span class="text-lg font-bold">Petunjuk & Status Dokumen</span>
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div class="space-y-3">
                <div class="flex items-center gap-2 text-primary-600 dark:text-primary-400 font-semibold">
                    <x-heroicon-m-academic-cap class="w-5 h-5" />
                    <span>Panduan Pengisian:</span>
                </div>
                <ul class="list-disc pl-5 space-y-2 text-gray-600 dark:text-gray-400">
                    <li><strong class="text-gray-900 dark:text-gray-100">Disiplin Waktu:</strong> Segera isi jurnal
                        setelah KBM selesai agar data tetap akurat dan relevan.</li>
                    <li><strong class="text-gray-900 dark:text-gray-100">Presensi Siswa:</strong> Pastikan total angka
                        Sakit, Izin, dan Alpha sesuai dengan daftar nama yang dipilih.</li>
                    <li><strong class="text-gray-900 dark:text-gray-100">Refleksi:</strong> Isian ini sangat penting
                        untuk pendataan supervisi akademik dan akreditasi sekolah.</li>
                </ul>
            </div>

            <div class="space-y-3">
                <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400 font-semibold">
                    <x-heroicon-m-shield-check class="w-5 h-5" />
                    <span>Integritas Data:</span>
                </div>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Dokumen ini merupakan rekam jejak digital resmi aktivitas pembelajaran Anda di Madrasah.
                    Data yang diinput akan digunakan sebagai dasar laporan bulanan dan evaluasi kinerja guru.
                    Pastikan seluruh informasi valid sebelum menekan tombol simpan.
                </p>
            </div>
        </div>
    </x-filament::section>
</div>