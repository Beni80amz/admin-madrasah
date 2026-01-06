<div class="min-h-screen pt-24 pb-16 px-5 md:px-10 lg:px-20 xl:px-40 w-full">
    <div class="max-w-[1400px] mx-auto w-full flex flex-col gap-8">

        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-2 text-sm text-text-secondary-light dark:text-text-secondary-dark">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors" wire:navigate>Beranda</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-gray-500">Akademik</span>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-text-primary-light dark:text-text-primary-dark">Data Guru dan Staff</span>
            </div>
            <div class="flex flex-col gap-2">
                <h1 class="text-text-primary-light dark:text-text-primary-dark text-3xl md:text-4xl font-bold">Data Guru
                    dan Staff</h1>
                <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">
                    Daftar lengkap data guru dan tenaga pendidik
                    {{ $siteProfile->nama_madrasah ?? 'Madrasah Prototype' }}
                </p>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div
            class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl p-6">
            <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                    <!-- Search Input -->
                    <div class="relative flex-1 sm:w-80">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari Nama Guru..."
                            class="w-full px-4 py-3 bg-white dark:bg-background-dark border border-border-light dark:border-border-dark rounded-xl text-text-primary-light dark:text-text-primary-dark placeholder-gray-400 dark:placeholder-gray-500 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition-all">
                    </div>

                    <!-- Filter Jabatan -->
                    <div class="relative sm:w-56">
                        <select wire:model.live="jabatan"
                            class="w-full px-4 py-3 bg-white dark:bg-background-dark border border-border-light dark:border-border-dark rounded-xl text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition-all appearance-none cursor-pointer"
                            style="color-scheme: light dark;">
                            <option value="" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white">Semua
                                Jabatan</option>
                            @foreach($jabatanOptions as $option)
                                <option value="{{ $option }}"
                                    class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Total Count Badge -->
                <div class="flex items-center gap-2 px-4 py-2 bg-primary/10 border border-primary/30 rounded-xl">
                    <span class="material-symbols-outlined text-primary text-lg">groups</span>
                    <span class="text-primary font-bold">{{ $teachers->total() }}</span>
                    <span class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Guru</span>
                </div>

                <!-- Reset Button -->
                @if($search || $jabatan)
                    <button wire:click="resetFilters"
                        class="flex items-center gap-2 px-4 py-3 bg-white/5 border border-border-dark text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:text-text-primary-dark hover:border-white/20 rounded-xl transition-colors">
                        <span class="material-symbols-outlined text-lg">refresh</span>
                        Reset
                    </button>
                @endif
            </div>

            <!-- Export Button -->
            <div class="mt-4 flex justify-end">
                <button wire:click="downloadPdf"
                    class="flex items-center gap-2 px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors shadow-lg shadow-red-600/20">
                    <span class="material-symbols-outlined text-lg">picture_as_pdf</span>
                    Export PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div
        class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl overflow-hidden mt-8">
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
                            NUPTK</th>
                        <th
                            class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider">
                            NPK/Peg.ID</th>
                        <th
                            class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider">
                            Jabatan/Posisi</th>
                        <th
                            class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider">
                            Tugas Pokok</th>
                        <th
                            class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider">
                            Kelas/Rombel</th>
                        <th
                            class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider min-w-[200px]">
                            Tugas Tambahan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light dark:divide-border-dark">
                    @forelse($teachers as $index => $teacher)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-sm">
                                {{ $teachers->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-4">
                                <div
                                    class="size-10 rounded-full bg-primary/20 flex items-center justify-center overflow-hidden">
                                    @if($teacher->photo)
                                        <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->nama_lengkap }}"
                                            class="size-10 rounded-full object-cover">
                                    @else
                                        <span class="material-symbols-outlined text-primary text-lg">person</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span
                                    class="text-text-primary-light dark:text-text-primary-dark text-sm font-medium">{{ $teacher->nama_lengkap }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <span
                                    class="text-text-secondary-light dark:text-text-secondary-dark text-sm">{{ $teacher->nuptk ?? '-' }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <span
                                    class="text-text-secondary-light dark:text-text-secondary-dark text-sm">{{ $teacher->npk_peg_id ?? '-' }}</span>
                            </td>
                            <td class="py-4 px-4">
                                @php
                                    $jabatanColors = [
                                        'Kepala Madrasah' => 'bg-amber-600 text-white',
                                        'Waka Kurikulum' => 'bg-blue-600 text-white',
                                        'Waka Kesiswaan' => 'bg-violet-600 text-white',
                                        'Operator' => 'bg-cyan-600 text-white',
                                        'Kaur. Tata Usaha' => 'bg-emerald-600 text-white',
                                        'Staff Tata Usaha' => 'bg-gray-600 text-white',
                                        'Bendahara' => 'bg-rose-600 text-white',
                                    ];
                                    $jabatanNama = $teacher->jabatan?->nama ?? '-';
                                    $colorClass = $jabatanColors[$jabatanNama] ?? 'bg-gray-600 text-white';
                                @endphp
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold {{ $colorClass }}">
                                    {{ $jabatanNama }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span
                                    class="text-text-primary-light dark:text-text-primary-dark text-sm">{{ $teacher->tugasPokok?->nama ?? '-' }}</span>
                            </td>
                            <td class="py-4 px-4">
                                @if($teacher->kelas_rombel !== '-')
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-violet-600/20 text-violet-400 border border-violet-500/30">
                                        {{ $teacher->kelas_rombel }}
                                    </span>
                                @else
                                    <span class="text-gray-500 text-sm">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                @if($teacher->tugasTambahan)
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                                        {{ $teacher->tugasTambahan->nama }}
                                    </span>
                                @else
                                    <span class="text-gray-500 text-sm">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="size-16 rounded-full bg-white/5 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-gray-500 text-3xl">search_off</span>
                                    </div>
                                    <p class="text-text-secondary-light dark:text-text-secondary-dark">Tidak ada data guru
                                        yang ditemukan</p>
                                    @if($search || $jabatan)
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
        @if($teachers->hasPages())
            <div
                class="border-t border-border-light dark:border-border-dark px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-text-secondary-light dark:text-text-secondary-dark text-sm">
                    Menampilkan {{ $teachers->firstItem() }} - {{ $teachers->lastItem() }} dari
                    {{ $teachers->total() }} data
                </div>
                <div class="flex items-center gap-2">
                    <!-- Previous Button -->
                    @if($teachers->onFirstPage())
                        <button disabled
                            class="flex items-center justify-center size-10 rounded-lg border border-border-dark text-gray-600 cursor-not-allowed">
                            <span class="material-symbols-outlined text-lg">chevron_left</span>
                        </button>
                    @else
                        <button wire:click="previousPage"
                            class="flex items-center justify-center size-10 rounded-lg border border-border-dark text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:text-text-primary-dark hover:border-white/20 transition-colors">
                            <span class="material-symbols-outlined text-lg">chevron_left</span>
                        </button>
                    @endif

                    <!-- Page Numbers -->
                    @foreach($teachers->getUrlRange(1, $teachers->lastPage()) as $page => $url)
                        @if($teachers->currentPage() == $page)
                            <button
                                class="flex items-center justify-center size-10 rounded-lg border border-primary bg-primary text-white font-medium text-sm">
                                {{ $page }}
                            </button>
                        @else
                            <button wire:click="gotoPage({{ $page }})"
                                class="flex items-center justify-center size-10 rounded-lg border border-border-dark text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:text-text-primary-dark hover:border-white/20 transition-colors font-medium text-sm">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach

                    <!-- Next Button -->
                    @if($teachers->onLastPage())
                        <button disabled
                            class="flex items-center justify-center size-10 rounded-lg border border-border-dark text-gray-600 cursor-not-allowed">
                            <span class="material-symbols-outlined text-lg">chevron_right</span>
                        </button>
                    @else
                        <button wire:click="nextPage"
                            class="flex items-center justify-center size-10 rounded-lg border border-border-dark text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:text-text-primary-dark hover:border-white/20 transition-colors">
                            <span class="material-symbols-outlined text-lg">chevron_right</span>
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>

</div>