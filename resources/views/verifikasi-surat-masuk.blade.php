<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat Penerimaan - {{ $siteProfile->nama_madrasah ?? 'Madrasah' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            min-height: 100vh;
        }
    </style>
</head>

<body class="py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-t-2xl shadow-lg p-6 text-center border-b-4 border-emerald-500">
            <div class="flex items-center justify-center gap-4 mb-4">
                @if($siteProfile->logo)
                    <img src="{{ asset('storage/' . $siteProfile->logo) }}" alt="Logo" class="w-16 h-16 object-contain">
                @endif
                <div>
                    <h1 class="text-xl font-bold text-emerald-600">{{ $siteProfile->nama_madrasah ?? 'MADRASAH' }}</h1>
                    <p class="text-sm text-gray-500">Verifikasi Surat Penerimaan</p>
                </div>
            </div>

            <!-- Status Badge -->
            @if($siswaMasuk->isPending())
                <div
                    class="inline-flex items-center gap-2 bg-amber-100 text-amber-700 px-4 py-2 rounded-full text-sm font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Menunggu Verifikasi
                </div>
            @elseif($siswaMasuk->isApproved())
                <div
                    class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-700 px-4 py-2 rounded-full text-sm font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Surat Disetujui & Terverifikasi
                </div>
            @else
                <div
                    class="inline-flex items-center gap-2 bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Permohonan Ditolak
                </div>
            @endif
        </div>

        <!-- Content -->
        <div class="bg-white shadow-lg p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">SURAT PENERIMAAN SISWA PINDAHAN</h2>

            <div class="space-y-3 text-sm">
                <div class="flex">
                    <span class="w-40 text-gray-500">Nomor Surat</span>
                    <span class="font-medium">: {{ $siswaMasuk->nomor_surat_penerimaan ?? '-' }}</span>
                </div>
                <div class="flex">
                    <span class="w-40 text-gray-500">Nama Siswa</span>
                    <span class="font-medium">: {{ $siswaMasuk->nama_lengkap }}</span>
                </div>
                @if($siswaMasuk->nisn)
                    <div class="flex">
                        <span class="w-40 text-gray-500">NISN</span>
                        <span class="font-medium">: {{ $siswaMasuk->nisn }}</span>
                    </div>
                @endif
                <div class="flex">
                    <span class="w-40 text-gray-500">Sekolah Asal</span>
                    <span class="font-medium">: {{ $siswaMasuk->sekolah_asal }}</span>
                </div>
                <div class="flex">
                    <span class="w-40 text-gray-500">Kelas Asal</span>
                    <span class="font-medium">: {{ $siswaMasuk->kelas_asal ?? '-' }}</span>
                </div>
                <div class="flex">
                    <span class="w-40 text-gray-500">Kelas Tujuan</span>
                    <span class="font-medium">: Kelas {{ $siswaMasuk->kelas_tujuan }}</span>
                </div>
                <div class="flex">
                    <span class="w-40 text-gray-500">Tanggal Masuk</span>
                    <span class="font-medium">: {{ $siswaMasuk->tanggal_masuk->format('d-m-Y') }}</span>
                </div>
                @if($siswaMasuk->verified_at)
                    <div class="flex">
                        <span class="w-40 text-gray-500">Tanggal Verifikasi</span>
                        <span class="font-medium">: {{ $siswaMasuk->verified_at->format('d-m-Y H:i') }}</span>
                    </div>
                @endif
            </div>

            @if($siswaMasuk->isRejected() && $siswaMasuk->catatan_verifikasi)
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-700"><strong>Alasan Penolakan:</strong> {{ $siswaMasuk->catatan_verifikasi }}
                    </p>
                </div>
            @endif

            @if($siswaMasuk->isApproved() && $siswaMasuk->catatan_verifikasi)
                <div class="mt-4 p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                    <p class="text-sm text-emerald-700"><strong>Catatan:</strong> {{ $siswaMasuk->catatan_verifikasi }}</p>
                </div>
            @endif
        </div>

        <!-- Kepala Madrasah Section -->
        <div class="bg-gray-50 shadow-lg p-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4">DITANDATANGANI OLEH</h3>

            <div class="text-center">
                <p class="text-sm text-gray-500 mb-2">Kepala Madrasah</p>
                <p class="font-bold text-gray-800 text-lg">{{ $siteProfile->nama_kepala_madrasah ?? '-' }}</p>
                @if($siteProfile->nip_kepala_madrasah)
                    <p class="text-sm text-gray-500">NIP. {{ $siteProfile->nip_kepala_madrasah }}</p>
                @endif
            </div>

            <!-- Tanda Tangan & Stempel -->
            @if($siswaMasuk->isApproved())
                <div class="flex justify-center gap-8 mt-6">
                    @if($siteProfile->tanda_tangan_kepala_madrasah)
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $siteProfile->tanda_tangan_kepala_madrasah) }}" alt="Tanda Tangan"
                                class="w-32 h-auto mx-auto mb-2">
                            <p class="text-xs text-gray-400">Tanda Tangan</p>
                        </div>
                    @endif

                    @if($siteProfile->stempel_madrasah)
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $siteProfile->stempel_madrasah) }}" alt="Stempel Madrasah"
                                class="w-32 h-auto mx-auto mb-2">
                            <p class="text-xs text-gray-400">Stempel Madrasah</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Status Info -->
        @if($siswaMasuk->isPending())
            <div class="bg-amber-50 shadow-lg p-4 text-center">
                <p class="text-sm text-amber-700">
                    <strong>Perhatian:</strong> Surat ini masih bersifat SEMENTARA dan menunggu verifikasi dari pihak
                    madrasah.
                </p>
            </div>
        @elseif($siswaMasuk->isApproved())
            <div class="bg-emerald-50 shadow-lg p-4 text-center">
                <p class="text-sm text-emerald-700">
                    <strong>✓ Terverifikasi:</strong> Siswa telah resmi diterima sebagai siswa aktif di
                    {{ $siteProfile->nama_madrasah ?? 'Madrasah' }}.
                </p>
            </div>
        @endif

        <!-- Footer -->
        <div class="bg-white rounded-b-2xl shadow-lg p-4 text-center">
            <p class="text-xs text-gray-400">
                Dokumen ini diterbitkan oleh {{ $siteProfile->nama_madrasah ?? 'Madrasah' }}<br>
                {{ $siteProfile->alamat ?? '' }}
            </p>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-6">
            <a href="{{ url('/') }}"
                class="inline-flex items-center gap-2 bg-white text-emerald-600 px-6 py-3 rounded-full font-medium shadow hover:shadow-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>

</html>