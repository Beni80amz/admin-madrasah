<div class="min-h-screen bg-background-light dark:bg-background-dark">
    {{-- Hero Section --}}
    <div
        class="relative bg-gradient-to-br from-primary via-primary-dark to-emerald-800 pt-32 pb-20 px-5 md:px-10 lg:px-20 overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-0 left-0 w-72 h-72 bg-white rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2">
            </div>
            <div
                class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl translate-x-1/3 translate-y-1/3">
            </div>
        </div>

        <div class="max-w-[1400px] mx-auto relative z-10 text-center">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-white text-sm font-medium mb-6">
                <span class="material-symbols-outlined text-lg">school</span>
                Tahun Ajaran {{ $ppdbInfo['tahun_ajaran'] }}
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-4">
                Penerimaan Peserta Didik Baru
            </h1>
            <p class="text-xl text-white/80 max-w-2xl mx-auto mb-8">
                {{ $siteProfile->nama_madrasah ?? 'Madrasah' }} membuka pendaftaran siswa baru.
                Daftarkan putra/putri Anda sekarang!
            </p>

            @if($ppdbInfo['active'])
                <a href="{{ route('ppdb.register') }}"
                    class="inline-flex items-center gap-3 px-8 py-4 bg-white text-primary font-bold text-lg rounded-full hover:bg-white/90 transition-all shadow-xl shadow-black/20 hover:scale-105">
                    <span class="material-symbols-outlined">edit_document</span>
                    Daftar Sekarang
                </a>
            @else
                <div
                    class="inline-flex items-center gap-3 px-8 py-4 bg-white/20 text-white font-bold text-lg rounded-full cursor-not-allowed">
                    <span class="material-symbols-outlined">lock</span>
                    Pendaftaran Belum Dibuka
                </div>
            @endif
        </div>
    </div>

    <div class="max-w-[1200px] mx-auto px-6 md:px-10 lg:px-16 py-16">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 -mt-10 mb-10 relative z-20">
            <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-6 shadow-xl border border-border-light dark:border-border-dark text-center">
                <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-primary text-3xl">group_add</span>
                </div>
                <div class="text-3xl font-bold text-primary mb-2">{{ $totalRegistrations }}</div>
                <div class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Pendaftar</div>
            </div>
            <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-6 shadow-xl border border-border-light dark:border-border-dark text-center">
                <div class="w-14 h-14 bg-blue-500/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-blue-500 text-3xl">groups</span>
                </div>
                <div class="text-3xl font-bold text-blue-500 mb-2">{{ $ppdbInfo['kuota'] }}</div>
                <div class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Kuota</div>
            </div>
            <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-6 shadow-xl border border-border-light dark:border-border-dark text-center">
                <div class="w-14 h-14 bg-amber-500/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-amber-500 text-3xl">payments</span>
                </div>
                <div class="text-2xl font-bold text-amber-500 mb-2">{{ $ppdbInfo['biaya'] }}</div>
                <div class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Biaya</div>
            </div>
            <div class="bg-surface-light dark:bg-surface-dark rounded-2xl p-6 shadow-xl border border-border-light dark:border-border-dark text-center">
                <div class="w-14 h-14 bg-violet-500/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-violet-500 text-3xl">event</span>
                </div>
                <div class="text-xl font-bold text-violet-500 mb-2">
                    {{ \Carbon\Carbon::parse($ppdbInfo['period']['end'])->format('d M Y') }}
                </div>
                <div class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Batas Daftar</div>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="grid grid-cols-2 gap-4 mb-16 bg-surface-light dark:bg-surface-dark p-4 rounded-2xl border border-border-light dark:border-border-dark">
            <button wire:click="setTab('informasi')"
                class="px-6 py-4 rounded-xl font-semibold transition-all flex items-center justify-center gap-3 {{ $activeTab === 'informasi' ? 'bg-primary text-white shadow-lg' : 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-gray-100 dark:hover:bg-white/5' }}">
                <span class="material-symbols-outlined text-2xl">info</span>
                <span>Informasi PPDB</span>
            </button>
            <button wire:click="setTab('peserta')"
                class="px-6 py-4 rounded-xl font-semibold transition-all flex items-center justify-center gap-3 {{ $activeTab === 'peserta' ? 'bg-primary text-white shadow-lg' : 'text-text-secondary-light dark:text-text-secondary-dark hover:bg-gray-100 dark:hover:bg-white/5' }}">
                <span class="material-symbols-outlined text-2xl">list_alt</span>
                <span>Daftar Peserta</span>
            </button>
        </div>

        {{-- Tab Content --}}
        @if($activeTab === 'informasi')
            <div class="space-y-10 animate-fade-in">
                {{-- Jadwal Pendaftaran --}}
                <div
                    class="bg-surface-light dark:bg-surface-dark rounded-2xl p-6 md:p-8 border border-border-light dark:border-border-dark">
                    <h2
                        class="text-2xl font-bold text-text-primary-light dark:text-text-primary-dark mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-3xl">calendar_month</span>
                        Jadwal Pendaftaran
                    </h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div
                            class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
                            <div
                                class="text-sm font-medium text-green-600 dark:text-green-400 uppercase tracking-wider mb-2">
                                Mulai Pendaftaran</div>
                            <div class="text-2xl font-bold text-green-700 dark:text-green-300">
                                {{ \Carbon\Carbon::parse($ppdbInfo['period']['start'])->translatedFormat('d F Y') }}
                            </div>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-6 border border-red-200 dark:border-red-800">
                            <div class="text-sm font-medium text-red-600 dark:text-red-400 uppercase tracking-wider mb-2">
                                Batas Pendaftaran</div>
                            <div class="text-2xl font-bold text-red-700 dark:text-red-300">
                                {{ \Carbon\Carbon::parse($ppdbInfo['period']['end'])->translatedFormat('d F Y') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alur Pendaftaran --}}
                <div
                    class="bg-surface-light dark:bg-surface-dark rounded-2xl p-6 md:p-8 border border-border-light dark:border-border-dark">
                    <h2
                        class="text-2xl font-bold text-text-primary-light dark:text-text-primary-dark mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-3xl">timeline</span>
                        Alur Pendaftaran
                    </h2>
                    <div class="relative">
                        {{-- Timeline Line --}}
                        <div
                            class="absolute left-6 top-0 bottom-0 w-0.5 bg-gradient-to-b from-primary via-primary to-primary/30 hidden md:block">
                        </div>

                        <div class="space-y-8">
                            @foreach($ppdbInfo['alur'] as $index => $step)
                                <div class="flex gap-5 md:gap-6 items-start">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center font-bold text-lg shadow-lg shadow-primary/30 z-10">
                                        {{ $step['step'] }}
                                    </div>
                                    <div
                                        class="flex-1 bg-gray-50 dark:bg-white/5 rounded-xl p-6 border border-border-light dark:border-border-dark">
                                        <h3 class="font-bold text-lg text-text-primary-light dark:text-text-primary-dark mb-3">
                                            {{ $step['title'] }}</h3>
                                        <p class="text-text-secondary-light dark:text-text-secondary-dark leading-relaxed">
                                            {{ $step['description'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Persyaratan --}}
                <div
                    class="bg-surface-light dark:bg-surface-dark rounded-2xl p-6 md:p-8 border border-border-light dark:border-border-dark">
                    <h2
                        class="text-2xl font-bold text-text-primary-light dark:text-text-primary-dark mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-3xl">checklist</span>
                        Persyaratan Pendaftaran
                    </h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        @foreach($ppdbInfo['persyaratan'] as $item)
                        @php
                            // Defensive: Handle legacy string data
                            if (is_string($item)) {
                                $item = ['item' => $item, 'required' => true];
                            }
                            $itemName = $item['item'];
                            $isRequired = $item['required'] ?? true;
                        @endphp
                            <div
                                class="flex items-start gap-3 bg-gray-50 dark:bg-white/5 rounded-xl p-4 border border-border-light dark:border-border-dark">
                                <span
                                    class="material-symbols-outlined {{ $isRequired ? 'text-primary' : 'text-gray-400' }} text-xl flex-shrink-0 mt-0.5">
                                    {{ $isRequired ? 'check_circle' : 'info' }}
                                </span>
                                <span class="text-text-primary-light dark:text-text-primary-dark">
                                    {{ $itemName }}
                                    @if(!$isRequired)
                                        <span class="text-xs text-text-secondary-light dark:text-text-secondary-dark ml-1">(Opsional)</span>
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- CTA --}}
                @if($ppdbInfo['active'])
                    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-2xl p-8 text-center">
                        <h3 class="text-2xl font-bold text-white mb-4">Siap Mendaftar?</h3>
                        <p class="text-white/80 mb-6 max-w-xl mx-auto">
                            Lengkapi formulir pendaftaran online sekarang. Pastikan dokumen persyaratan sudah siap.
                        </p>
                        <a href="{{ route('ppdb.register') }}"
                            class="inline-flex items-center gap-3 px-8 py-4 bg-white text-primary font-bold text-lg rounded-full hover:bg-white/90 transition-all shadow-xl">
                            <span class="material-symbols-outlined">arrow_forward</span>
                            Mulai Pendaftaran
                        </a>
                    </div>
                @endif
            </div>
        @endif

        @if($activeTab === 'peserta')
            <div class="animate-fade-in">
                <div
                    class="bg-surface-light dark:bg-surface-dark rounded-2xl border border-border-light dark:border-border-dark overflow-hidden">
                    {{-- Search --}}
                    <div class="p-6 border-b border-border-light dark:border-border-dark">
                        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                            <h2
                                class="text-xl font-bold text-text-primary-light dark:text-text-primary-dark flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary">groups</span>
                                Daftar Calon Peserta Didik
                            </h2>
                            <div class="relative w-full sm:w-72">
                                <input type="text" wire:model.live.debounce.300ms="search"
                                    placeholder="Cari nama atau no. pendaftaran..."
                                    class="w-full px-4 py-2.5 pl-10 bg-white dark:bg-background-dark border border-border-light dark:border-border-dark rounded-xl text-text-primary-light dark:text-text-primary-dark placeholder-gray-400 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none">
                                <span
                                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                            </div>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-white/5 border-b border-border-light dark:border-border-dark">
                                    <th
                                        class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider">
                                        No. Daftar</th>
                                    <th
                                        class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider">
                                        Nama Lengkap</th>
                                    <th
                                        class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider">
                                        Asal Sekolah</th>
                                    <th
                                        class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider">
                                        Tanggal Daftar</th>
                                    <th
                                        class="text-left py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-xs font-medium uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-light dark:divide-border-dark">
                                @forelse($registrations as $index => $reg)
                                                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                                                <td class="py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-sm">
                                                                    {{ $registrations->firstItem() + $index }}
                                                                </td>
                                                                <td class="py-4 px-4">
                                                                    <span
                                                                        class="font-mono text-sm text-primary font-medium">{{ $reg->no_daftar }}</span>
                                                                </td>
                                                                <td class="py-4 px-4">
                                                                    <span
                                                                        class="text-text-primary-light dark:text-text-primary-dark font-medium">{{ $reg->nama_lengkap }}</span>
                                                                </td>
                                                                <td class="py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-sm">
                                                                    {{ $reg->asal_sekolah }}{{ $reg->nama_sekolah_asal ? ' - ' . $reg->nama_sekolah_asal : '' }}
                                                                </td>
                                                                <td class="py-4 px-4 text-text-secondary-light dark:text-text-secondary-dark text-sm">
                                                                    {{ $reg->created_at->format('d M Y') }}
                                                                </td>
                                                                <td class="py-4 px-4">
                                                                    @php
                                                                        $statusColors = [
                                                                            'new' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                                                            'verified' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                                                            'accepted' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 border-green-200 dark:border-green-800',
                                                                            'rejected' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
                                                                            'enrolled' => 'bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300 border-violet-200 dark:border-violet-800',
                                                                        ];
                                                                        $statusLabels = [
                                                                            'new' => 'Baru',
                                                                            'verified' => 'Diverifikasi',
                                                                            'accepted' => 'Diterima',
                                                                            'rejected' => 'Ditolak',
                                                                            'enrolled' => 'Terdaftar',
                                                                        ];
                                                                    @endphp
                                     <span
                                                                        class="inline-flex px-3 py-1 rounded-full text-xs font-medium border {{ $statusColors[$reg->status] ?? $statusColors['new'] }}">
                                                                        {{ $statusLabels[$reg->status] ?? 'Baru' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center">
                                            <div class="flex flex-col items-center gap-3">
                                                <div
                                                    class="w-16 h-16 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                                                    <span
                                                        class="material-symbols-outlined text-gray-400 text-3xl">search_off</span>
                                                </div>
                                                <p class="text-text-secondary-light dark:text-text-secondary-dark">
                                                    @if($search)
                                                        Tidak ada hasil untuk "{{ $search }}"
                                                    @else
                                                        Belum ada peserta yang mendaftar
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($registrations->hasPages())
                        <div class="border-t border-border-light dark:border-border-dark px-6 py-4">
                            {{ $registrations->links() }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>