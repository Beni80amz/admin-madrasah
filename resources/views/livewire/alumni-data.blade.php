<div class="min-h-screen pt-24 pb-16 px-5 md:px-10 lg:px-20 xl:px-40 w-full">
    <div class="max-w-[1400px] mx-auto w-full flex flex-col gap-8">

        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-2 text-sm text-text-secondary-light dark:text-text-secondary-dark">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors" wire:navigate>Beranda</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-gray-500">Akademik</span>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-text-primary-light dark:text-text-primary-dark">Data Alumni</span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex flex-col gap-2">
                    <h1 class="text-text-primary-light dark:text-text-primary-dark text-3xl md:text-4xl font-bold">Data
                        Alumni</h1>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">
                        Daftar alumni {{ $siteProfile->nama_madrasah ?? 'Madrasah' }} dari berbagai tahun kelulusan
                    </p>
                </div>
                @if($totalAlumni > 0)
                    <a href="{{ route('akademik.data-alumni.download') }}"
                        class="flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full transition-all self-start md:self-center">
                        <span class="material-symbols-outlined text-xl">print</span>
                        Cetak
                    </a>
                @endif
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div
            class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl p-6">
            <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                    <!-- Search Input -->
                    <div class="relative flex-1 sm:w-80">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari Nama Alumni..."
                            class="w-full px-4 py-3 bg-white dark:bg-background-dark border border-border-light dark:border-border-dark rounded-xl text-text-primary-light dark:text-text-primary-dark placeholder-gray-400 dark:placeholder-gray-500 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition-all">
                    </div>

                    <!-- Filter Tahun Lulus -->
                    <div class="relative sm:w-48">
                        <select wire:model.live="tahunLulus"
                            class="w-full px-4 py-3 bg-white dark:bg-background-dark border border-border-light dark:border-border-dark rounded-xl text-text-primary-light dark:text-text-primary-dark focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition-all appearance-none cursor-pointer">
                            <option value="">Semua Tahun</option>
                            @foreach($tahunLulusOptions as $option)
                                <option value="{{ $option }}">Lulus {{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <!-- Total Count Badge -->
                    <div class="flex items-center gap-2 px-4 py-2 bg-primary/10 border border-primary/30 rounded-xl">
                        <span class="material-symbols-outlined text-primary text-lg">school</span>
                        <span class="text-primary font-bold">{{ $totalAlumni }}</span>
                        <span class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Alumni</span>
                    </div>

                    <!-- Reset Button -->
                    @if($search || $tahunLulus)
                        <button wire:click="resetFilters"
                            class="flex items-center gap-2 px-4 py-3 bg-white/5 border border-border-dark text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:text-text-primary-dark hover:border-white/20 rounded-xl transition-colors">
                            <span class="material-symbols-outlined text-lg">refresh</span>
                            Reset
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div
            class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border-light dark:border-border-dark bg-gray-50 dark:bg-white/5">
                            <th
                                class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider">
                                No</th>
                            <th
                                class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider">
                                Foto</th>
                            <th
                                class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider">
                                Nama Lengkap</th>
                            <th
                                class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider">
                                Tahun Lulus</th>
                            <th
                                class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider min-w-[200px]">
                                Alamat</th>
                            <th
                                class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider">
                                Nomor Mobile</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light dark:divide-border-dark">
                        @forelse($paginatedAlumni as $index => $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-sm">
                                    {{ $paginatedAlumni->firstItem() + $index }}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="size-10 rounded-full bg-primary/20 flex items-center justify-center">
                                        @if($item->photo)
                                            <img src="{{ Storage::url($item->photo) }}" alt="{{ $item->nama_lengkap }}"
                                                class="size-10 rounded-full object-cover">
                                        @else
                                            <span class="material-symbols-outlined text-primary text-lg">person</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="text-text-primary-light dark:text-text-primary-dark text-sm font-medium">{{ $item->nama_lengkap }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        {{ $item->tahun_lulus }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="text-text-primary-light dark:text-text-primary-dark text-sm">{{ $item->alamat }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-gray-500 text-sm">lock</span>
                                        <span
                                            class="text-gray-500 text-sm font-mono">{{ $this->maskPhoneNumber($item->nomor_mobile) }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="size-16 rounded-full bg-white/5 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-gray-500 text-3xl">search_off</span>
                                        </div>
                                        <p class="text-text-secondary-light dark:text-text-secondary-dark">Tidak ada data
                                            alumni yang ditemukan</p>
                                        @if($search || $tahunLulus)
                                            <button wire:click="resetFilters" class="text-primary hover:underline text-sm">
                                                Reset filter
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($paginatedAlumni->hasPages())
                <div class="border-t border-border-light dark:border-border-dark px-6 py-4">
                    {{ $paginatedAlumni->links() }}
                </div>
            @endif
        </div>

        <!-- Privacy Notice -->
        <div
            class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl p-6">
            <div class="flex items-start gap-4">
                <div
                    class="size-10 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-amber-400">privacy_tip</span>
                </div>
                <div>
                    <h3 class="text-text-primary-light dark:text-text-primary-dark font-bold mb-1">Perlindungan Privasi
                    </h3>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm leading-relaxed">
                        Nomor telepon alumni dirahasiakan untuk melindungi privasi. Untuk menghubungi alumni, silakan
                        menghubungi pihak madrasah terlebih dahulu.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>