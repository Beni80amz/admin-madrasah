<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Absensi Digital - Login</title>
    <!-- Google Fonts: Lexend -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <!-- Material Symbols -->
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <!-- Tailwind CSS -->
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
                    },
                    fontFamily: {
                        "display": ["Lexend", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
</head>

<body class="bg-background-light dark:bg-background-dark font-display antialiased transition-colors duration-200">
    <div class="flex h-screen w-full overflow-hidden">
        <!-- Left Side: Visuals/Branding -->
        <div class="hidden lg:flex w-1/2 relative bg-background-dark items-center justify-center overflow-hidden">
            <div class="absolute inset-0 z-0 bg-cover bg-center opacity-40 mix-blend-overlay"
                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDaXN9wLc6TMwVZko8CdLudq-F4-mEj8lQXubYnP1NYDfUR5UUk7DPdAR_F2fA2TrbUFE0A1FRMeMI7-9_paztak0ZZtyDBsRkn9MiSfrbQoyGe6i-dzXotCL7vXV5-N2Bto8Km4bOmkqQ-AosFi6ZWpjhyyRWE43XsXuBELrCHMKmPLyEnb9Zq1ew-9QvHWGbf6rMxetp5qwI5KPZWrPT5AZTIkPTaXoQ01OxRrpqlwuyjtQ9FqDUJOsj_E1_XvBJLZYSPeYw8qpE');">
            </div>
            <div
                class="absolute inset-0 bg-gradient-to-tr from-background-dark via-background-dark/80 to-primary/20 z-10">
            </div>
            <div class="relative z-20 p-12 text-center max-w-lg">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-primary mb-8 shadow-[0_0_40px_-10px_rgba(19,236,37,0.6)]">
                    <span class="material-symbols-outlined text-background-dark" style="font-size: 48px;">school</span>
                </div>
                <h2 class="text-4xl font-bold text-white mb-6 tracking-tight">Selamat Datang di Absensi Digital</h2>
                <p class="text-gray-300 text-lg leading-relaxed">Kelola kehadiran, izin, dan riwayat aktivitas Anda
                    dengan mudah dalam satu aplikasi terintegrasi.</p>
                <div class="absolute -bottom-32 -right-32 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>
                <div class="absolute -top-32 -left-32 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div
            class="flex w-full lg:w-1/2 flex-col items-center justify-center bg-background-light dark:bg-background-dark p-6 sm:p-12 relative">
            <div class="w-full max-w-[440px] flex flex-col">
                <div class="flex flex-col items-center mb-10 text-center">
                    @if($profile && $profile->logo)
                        <img src="{{ asset('storage/' . $profile->logo) }}" alt="Logo"
                            class="lg:hidden w-14 h-14 rounded-xl object-contain mb-4 shadow-lg shadow-primary/20" />
                    @else
                        <div
                            class="lg:hidden w-14 h-14 rounded-xl bg-primary flex items-center justify-center mb-4 shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined text-background-dark"
                                style="font-size: 32px;">school</span>
                        </div>
                    @endif
                    <h1 class="text-slate-900 dark:text-white text-[32px] font-bold tracking-tight leading-tight">
                        ABSENSI DIGITAL</h1>
                    @if($profile && $profile->nama_madrasah)
                        <p class="text-primary text-base font-semibold mt-1">{{ $profile->nama_madrasah }}</p>
                    @endif
                    <p class="text-slate-500 dark:text-slate-400 text-base font-normal mt-2">Silakan masuk untuk
                        melanjutkan</p>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div
                        class="mb-4 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 text-sm">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="loginForm" method="POST" action="{{ route('login.post') }}" class="flex flex-col gap-5">
                    @csrf
                    <input type="hidden" name="device_id" id="device_id_input">

                    <!-- NIS/NIP Input -->
                    <div class="flex flex-col gap-2">
                        <label class="text-slate-900 dark:text-white text-sm font-medium" for="username">NIS /
                            NIP</label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 material-symbols-outlined text-[20px]">badge</span>
                            <input
                                class="w-full h-14 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-white/5 pl-12 pr-4 text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all text-base"
                                id="username" name="username" placeholder="12345678" type="text" required
                                value="{{ old('username') }}" />
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="flex flex-col gap-2">
                        <label class="text-slate-900 dark:text-white text-sm font-medium"
                            for="password">Password</label>
                        <div class="relative group">
                            <span
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 material-symbols-outlined text-[20px]">lock</span>
                            <input
                                class="w-full h-14 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-white/5 pl-12 pr-12 text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all text-base"
                                id="password" name="password" placeholder="•••••••••" type="password" required />
                        </div>
                    </div>

                    <button type="submit"
                        class="mt-4 flex w-full h-12 items-center justify-center rounded-lg bg-primary text-[#0d1b0f] text-base font-bold tracking-wide transition-all hover:brightness-105 hover:shadow-[0_0_20px_-5px_rgba(19,236,37,0.4)] active:scale-[0.98]">
                        MASUK
                    </button>
                </form>

                <div class="mt-12 text-center">
                    <p class="text-slate-400 dark:text-slate-600 text-sm">
                        Mengalami kendala saat login? <br />
                        <a class="text-slate-600 dark:text-slate-400 font-medium hover:text-primary dark:hover:text-primary transition-colors underline decoration-slate-300 dark:decoration-slate-700 underline-offset-4"
                            href="#">Hubungi Admin/Operator Madrasah</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Device ID Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function generateUUID() {
                if (typeof crypto !== 'undefined' && crypto.randomUUID) {
                    try { return crypto.randomUUID(); } catch (e) { }
                }
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                    var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            }

            let deviceId = localStorage.getItem('absensi_device_uuid');
            if (!deviceId) {
                deviceId = generateUUID();
                localStorage.setItem('absensi_device_uuid', deviceId);
            }
            const input = document.getElementById('device_id_input');
            if (input) {
                input.value = deviceId;
                console.log("Device ID bound: " + deviceId);
            }
        });
    </script>
</body>

</html>