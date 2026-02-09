<div class="min-h-screen bg-surface-light dark:bg-background-dark">
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-transparent"></div>
        <div class="layout-container relative z-10 px-5 md:px-10 lg:px-40">
            <div class="max-w-3xl">
                <span
                    class="inline-block px-4 py-2 rounded-full bg-primary/10 text-primary text-sm font-bold tracking-wider uppercase mb-4 animate-fade-in-up">
                    Pusat Unduhan
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 dark:text-white leading-tight tracking-tight mb-6 animate-fade-in-up"
                    style="animation-delay: 100ms">
                    Akses Mudah ke <span class="text-primary italic">Dokumen Penting</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 animate-fade-in-up"
                    style="animation-delay: 200ms">
                    Temukan dan unduh berbagai dokumen, formulir, dan materi pendukung lainnya yang disediakan oleh
                    madrasah.
                </p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="pb-32 relative">
        <div class="layout-container px-5 md:px-10 lg:px-40">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Placeholder Card 1 -->
                <div
                    class="group bg-white dark:bg-surface-dark p-8 rounded-3xl border border-border-light dark:border-border-dark hover:border-primary transition-all duration-300 shadow-sm hover:shadow-xl hover:-translate-y-2">
                    <div
                        class="size-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary mb-6 transition-colors group-hover:bg-primary group-hover:text-white">
                        <span class="material-symbols-outlined text-3xl">description</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 leading-tight">Formulir Pendaftaran
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6 line-clamp-2">
                        Surat pernyataan dan berkas fisik pendaftaran peserta didik baru tahun ajaran 2024/2025.
                    </p>
                    <button class="flex items-center gap-2 text-primary font-bold hover:gap-3 transition-all">
                        <span>Unduh File</span>
                        <span class="material-symbols-outlined text-sm">download</span>
                    </button>
                </div>

                <!-- Placeholder Card 2 -->
                <div
                    class="group bg-white dark:bg-surface-dark p-8 rounded-3xl border border-border-light dark:border-border-dark hover:border-primary transition-all duration-300 shadow-sm hover:shadow-xl hover:-translate-y-2">
                    <div
                        class="size-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary mb-6 transition-colors group-hover:bg-primary group-hover:text-white">
                        <span class="material-symbols-outlined text-3xl">event_note</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 leading-tight">Kalender Akademik
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6 line-clamp-2">
                        Jadwal kegiatan belajar mengajar, hari libur, dan agenda penting madrasah semester ganjil.
                    </p>
                    <button class="flex items-center gap-2 text-primary font-bold hover:gap-3 transition-all">
                        <span>Unduh File</span>
                        <span class="material-symbols-outlined text-sm">download</span>
                    </button>
                </div>

                <!-- Placeholder Card 3 -->
                <div
                    class="group bg-white dark:bg-surface-dark p-8 rounded-3xl border border-border-light dark:border-border-dark hover:border-primary transition-all duration-300 shadow-sm hover:shadow-xl hover:-translate-y-2">
                    <div
                        class="size-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary mb-6 transition-colors group-hover:bg-primary group-hover:text-white">
                        <span class="material-symbols-outlined text-3xl">auto_stories</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 leading-tight">Brosur Madrasah</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6 line-clamp-2">
                        Informasi lengkap mengenai program unggulan, fasilitas, dan biaya pendidikan di madrasah kami.
                    </p>
                    <button class="flex items-center gap-2 text-primary font-bold hover:gap-3 transition-all">
                        <span>Unduh File</span>
                        <span class="material-symbols-outlined text-sm">download</span>
                    </button>
                </div>
            </div>

            <!-- Coming Soon Info -->
            <div
                class="mt-20 p-8 rounded-[40px] bg-white dark:bg-surface-dark/50 border border-border-light dark:border-border-dark relative overflow-hidden text-center max-w-4xl mx-auto">
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Butuh Dokumen Lain?</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-0">
                        Kami sedang memperbarui koleksi dokumen kami. Jika Anda tidak menemukan file yang Anda cari,
                        silakan hubungi bagian administrasi madrasah.
                    </p>
                </div>
                <div class="absolute -right-20 -top-20 size-60 bg-primary/5 rounded-full blur-3xl"></div>
                <div class="absolute -left-20 -bottom-20 size-60 bg-primary/5 rounded-full blur-3xl"></div>
            </div>
        </div>
    </section>
</div>

<style>
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>