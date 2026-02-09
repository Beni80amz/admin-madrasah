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
            <!-- Category Filter Tabs -->
            <div class="flex flex-wrap items-center justify-center gap-3 mb-16 animate-fade-in-up"
                style="animation-delay: 300ms">
                @foreach($categories as $category)
                    <button wire:click="setCategory('{{ $category }}')"
                        class="px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 {{ $selectedCategory === $category ? 'bg-primary text-white shadow-lg shadow-primary/20 scale-105' : 'bg-white dark:bg-surface-dark text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 border border-border-light dark:border-border-dark' }}">
                        {{ $category }}
                    </button>
                @endforeach
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-20">
                @forelse($downloads as $download)
                    <div wire:key="download-{{ $download->id }}"
                        class="group bg-white dark:bg-surface-dark p-6 rounded-3xl border border-border-light dark:border-border-dark hover:border-primary transition-all duration-300 shadow-sm hover:shadow-xl hover:-translate-y-2 animate-fade-in-up">
                        <div
                            class="size-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary mb-5 transition-colors group-hover:bg-primary group-hover:text-white">
                            @php
                                $icon = match ($download->category) {
                                    'Pendaftaran' => 'description',
                                    'Akademik' => 'event_note',
                                    'Brosur' => 'auto_stories',
                                    'Surat' => 'mail',
                                    default => 'draft',
                                };
                            @endphp
                            <span class="material-symbols-outlined text-2xl">{{ $icon }}</span>
                        </div>

                        <div class="mb-5">
                            <div class="flex items-center gap-2 mb-2">
                                <span
                                    class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400">
                                    {{ $download->category }}
                                </span>
                            </div>
                            <h3
                                class="text-lg font-bold text-gray-900 dark:text-white mb-2 leading-tight group-hover:text-primary transition-colors">
                                {{ $download->title }}
                            </h3>
                            @if($download->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    {{ $download->description }}
                                </p>
                            @endif
                        </div>

                        <div
                            class="pt-4 border-t border-border-light dark:border-white/5 flex items-center justify-between">
                            <button wire:click="downloadFile({{ $download->id }})" wire:loading.attr="disabled"
                                class="flex items-center gap-2 text-primary text-sm font-bold hover:gap-3 transition-all disabled:opacity-50">
                                <span wire:loading.remove wire:target="downloadFile({{ $download->id }})">Unduh</span>
                                <span wire:loading wire:target="downloadFile({{ $download->id }})">...</span>
                                <span class="material-symbols-outlined text-sm">download</span>
                            </button>
                            <span class="text-[10px] font-medium text-gray-400 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[12px]">visibility</span>
                                {{ $download->download_count }}x
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center animate-fade-in-up">
                        <div
                            class="size-20 bg-gray-100 dark:bg-surface-dark rounded-full flex items-center justify-center mx-auto mb-6 text-gray-400">
                            <span class="material-symbols-outlined text-4xl">folder_off</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Belum Ada Dokumen</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Silakan pilih kategori lain atau cek kembali nanti.
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Help Info -->
            <div
                class="mt-32 p-10 rounded-[40px] bg-white dark:bg-surface-dark/30 border border-border-light dark:border-border-dark relative overflow-hidden text-center max-w-4xl mx-auto shadow-sm animate-fade-in-up">
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