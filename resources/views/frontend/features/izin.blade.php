<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Izin/Sakit</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
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
    <div class="max-w-md mx-auto min-h-screen flex flex-col bg-white dark:bg-[#1a2e1d] shadow-xl">

        <!-- Header -->
        <header class="p-4 flex items-center gap-4 border-b border-gray-100 dark:border-gray-800">
            <a href="{{ route('dashboard.index') }}"
                class="p-2 -ml-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="font-bold text-lg">Buat Permohonan</h1>
        </header>

        <!-- Form -->
        <main class="flex-1 p-6">
            <form action="{{ route('izin.store') }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col gap-5">
                @csrf

                <!-- Type Selection -->
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Jenis
                        Permohonan</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="sakit" class="peer sr-only" checked>
                            <div
                                class="p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-purple-500 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 text-center transition-all">
                                <span
                                    class="material-symbols-outlined text-3xl mb-1 text-gray-400 peer-checked:text-purple-600">sick</span>
                                <p class="font-bold text-sm text-gray-500 peer-checked:text-purple-700">Sakit</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="izin" class="peer sr-only">
                            <div
                                class="p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 text-center transition-all">
                                <span
                                    class="material-symbols-outlined text-3xl mb-1 text-gray-400 peer-checked:text-blue-600">description</span>
                                <p class="font-bold text-sm text-gray-500 peer-checked:text-blue-700">Izin</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Date Range -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">Dari
                            Tanggal</label>
                        <input type="date" name="start_date" required
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-black/20 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">Sampai
                            Tanggal</label>
                        <input type="date" name="end_date" required
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-black/20 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                </div>

                <!-- Reason -->
                <div>
                    <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">Alasan /
                        Keterangan</label>
                    <textarea name="reason" rows="4" required placeholder="Tuliskan alasan lengkap..."
                        class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-black/20 focus:ring-purple-500 focus:border-purple-500"></textarea>
                </div>

                <!-- Attachment -->
                <div>
                    <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">Lampiran (Surat
                        Dokter/Foto)</label>
                    <input type="file" name="attachment" accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition-all">
                    <p class="mt-1 text-xs text-gray-400">Format: JPG/PNG, Max 2MB.</p>
                </div>

                <div class="flex-1"></div>

                <button type="submit"
                    class="w-full bg-[#13ec25] hover:bg-[#0fd620] text-[#0d1b0f] font-bold py-4 rounded-xl shadow-lg shadow-green-500/20 active:scale-95 transition-all mt-6">
                    Kirim Permohonan
                </button>
            </form>
        </main>
    </div>
</body>

</html>