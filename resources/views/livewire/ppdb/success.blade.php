<div>
    <div class="min-h-screen bg-background-light dark:bg-background-dark py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto pt-20">
            {{-- Success Card --}}
            <div
                class="bg-surface-light dark:bg-surface-dark rounded-3xl shadow-2xl border border-border-light dark:border-border-dark overflow-hidden">
                {{-- Success Header --}}
                <div class="bg-gradient-to-br from-primary to-emerald-600 p-8 text-center">
                    <div
                        class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-white/20 backdrop-blur-sm mb-6 animate-bounce-slow">
                        <span class="material-symbols-outlined text-6xl text-white">check_circle</span>
                    </div>
                    <h1 class="text-3xl font-extrabold text-white mb-2">Pendaftaran Berhasil!</h1>
                    <p class="text-white/80">Data Anda telah berhasil dikirim ke panitia PPDB</p>
                </div>

                {{-- Registration Details --}}
                <div class="p-8">
                    {{-- Registration Code --}}
                    <div
                        class="bg-gray-50 dark:bg-white/5 rounded-2xl p-6 mb-6 text-center border-2 border-dashed border-primary/30">
                        <span
                            class="block text-sm text-text-secondary-light dark:text-text-secondary-dark uppercase tracking-widest mb-2">Kode
                            Pendaftaran</span>
                        <span
                            class="block text-4xl font-mono font-extrabold text-primary">{{ $registration->no_daftar }}</span>
                        <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark mt-2">
                            Simpan kode ini untuk pengecekan status pendaftaran
                        </p>
                    </div>

                    {{-- Summary --}}
                    <div class="space-y-4 mb-8">
                        <h3
                            class="text-lg font-bold text-text-primary-light dark:text-text-primary-dark flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">summarize</span>
                            Ringkasan Pendaftaran
                        </h3>
                        <div class="grid gap-3">
                            <div class="flex justify-between py-3 border-b border-border-light dark:border-border-dark">
                                <span class="text-text-secondary-light dark:text-text-secondary-dark">Nama
                                    Lengkap</span>
                                <span
                                    class="font-medium text-text-primary-light dark:text-text-primary-dark">{{ $registration->nama_lengkap }}</span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-border-light dark:border-border-dark">
                                <span class="text-text-secondary-light dark:text-text-secondary-dark">NIK</span>
                                <span
                                    class="font-medium text-text-primary-light dark:text-text-primary-dark font-mono">{{ $registration->nik }}</span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-border-light dark:border-border-dark">
                                <span class="text-text-secondary-light dark:text-text-secondary-dark">TTL</span>
                                <span class="font-medium text-text-primary-light dark:text-text-primary-dark">
                                    {{ $registration->tempat_lahir }},
                                    {{ $registration->tanggal_lahir->format('d M Y') }}
                                </span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-border-light dark:border-border-dark">
                                <span class="text-text-secondary-light dark:text-text-secondary-dark">Asal
                                    Sekolah</span>
                                <span class="font-medium text-text-primary-light dark:text-text-primary-dark">
                                    {{ $registration->asal_sekolah }}{{ $registration->nama_sekolah_asal ? ' - ' . $registration->nama_sekolah_asal : '' }}
                                </span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-border-light dark:border-border-dark">
                                <span class="text-text-secondary-light dark:text-text-secondary-dark">No. HP Orang
                                    Tua</span>
                                <span
                                    class="font-medium text-text-primary-light dark:text-text-primary-dark font-mono">{{ $registration->no_hp_ortu }}</span>
                            </div>
                            <div class="flex justify-between py-3">
                                <span class="text-text-secondary-light dark:text-text-secondary-dark">Tanggal
                                    Daftar</span>
                                <span class="font-medium text-text-primary-light dark:text-text-primary-dark">
                                    {{ $registration->created_at->translatedFormat('d F Y, H:i') }} WIB
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="space-y-4">
                        {{-- Download PDF --}}
                        <a href="{{ route('ppdb.receipt.download', $registration->id) }}"
                            class="flex items-center justify-center gap-3 w-full px-6 py-4 bg-primary text-white font-bold text-lg rounded-2xl hover:bg-primary-dark transition-all shadow-lg shadow-primary/30">
                            <span class="material-symbols-outlined text-2xl">download</span>
                            Download Bukti Pendaftaran (PDF)
                        </a>

                        {{-- Share WhatsApp --}}
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer"
                            class="flex items-center justify-center gap-3 w-full px-6 py-4 bg-green-500 text-white font-bold text-lg rounded-2xl hover:bg-green-600 transition-all shadow-lg shadow-green-500/30">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            Kirim via WhatsApp
                        </a>

                        {{-- Back to Home --}}
                        <a href="{{ route('home') }}" wire:navigate
                            class="flex items-center justify-center gap-3 w-full px-6 py-4 bg-gray-100 dark:bg-white/5 text-text-primary-light dark:text-text-primary-dark font-medium text-lg rounded-2xl hover:bg-gray-200 dark:hover:bg-white/10 transition-all border border-border-light dark:border-border-dark">
                            <span class="material-symbols-outlined">home</span>
                            Kembali ke Beranda
                        </a>
                    </div>

                    {{-- Info Note --}}
                    <div
                        class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-blue-500 flex-shrink-0">info</span>
                            <div class="text-sm text-blue-700 dark:text-blue-300">
                                <p class="font-medium mb-1">Langkah Selanjutnya:</p>
                                <ul class="list-disc list-inside space-y-1 text-blue-600 dark:text-blue-400">
                                    <li>Tunggu verifikasi dari panitia PPDB</li>
                                    <li>Cek status pendaftaran secara berkala</li>
                                    <li>Simpan bukti pendaftaran sebagai arsip</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes bounce-slow {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .animate-bounce-slow {
            animation: bounce-slow 2s ease-in-out infinite;
        }
    </style>
</div>