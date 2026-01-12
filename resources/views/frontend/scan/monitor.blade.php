<!DOCTYPE html>
<html class="dark" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Monitor QR Absensi - {{ $profile->nama_madrasah ?? 'Madrasah' }}</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300..900&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .glass {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
            position: relative;
        }

        .marquee-content {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 60s linear infinite; // Slower speed
        }

        @keyframes marquee {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(-100%, 0);
            }
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-950 text-white h-screen w-screen overflow-hidden flex flex-col" x-data="qrMonitor()">

    <!-- Main Content Area (Split Screen) -->
    <div class="flex-1 flex overflow-hidden">

        <!-- Left Side: Multimedia Carousel (65%) -->
        <div class="w-[65%] relative bg-black flex items-center justify-center overflow-hidden">
            <template x-if="slides.length > 0">
                <div class="absolute inset-0 w-full h-full">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-1000"
                            x-transition:enter-start="opacity-0 scale-105"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-1000"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0"
                            class="absolute inset-0 w-full h-full flex items-center justify-center">

                            <!-- Image Slide -->
                            <template x-if="slide.type === 'image'">
                                <img :src="slide.url" class="absolute inset-0 w-full h-full object-cover">
                            </template>

                            <!-- Video Slide -->
                            <template x-if="slide.type === 'video'">
                                <video :src="slide.url" autoplay muted loop
                                    class="absolute inset-0 w-full h-full object-cover"></video>
                            </template>

                            <!-- YouTube Slide -->
                            <template x-if="slide.type === 'youtube'">
                                <iframe
                                    :src="'https://www.youtube.com/embed/' + slide.url + '?autoplay=1&mute=1&controls=0&loop=1&playlist=' + slide.url"
                                    class="absolute inset-0 w-full h-full object-cover" frameborder="0"
                                    allow="autoplay; encrypted-media"></iframe>
                            </template>

                            <!-- Caption Overlay -->
                            <div
                                class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/80 to-transparent p-8 pt-20">
                                <h2 class="text-3xl font-bold text-white shadow-sm" x-text="slide.title"></h2>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Fallback if no slides -->
            <template x-if="slides.length === 0">
                <div class="flex flex-col items-center justify-center text-gray-500">
                    <svg class="w-24 h-24 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p class="text-xl">Tidak ada konten slide aktif.</p>
                </div>
            </template>
        </div>

        <!-- Right Side: QR & Information (35%) -->
        <div class="w-[35%] relative flex flex-col">
            <!-- Background Decoration -->
            <div class="absolute inset-0 bg-gray-900 z-0">
                <div class="absolute top-0 right-0 w-96 h-96 bg-green-600/20 rounded-full blur-3xl -mr-20 -mt-20"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl -ml-20 -mb-20">
                </div>
            </div>

            <div class="relative z-10 flex-1 flex flex-col p-8 justify-between">

                <!-- Header: School Info -->
                <div class="flex items-center gap-4 border-b border-gray-800 pb-6">
                    @if($profile->logo)
                        <img src="{{ asset('storage/' . $profile->logo) }}" class="w-16 h-16 object-contain drop-shadow-lg">
                    @else
                        <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center">
                            <span class="text-2xl font-bold text-green-500">M</span>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-white leading-tight uppercase tracking-wider">
                            {{ $profile->nama_madrasah ?? 'Madrasah Digital' }}
                        </h1>
                        <p class="text-green-400 text-sm font-medium tracking-wide">Sistem Absensi Digital Terpadu</p>
                    </div>
                </div>

                <!-- Clock Widget -->
                <div class="py-8 text-center">
                    <div class="text-8xl font-black text-white tracking-tight leading-none" x-text="time">00:00</div>
                    <div class="text-2xl text-gray-400 font-medium mt-2" x-text="date">Senin, 1 Januari 2024</div>
                </div>

                <!-- QR Code Card -->
                <div class="flex flex-col items-center">
                    <div class="relative group p-1 bg-gradient-to-br from-green-500 to-blue-600 rounded-3xl shadow-2xl">
                        <div class="bg-white p-4 rounded-[20px]">
                            <div id="qrcode" class="w-[280px] h-[280px]"></div>
                        </div>

                        <!-- Loading Overlay -->
                        <div x-show="loading"
                            class="absolute inset-0 bg-white/90 rounded-3xl flex items-center justify-center backdrop-blur-sm z-20">
                            <div
                                class="w-12 h-12 border-4 border-green-600 border-t-transparent rounded-full animate-spin">
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 w-full max-w-xs">
                        <div
                            class="flex justify-between text-xs text-gray-400 mb-2 uppercase font-semibold tracking-wider">
                            <span>Scan Me</span>
                            <span>Auto Refresh (<span x-text="timeLeft">30</span>s)</span>
                        </div>
                        <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-green-500 to-blue-500 transition-all duration-1000 ease-linear"
                                :style="'width: ' + progress + '%'"></div>
                        </div>
                    </div>
                </div>

                <!-- Connection Status -->
                <div class="mt-auto pt-6 flex justify-center items-center gap-2 text-sm text-green-500/80">
                    <span class="relative flex h-3 w-3">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    <span class="font-mono">ONLINE SERVER CONNECTED</span>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer: Running Text (Marquee) -->
    <div
        class="h-16 bg-gradient-to-r from-green-900 via-green-800 to-blue-900 border-t border-green-700/30 flex items-center z-50 shadow-[0_-5px_20px_rgba(0,0,0,0.5)]">
        <div class="bg-green-600/20 px-6 h-full flex items-center justify-center z-10 border-r border-green-500/30">
            <span
                class="bg-indigo-600 text-white text-xs font-bold px-2 py-1 rounded shadow-lg uppercase tracking-wider">Info</span>
        </div>
        <div class="marquee-container flex-1 h-full flex items-center">
            <div class="marquee-content text-xl font-medium text-green-50 tracking-wide px-4">
                {{ $profile->running_text ?? 'Selamat Datang di Sistem Informasi Madrasah Digital. Silakan lakukan scan QR Code untuk melakukan absensi.' }}
                &nbsp;&nbsp; • &nbsp;&nbsp;
                {{ $profile->running_text ?? 'Selamat Datang di Sistem Informasi Madrasah Digital. Silakan lakukan scan QR Code untuk melakukan absensi.' }}
            </div>
        </div>
        <!-- Digital Clock Small (Optional duplicate for visibility) -->
        <div class="bg-slate-900/50 px-6 h-full flex items-center z-10 border-l border-white/10 hidden md:flex">
            <div class="text-lg font-bold text-gray-300" x-text="time"></div>
        </div>
    </div>

    <script>
        function qrMonitor() {
            return {
                time: '00:00',
                date: '',
                loading: true,
                progress: 100,
                refreshInterval: 30, // seconds
                timeLeft: 30,  // Slideshow Logic
                activeSlide: 0,
                slideInterval: 10000, // 10 seconds per slide
                slides: @json($slides->map(function ($s) {
                    return [
                        'type' => $s->type,
                        'url' => $s->type === 'image' || $s->type === 'video' ? asset('storage/' . $s->file_path) : $s->url,
                        'title' => $s->title
                    ];
                })),

                init() {
                    this.startClock();
                    this.fetchQr();
                    this.startSlideshow();

                    // Countdown timer for QR
                    setInterval(() => {
                        this.timeLeft--;
                        this.progress = (this.timeLeft / this.refreshInterval) * 100;
                        if (this.timeLeft <= 0) {
                            this.fetchQr();
                        }
                    }, 1000);
                },

                startSlideshow() {
                    if (this.slides.length > 1) {
                        setInterval(() => {
                            this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                        }, this.slideInterval);
                    }
                },

                startClock() {
                    const updateTime = () => {
                        const now = new Date();
                        this.time = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
                        this.date = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                    };
                    updateTime();
                    setInterval(updateTime, 1000);
                },

                fetchQr() {
                    this.loading = true;
                    this.timeLeft = this.refreshInterval;
                    this.progress = 100;

                    fetch('{{ route("attendance.generate-qr") }}')
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                this.renderQr(data.token);
                            }
                        })
                        .catch(err => console.error(err))
                        .finally(() => { setTimeout(() => { this.loading = false; }, 500); });
                },

                renderQr(text) {
                    const container = document.getElementById("qrcode");
                    container.innerHTML = "";
                   new QRCode(container, {
                        text: text,
                        width: 280,
                        height: 280,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                }
            }
        }
    </script>
</body>

</html>