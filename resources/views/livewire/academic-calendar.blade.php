<div class="min-h-screen pt-24 pb-16 px-5 md:px-10 lg:px-20 xl:px-40 w-full">
    <div class="max-w-[1200px] mx-auto w-full flex flex-col gap-12">

        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-2 text-sm text-text-secondary-light dark:text-text-secondary-dark">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors" wire:navigate>Beranda</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-gray-500">Akademik</span>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-text-primary-light dark:text-text-primary-dark">Kalender Akademik</span>
            </div>
            <div class="flex flex-col gap-3">
                <h1 class="text-text-primary-light dark:text-text-primary-dark text-4xl md:text-5xl font-bold">Kalender
                    Akademik</h1>
                <p class="text-text-secondary-light dark:text-text-secondary-dark max-w-3xl text-lg leading-relaxed">
                    Jadwal kegiatan akademik dan non-akademik {{ $siteProfile->nama_madrasah ?? 'Madrasah Prototype' }}
                    Tahun Ajaran {{ $tahunAjaran->nama ?? '-' }}
                </p>
            </div>
        </div>

        <!-- Quick Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl p-6 flex items-center gap-4">
                <div
                    class="size-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-2xl">calendar_month</span>
                </div>
                <div>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Tahun Ajaran</p>
                    <p class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg">
                        {{ $tahunAjaran->nama ?? '-' }}</p>
                </div>
            </div>
            <div
                class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl p-6 flex items-center gap-4">
                <div
                    class="size-14 rounded-2xl bg-gradient-to-br from-primary to-emerald-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-2xl">school</span>
                </div>
                <div>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Semester Aktif</p>
                    <p class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg">Semester Ganjil</p>
                </div>
            </div>
            <div
                class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl p-6 flex items-center gap-4">
                <div
                    class="size-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-2xl">event</span>
                </div>
                <div>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Total Hari Efektif</p>
                    <p class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg">
                        {{ $totalHariEfektif }} Hari</p>
                </div>
            </div>
        </div>

        <!-- Semester Ganjil -->
        <section class="flex flex-col gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="size-12 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-2xl">looks_one</span>
                </div>
                <div>
                    <h2 class="text-text-primary-light dark:text-text-primary-dark text-2xl font-bold">Semester Ganjil
                    </h2>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Juli - Desember
                        {{ $tahunGanjil ?? date('Y') }}</p>
                </div>
            </div>

            <div
                class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-border-light dark:border-border-dark bg-gray-50 dark:bg-white/5">
                                <th
                                    class="text-left py-4 px-6 text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">
                                    Tanggal</th>
                                <th
                                    class="text-left py-4 px-6 text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">
                                    Kegiatan</th>
                                <th
                                    class="text-left py-4 px-6 text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">
                                    Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-light dark:divide-border-dark">
                            @forelse($semesterGanjil as $item)
                                @php
                                    $dateDisplay = $item->tanggal_mulai->format('d M Y');
                                    if ($item->tanggal_selesai && $item->tanggal_selesai != $item->tanggal_mulai) {
                                        $dateDisplay = $item->tanggal_mulai->format('d M') . ' - ' . $item->tanggal_selesai->format('d M Y');
                                    }
                                    $type = match ($item->kategori) {
                                        'Hari Libur' => 'libur',
                                        'Asesmen/Penilaian', 'Pelaksanaan ATS Ganjil/Genap', 'Pelaksanaan AAS Ganjil/Genap' => 'ujian',
                                        'Pembagian Raport PTS Ganjil/Genap' => 'raport_pts',
                                        'Pembagian Raport AAS Ganjil/Genap' => 'raport_aas',
                                        default => 'kegiatan',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    <td
                                        class="py-4 px-6 text-text-primary-light dark:text-text-primary-dark text-sm whitespace-nowrap">
                                        {{ $dateDisplay }}</td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            @if($type === 'libur')
                                                <span class="size-3 rounded-full bg-red-500"></span>
                                            @elseif($type === 'ujian')
                                                <span class="size-3 rounded-full bg-amber-500"></span>
                                            @elseif($type === 'raport_pts')
                                                <span class="size-3 rounded-full bg-violet-500"></span>
                                            @elseif($type === 'raport_aas')
                                                <span class="size-3 rounded-full bg-pink-500"></span>
                                            @else
                                                <span class="size-3 rounded-full bg-primary"></span>
                                            @endif
                                            <span
                                                class="text-text-primary-light dark:text-text-primary-dark text-sm">{{ $item->nama_kegiatan }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="text-xs font-medium px-3 py-1 rounded-full
                                                                        @if($type === 'libur') bg-red-500/10 text-red-400 border border-red-500/20
                                                                        @elseif($type === 'ujian') bg-amber-500/10 text-amber-400 border border-amber-500/20
                                                                        @elseif($type === 'raport_pts') bg-violet-500/10 text-violet-400 border border-violet-500/20
                                                                        @elseif($type === 'raport_aas') bg-pink-500/10 text-pink-400 border border-pink-500/20
                                                                        @else bg-primary/10 text-primary border border-primary/20
                                                                        @endif">
                                            {{ $item->keterangan ?? $item->kategori }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-gray-500">Belum ada data kegiatan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Semester Genap -->
        <section class="flex flex-col gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="size-12 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-2xl">looks_two</span>
                </div>
                <div>
                    <h2 class="text-text-primary-light dark:text-text-primary-dark text-2xl font-bold">Semester Genap
                    </h2>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Januari - Juni
                        {{ $tahunGenap ?? (date('Y') + 1) }}</p>
                </div>
            </div>

            <div
                class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-border-light dark:border-border-dark bg-gray-50 dark:bg-white/5">
                                <th
                                    class="text-left py-4 px-6 text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">
                                    Tanggal</th>
                                <th
                                    class="text-left py-4 px-6 text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">
                                    Kegiatan</th>
                                <th
                                    class="text-left py-4 px-6 text-text-secondary-light dark:text-text-secondary-dark text-sm font-medium">
                                    Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-light dark:divide-border-dark">
                            @forelse($semesterGenap as $item)
                                @php
                                    $dateDisplay = $item->tanggal_mulai->format('d M Y');
                                    if ($item->tanggal_selesai && $item->tanggal_selesai != $item->tanggal_mulai) {
                                        $dateDisplay = $item->tanggal_mulai->format('d M') . ' - ' . $item->tanggal_selesai->format('d M Y');
                                    }
                                    $type = match ($item->kategori) {
                                        'Hari Libur' => 'libur',
                                        'Asesmen/Penilaian', 'Pelaksanaan ATS Ganjil/Genap', 'Pelaksanaan AAS Ganjil/Genap' => 'ujian',
                                        'Pembagian Raport PTS Ganjil/Genap' => 'raport_pts',
                                        'Pembagian Raport AAS Ganjil/Genap' => 'raport_aas',
                                        default => 'kegiatan',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    <td
                                        class="py-4 px-6 text-text-primary-light dark:text-text-primary-dark text-sm whitespace-nowrap">
                                        {{ $dateDisplay }}</td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            @if($type === 'libur')
                                                <span class="size-3 rounded-full bg-red-500"></span>
                                            @elseif($type === 'ujian')
                                                <span class="size-3 rounded-full bg-amber-500"></span>
                                            @elseif($type === 'raport_pts')
                                                <span class="size-3 rounded-full bg-violet-500"></span>
                                            @elseif($type === 'raport_aas')
                                                <span class="size-3 rounded-full bg-pink-500"></span>
                                            @else
                                                <span class="size-3 rounded-full bg-primary"></span>
                                            @endif
                                            <span
                                                class="text-text-primary-light dark:text-text-primary-dark text-sm">{{ $item->nama_kegiatan }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="text-xs font-medium px-3 py-1 rounded-full
                                                                    @if($type === 'libur') bg-red-500/10 text-red-400 border border-red-500/20
                                                                    @elseif($type === 'ujian') bg-amber-500/10 text-amber-400 border border-amber-500/20
                                                                    @elseif($type === 'raport_pts') bg-violet-500/10 text-violet-400 border border-violet-500/20
                                                                    @elseif($type === 'raport_aas') bg-pink-500/10 text-pink-400 border border-pink-500/20
                                                                    @else bg-primary/10 text-primary border border-primary/20
                                                                    @endif">
                                            {{ $item->keterangan ?? $item->kategori }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-gray-500">Belum ada data kegiatan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Legend -->
        <div
            class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-2xl p-6">
            <h3 class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg mb-4">Keterangan Warna</h3>
            <div class="flex flex-wrap gap-6">
                <div class="flex items-center gap-3">
                    <span class="size-4 rounded-full bg-primary"></span>
                    <span class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Kegiatan
                        Sekolah</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="size-4 rounded-full bg-amber-500"></span>
                    <span class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Ujian /
                        Penilaian</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="size-4 rounded-full bg-violet-500"></span>
                    <span class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Pembagian Raport
                        PTS</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="size-4 rounded-full bg-pink-500"></span>
                    <span class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Pembagian Raport
                        AAS</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="size-4 rounded-full bg-red-500"></span>
                    <span class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Hari Libur</span>
                </div>
            </div>
        </div>

        <!-- Download Section -->
        <div
            class="bg-gradient-to-r from-primary/10 to-emerald-900/20 border border-primary/30 rounded-2xl p-8 flex flex-col gap-6">
            <div class="flex items-center gap-4">
                <div class="size-16 rounded-2xl bg-primary/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-3xl">download</span>
                </div>
                <div>
                    <h3 class="text-text-primary-light dark:text-text-primary-dark font-bold text-xl">Unduh Kalender
                        Akademik</h3>
                    <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">Dapatkan versi lengkap
                        dalam format PDF</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('akademik.kalender.download') }}"
                    class="flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white font-bold rounded-full hover:brightness-110 transition-all">
                    <span class="material-symbols-outlined">table_view</span>
                    Download Tabel PDF
                </a>
                <a href="{{ route('akademik.kalender.download-visual') }}"
                    class="flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white font-bold rounded-full hover:brightness-110 transition-all">
                    <span class="material-symbols-outlined">calendar_month</span>
                    Download Kalender Visual
                </a>
            </div>
        </div>

    </div>
</div>