<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Riwayat Absensi</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&amp;display=swap" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .font-display {
            font-family: 'Lexend', sans-serif;
        }
    </style>
</head>

<body class="bg-[#f6f8f6] text-[#0d1b0f] min-h-screen flex flex-col font-display">
    <header class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-md mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('dashboard.index') }}" class="p-2 -ml-2 rounded-full hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="font-bold text-lg">Riwayat Absensi</h1>
            <div class="w-10"></div>
        </div>
    </header>

    <main class="flex-1 w-full max-w-md mx-auto pt-24 px-4 flex flex-col items-center justify-center text-center gap-4">
        <div class="size-24 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mb-4">
            <span class="material-symbols-outlined text-5xl">history</span>
        </div>
        <h2 class="text-2xl font-bold">Fitur Segera Hadir</h2>
        <p class="text-gray-500">Halaman riwayat absensi detil sedang dalam pengembangan.</p>
    </main>
</body>

</html>