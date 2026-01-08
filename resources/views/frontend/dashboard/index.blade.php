<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Absensi Digital - Dashboard</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&amp;display=swap" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec25",
                        "background-light": "#f6f8f6",
                        "background-dark": "#102212",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1a2e1d",
                        "text-main": "#0d1b0f",
                        "text-sub": "#4c9a52",
                    },
                    fontFamily: {
                        "display": ["Lexend", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .material-symbols-outlined.filled {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>

<body
    class="bg-background-light dark:bg-background-dark text-text-main dark:text-gray-100 min-h-screen flex flex-col font-display overflow-x-hidden"
    x-data="{ sidebarOpen: false }">
    <!-- Top Navigation Bar -->
    <header
        class="sticky top-0 z-50 w-full bg-surface-light dark:bg-surface-dark border-b border-gray-100 dark:border-gray-800 shadow-sm">
        <div class="max-w-[960px] mx-auto px-4 md:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <span class="material-symbols-outlined text-text-main dark:text-white">menu</span>
                </button>
                <div class="flex items-center gap-2">
                    <div class="size-8 rounded-lg bg-primary/20 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined filled text-xl">school</span>
                    </div>
                    <h1 class="text-lg font-bold tracking-tight text-text-main dark:text-white hidden sm:block">Absensi
                        Digital</h1>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <!-- Notification Bell -->
                <button class="relative p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <span class="material-symbols-outlined filled text-gray-500 dark:text-gray-400">notifications</span>
                    <span
                        class="absolute top-2 right-2 size-2.5 bg-red-500 rounded-full border-2 border-surface-light dark:border-surface-dark"></span>
                </button>

                <div class="flex items-center gap-3 pl-3 border-l border-gray-200 dark:border-gray-700">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-text-main dark:text-white leading-tight">{{ $user->name }}</p>
                        <p class="text-[11px] font-semibold text-primary tracking-wide">
                            {{ $subText != '-' ? $subText : $roleText }}</p>
                    </div>
                    <div
                        class="size-10 rounded-full bg-gray-200 overflow-hidden border-2 border-primary ring-2 ring-primary/20">
                        @php
                            $photoPath = $student->photo ?? ($teacher->photo ?? null);
                            $photoUrl = $photoPath ? asset('storage/' . $photoPath) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=13ec25&color=0d1b0f';
                        @endphp
                        <img alt="Profile" class="w-full h-full object-cover" src="{{ $photoUrl }}" />
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar / Hamburger Menu -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-50 flex" style="display: none;">
        <div @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
        <div
            class="relative w-64 h-full bg-surface-light dark:bg-surface-dark shadow-xl flex flex-col p-6 transition-transform">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold text-text-main dark:text-white">Menu</h2>
                <button @click="sidebarOpen = false" class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <nav class="flex flex-col gap-2">
                <a href="{{ route('dashboard.index') }}"
                    class="flex items-center gap-3 p-3 rounded-xl bg-primary/10 text-primary font-medium">
                    <span class="material-symbols-outlined filled">home</span>
                    Beranda
                </a>
                <a href="{{ route('riwayat.index') }}"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 text-gray-600 dark:text-gray-300 font-medium transition-colors">
                    <span class="material-symbols-outlined">history</span>
                    Riwayat
                </a>
                <a href="{{ route('inbox.index') }}"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 text-gray-600 dark:text-gray-300 font-medium transition-colors">
                    <span class="material-symbols-outlined">mail</span>
                    Inbox
                </a>
                <a href="{{ route('profil.user') }}"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 text-gray-600 dark:text-gray-300 font-medium transition-colors">
                    <span class="material-symbols-outlined">person</span>
                    Profil
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/10 text-red-600 font-medium transition-colors text-left">
                        <span class="material-symbols-outlined">logout</span>
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </div>

    <main class="flex-1 w-full max-w-[960px] mx-auto p-4 md:p-8 flex flex-col gap-8 pb-24">
        <!-- Welcome Section -->
        <section class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <p class="text-primary font-semibold mb-1 tracking-wide">Selamat Pagi,</p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-text-main dark:text-white tracking-tight">
                    {{ $user->name }}</h2>
                <div class="flex items-center gap-3 mt-3">
                    <span
                        class="px-3 py-1.5 rounded-lg bg-primary/20 text-green-800 dark:text-green-300 text-xs font-bold uppercase tracking-wider">
                        {{ $roleText == 'Siswa' ? 'NIS: ' . ($student->nis_lokal ?? '-') : 'NIP: ' . ($teacher->nip ?? '-') }}
                    </span>
                    <span
                        class="px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-xs font-bold uppercase tracking-wider">
                        {{ $subText }}
                    </span>
                </div>
            </div>
            <div class="hidden md:block">
                <a href="{{ route('scan.index') }}"
                    class="flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-surface-dark border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md transition-all group">
                    <span
                        class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">qr_code_scanner</span>
                    <span class="text-sm font-bold text-text-main dark:text-white">Scan QR Code</span>
                </a>
            </div>
        </section>

        <!-- Hero Card: Clock & Date -->
        <section class="w-full">
            <div class="relative w-full rounded-[2rem] overflow-hidden shadow-xl shadow-primary/10 group">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAJsuNZUKMNtYZFbro1uvm_Tf2VqZ53V7WX3oEqv6owZMbTEHH15wJWF3z3mBtMlUQXIqoQq5GDDHH2B2CyfUpnpT-tNAqzlsOJDC7y4pRHiWF-sDAQoSBHws2KPmKilI2g3xqLUFRPgJgE0JVKmUp-QBMUGSUFDEiKCs1n-Nqm7_mOpAoKqPb_Zk25E2xtDyg0GfxkUYBkSpvdsG7wdJQsLnD2I5SohsyKIaG2s8VB4XsBFlUA_gsxhtSmyfPys604XYVX1FKykEg");'>
                </div>
                <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/60 to-black/20"></div>
                <div class="relative p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="flex flex-col gap-3 w-full md:w-auto">
                        <div class="flex items-center gap-2 text-white/90">
                            <span class="material-symbols-outlined filled text-primary text-2xl">schedule</span>
                            <span class="text-sm font-bold uppercase tracking-wider">Waktu Sekarang</span>
                        </div>
                        <h3 class="text-6xl md:text-7xl font-bold text-white tracking-tighter tabular-nums leading-none"
                            id="realtime-clock">--:--</h3>
                        <p class="text-white/80 font-medium text-lg mt-1" id="current-date">--</p>
                    </div>

                    <!-- Absen Button (Desktop) -->
                    <div class="hidden md:block">
                        <a href="{{ route('scan.index') }}"
                            class="flex items-center gap-3 bg-primary hover:bg-[#0fd620] text-text-main px-8 py-4 rounded-2xl transition-all hover:scale-105 hover:shadow-[0_0_30px_rgba(19,236,37,0.5)] active:scale-95 group">
                            <span
                                class="material-symbols-outlined text-3xl transition-transform group-hover:rotate-12">fingerprint</span>
                            <span class="text-lg font-bold">Absen Masuk</span>
                        </a>
                    </div>

                    <!-- Absen Button (Mobile) -->
                    <div class="w-full md:hidden mt-4">
                        <a href="{{ route('scan.index') }}"
                            class="w-full flex items-center justify-center gap-2 bg-primary hover:bg-[#0fd620] text-text-main text-lg font-bold px-8 py-4 rounded-xl transition-all shadow-[0_0_20px_rgba(19,236,37,0.4)] active:scale-95">
                            <span class="material-symbols-outlined">fingerprint</span>
                            <span>Absen Masuk</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Grid & Menu Cepat & Summary -->
        <div class="flex flex-col gap-6">
            <!-- Today's Stats -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Masuk -->
                <div
                    class="bg-surface-light dark:bg-surface-dark p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center justify-between group hover:border-primary/30 transition-colors">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mb-1">
                            Masuk</p>
                        <p class="text-2xl font-bold text-text-main dark:text-white tabular-nums">
                            {{ $todayAttendance ? \Carbon\Carbon::parse($todayAttendance->time_in)->format('H:i') : '--:--' }}
                        </p>
                    </div>
                    <div
                        class="size-12 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined">login</span>
                    </div>
                </div>
                <!-- Pulang -->
                <div
                    class="bg-surface-light dark:bg-surface-dark p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center justify-between group hover:border-orange-500/30 transition-colors">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mb-1">
                            Pulang</p>
                        <p class="text-2xl font-bold text-gray-400 dark:text-gray-500 tabular-nums">
                            {{ ($todayAttendance && $todayAttendance->time_out) ? \Carbon\Carbon::parse($todayAttendance->time_out)->format('H:i') : '--:--' }}
                        </p>
                    </div>
                    <div
                        class="size-12 rounded-full bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center text-orange-600 dark:text-orange-400 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined">logout</span>
                    </div>
                </div>
                <!-- Status -->
                <div
                    class="bg-surface-light dark:bg-surface-dark p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center justify-between group hover:border-blue-500/30 transition-colors">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mb-2">
                            Status</p>
                        @if($todayAttendance)
                            <div
                                class="inline-flex items-center gap-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-3 py-1 rounded-full">
                                <span class="w-2 h-2 rounded-full bg-green-600"></span>
                                <p class="text-xs font-bold uppercase">{{ $todayAttendance->status }}</p>
                            </div>
                        @else
                            <div
                                class="inline-flex items-center gap-2 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 px-3 py-1 rounded-full">
                                <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                <p class="text-xs font-bold uppercase">Belum Absen</p>
                            </div>
                        @endif
                    </div>
                    <div
                        class="size-12 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined filled">verified</span>
                    </div>
                </div>
            </section>

            <!-- Bottom Section Grid -->
            <section class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Menu Cepat (Left, 4 cols) -->
                <div
                    class="lg:col-span-4 bg-surface-light dark:bg-surface-dark p-6 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm">
                    <h3 class="text-lg font-bold text-text-main dark:text-white mb-6">Menu Cepat</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('izin.index') }}"
                            class="flex flex-col items-center justify-center gap-3 p-4 py-6 rounded-2xl bg-purple-50 dark:bg-purple-900/10 hover:bg-purple-100 dark:hover:bg-purple-900/20 transition-all group">
                            <div
                                class="size-14 rounded-full bg-white dark:bg-white/10 text-purple-600 dark:text-purple-300 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-2xl">description</span>
                            </div>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-200">Izin/Sakit</span>
                        </a>
                        <a href="{{ route('riwayat.index') }}"
                            class="flex flex-col items-center justify-center gap-3 p-4 py-6 rounded-2xl bg-blue-50 dark:bg-blue-900/10 hover:bg-blue-100 dark:hover:bg-blue-900/20 transition-all group">
                            <div
                                class="size-14 rounded-full bg-white dark:bg-white/10 text-blue-600 dark:text-blue-300 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-2xl">history</span>
                            </div>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-200">Riwayat</span>
                        </a>
                        <a href="{{ route('jadwal.index') }}"
                            class="flex flex-col items-center justify-center gap-3 p-4 py-6 rounded-2xl bg-orange-50 dark:bg-orange-900/10 hover:bg-orange-100 dark:hover:bg-orange-900/20 transition-all group">
                            <div
                                class="size-14 rounded-full bg-white dark:bg-white/10 text-orange-600 dark:text-orange-300 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-2xl">calendar_month</span>
                            </div>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-200">Jadwal</span>
                        </a>
                        <a href="{{ route('tugas.index') }}"
                            class="flex flex-col items-center justify-center gap-3 p-4 py-6 rounded-2xl bg-teal-50 dark:bg-teal-900/10 hover:bg-teal-100 dark:hover:bg-teal-900/20 transition-all group">
                            <div
                                class="size-14 rounded-full bg-white dark:bg-white/10 text-teal-600 dark:text-teal-300 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-2xl">assignment</span>
                            </div>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-200">Tugas</span>
                        </a>
                    </div>
                </div>

                <!-- Weekly Timeline (Right, 8 cols) -->
                <div
                    class="lg:col-span-8 bg-surface-light dark:bg-surface-dark p-6 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-lg font-bold text-text-main dark:text-white">Rekap Minggu Ini</h3>
                        <a class="text-sm font-bold text-primary hover:text-green-600 transition-colors cursor-pointer">Lihat
                            Semua</a>
                    </div>

                    <div class="flex-1 flex items-center">
                        <div class="w-full grid grid-cols-6 gap-2 sm:gap-4 text-center">
                            @foreach($timeline as $day => $data)
                                <div class="flex flex-col items-center gap-3">
                                    <span
                                        class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">{{ $day }}</span>
                                    <div class="size-12 sm:size-14 rounded-2xl flex items-center justify-center text-2xl shadow-sm transition-transform hover:scale-105
                                        {{ $data['color'] == 'green' ? 'bg-green-100 dark:bg-green-900/30 text-green-600' : '' }}
                                        {{ $data['color'] == 'yellow' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600' : '' }}
                                        {{ $data['color'] == 'red' ? 'bg-red-100 dark:bg-red-900/30 text-red-600' : '' }}
                                        {{ $data['color'] == 'blue' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600' : '' }}
                                        {{ $data['color'] == 'gray' ? 'bg-gray-50 dark:bg-white/5 text-gray-300' : '' }}
                                    ">
                                        @if($data['color'] == 'green') <span class="material-symbols-outlined">check</span>
                                        @elseif($data['color'] == 'yellow') <span
                                            class="material-symbols-outlined">sick</span>
                                        @elseif($data['color'] == 'red') <span
                                            class="material-symbols-outlined">close</span>
                                        @elseif($data['color'] == 'blue') <span
                                            class="material-symbols-outlined">history_edu</span>
                                        @else <span class="material-symbols-outlined">remove</span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] sm:text-xs font-bold
                                        {{ $data['color'] == 'green' ? 'text-green-600' : '' }}
                                        {{ $data['color'] == 'yellow' ? 'text-yellow-600' : '' }}
                                        {{ $data['color'] == 'red' ? 'text-red-600' : '' }}
                                        {{ $data['color'] == 'blue' ? 'text-blue-600' : '' }}
                                        {{ $data['color'] == 'gray' ? 'text-gray-300' : '' }}
                                    ">{{ $data['label'] != '-' ? $data['label'] : '--' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Bottom Navigation Bar (Sticky Footer) -->
    <div
        class="fixed bottom-0 left-0 w-full bg-surface-light dark:bg-surface-dark border-t border-gray-100 dark:border-gray-800 pb-safe md:pb-0 z-40 shadow-[0_-5px_20px_-10px_rgba(0,0,0,0.05)]">
        <div class="max-w-[960px] mx-auto px-6">
            <nav class="flex justify-between items-end h-[72px] pb-3">
                <a class="flex-1 flex flex-col items-center justify-end gap-1.5 group h-full pb-1"
                    href="{{ route('dashboard.index') }}">
                    <span
                        class="material-symbols-outlined filled text-primary text-[28px] group-hover:scale-110 transition-transform drop-shadow-sm">home</span>
                    <span class="text-[10px] font-bold text-text-main dark:text-white">Home</span>
                </a>

                <a class="flex-1 flex flex-col items-center justify-end gap-1.5 group h-full pb-1 text-gray-400 dark:text-gray-500 hover:text-text-main dark:hover:text-white transition-colors"
                    href="{{ route('riwayat.index') }}">
                    <span
                        class="material-symbols-outlined text-[28px] group-hover:scale-110 transition-transform">history</span>
                    <span class="text-[10px] font-bold">History</span>
                </a>

                <!-- Floating Action Button for Scan QR -->
                <div class="relative -top-8 px-2">
                    <a href="{{ route('scan.index') }}"
                        class="size-[72px] rounded-full bg-[#1a1a1a] dark:bg-white text-white dark:text-[#1a1a1a] shadow-xl shadow-black/20 flex items-center justify-center hover:scale-105 active:scale-95 transition-all ring-8 ring-white dark:ring-[#102212]">
                        <span class="material-symbols-outlined text-[32px]">qr_code_scanner</span>
                    </a>
                </div>

                <a class="flex-1 flex flex-col items-center justify-end gap-1.5 group h-full pb-1 text-gray-400 dark:text-gray-500 hover:text-text-main dark:hover:text-white transition-colors"
                    href="{{ route('inbox.index') }}">
                    <span
                        class="material-symbols-outlined text-[28px] group-hover:scale-110 transition-transform">mail</span>
                    <span class="text-[10px] font-bold">Inbox</span>
                </a>

                <a class="flex-1 flex flex-col items-center justify-end gap-1.5 group h-full pb-1 text-gray-400 dark:text-gray-500 hover:text-text-main dark:hover:text-white transition-colors"
                    href="{{ route('profil.user') }}">
                    <span
                        class="material-symbols-outlined text-[28px] group-hover:scale-110 transition-transform">person</span>
                    <span class="text-[10px] font-bold">Profile</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Clock Script -->
    <script>
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            const dateString = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });

            document.getElementById('realtime-clock').textContent = timeString;
            document.getElementById('current-date').textContent = dateString;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>

</html>