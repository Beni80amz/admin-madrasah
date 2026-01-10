<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Dokumen Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white max-w-md w-full rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-green-600 p-6 text-center">
            <div
                class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4 shadow-lg animate-bounce">
                <span class="material-symbols-outlined text-4xl text-green-600">verified_user</span>
            </div>
            <h1 class="text-white text-2xl font-bold">Dokumen Valid</h1>
            <!-- Nama Madrasah sebagai Link -->
            <a href="{{ url('/admin') }}"
                class="text-green-100 opacity-90 mt-2 block hover:underline hover:text-white transition-colors text-sm font-medium">
                {{ $profile->nama_madrasah ?? 'Madrasah' }}
            </a>

            <p class="text-green-100 mt-6 text-sm">Validasi Laporan Absensi Digital</p>
        </div>

        <div class="p-6 space-y-4">
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Status Dokumen</p>
                <div class="flex items-center gap-2 text-green-700 font-bold text-lg">
                    <span class="material-symbols-outlined">check_circle</span>
                    Terverifikasi
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-500 text-sm">Jenis Dokumen</span>
                    <span class="font-medium text-gray-800 text-sm">Laporan Absensi</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-500 text-sm">Tanggal Verifikasi</span>
                    <span class="font-medium text-gray-800 text-sm">{{ $verificationDate }}</span>
                </div>

                <!-- Verifikator -->
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-500 text-sm">Verifikator</span>
                    <span
                        class="font-medium text-gray-800 text-sm text-right">{{ $profile->nama_kepala_madrasah ?? '-' }}</span>
                </div>

                <!-- Tanda Tangan -->
                @if($profile->tanda_tangan_kepala_madrasah)
                    <div class="py-4 text-center border-b border-gray-100">
                        <p class="text-xs text-gray-400 mb-2">Tanda Tangan Elektronik</p>
                        <img src="{{ asset('storage/' . $profile->tanda_tangan_kepala_madrasah) }}" alt="Tanda Tangan"
                            class="h-16 mx-auto object-contain">
                    </div>
                @endif

                <div class="flex justify-between pt-1">
                    <span class="text-gray-500 text-sm">Penerbit</span>
                    <span
                        class="font-medium text-gray-800 text-sm text-right">{{ $profile->nama_madrasah ?? 'Sistem Administrasi Madrasah' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 p-4 text-center border-t border-gray-100">
            <p class="text-xs text-gray-400">© {{ date('Y') }} Absensi Digital</p>
        </div>
    </div>

</body>

</html>