<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat - {{ $siteProfile->nama_madrasah ?? 'Madrasah' }}</title>
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
                    <p class="text-sm text-gray-500">Verifikasi Surat Resmi</p>
                </div>
            </div>
            <div
                class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-700 px-4 py-2 rounded-full text-sm font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Surat Terverifikasi
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white shadow-lg p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">SURAT KETERANGAN PINDAH/KELUAR</h2>

            <div class="space-y-3 text-sm">
                <div class="flex">
                    <span class="w-40 text-gray-500">Nomor Surat</span>
                    <span class="font-medium">: {{ $siswaKeluar->nomor_surat ?? '-' }}</span>
                </div>
                <div class="flex">
                    <span class="w-40 text-gray-500">Nama Siswa</span>
                    <span class="font-medium">: {{ $siswaKeluar->nama_lengkap }}</span>
                </div>
                <div class="flex">
                    <span class="w-40 text-gray-500">NISN</span>
                    <span class="font-medium">: {{ $siswaKeluar->nisn }}</span>
                </div>
                <div class="flex">
                    <span class="w-40 text-gray-500">Kelas Terakhir</span>
                    <span class="font-medium">: {{ $siswaKeluar->kelas_terakhir }}</span>
                </div>
                <div class="flex">
                    <span class="w-40 text-gray-500">Tanggal Keluar</span>
                    <span class="font-medium">: {{ $siswaKeluar->tanggal_keluar->format('d-m-Y') }}</span>
                </div>
                @if($siswaKeluar->sekolah_tujuan)
                    <div class="flex">
                        <span class="w-40 text-gray-500">Sekolah Tujuan</span>
                        <span class="font-medium">: {{ $siswaKeluar->sekolah_tujuan }}</span>
                    </div>
                @endif
            </div>
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
        </div>

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