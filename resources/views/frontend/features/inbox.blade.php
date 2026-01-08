<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox - Absensi Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <style>
        body {
            font-family: 'Lexend', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-[#102212] min-h-screen text-[#0d1b0f] dark:text-white">
    <div class="max-w-md mx-auto min-h-screen flex flex-col">
        <!-- Header -->
        <header class="p-4 flex items-center gap-4 bg-white dark:bg-[#1a2e1d] shadow-sm">
            <a href="{{ route('dashboard.index') }}" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="font-bold text-lg">Kotak Masuk</h1>
        </header>

        <!-- Content -->
        <main class="flex-1 p-6 flex flex-col items-center justify-center text-center gap-4">
            <div
                class="size-20 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-500 mb-2">
                <span class="material-symbols-outlined text-4xl">mail</span>
            </div>
            <h2 class="text-xl font-bold">Belum ada pesan</h2>
            <p class="text-gray-500 dark:text-gray-400">Notifikasi dan pesan penting akan muncul di sini.</p>
        </main>
    </div>
</body>

</html>