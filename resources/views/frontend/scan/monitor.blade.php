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
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
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

        /* Force generated QR code to be responsive and KEEP ASPECT RATIO */
        #qrcode img {
            width: 100% !important;
            height: 100% !important;
            object-fit: contain !important;
            display: block !important;
        }

        #qrcode canvas {
            display: none !important;
            /* Fix duplicate QR issue by hiding canvas */
        }
    </style>
</head>

<body class="bg-gray-950 text-white h-screen w-screen overflow-hidden flex flex-col" x-data="qrMonitor()">

    <!-- Fullscreen Toggle (Hidden / Floating) -->
    <button @click="toggleFullscreen"
        class="fixed top-4 right-4 z-[9999] bg-black/50 hover:bg-black/70 text-white p-2 rounded-full backdrop-blur-sm opacity-0 hover:opacity-100 transition-opacity duration-300">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
        </svg>
    </button>

    <!-- Main Content Area (Split Screen) -->
    <div class="flex-1 flex flex-col lg:flex-row overflow-hidden">

        <!-- Left Side: Multimedia Carousel (50%) -->
        <div
            class="w-full h-[35vh] lg:w-[50%] lg:h-full relative bg-black flex items-center justify-center overflow-hidden shrink-0">
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
                                <div class="absolute inset-0 w-full h-full pointer-events-none">
                                    <iframe
                                        :src="'https://www.youtube.com/embed/' + slide.url + '?autoplay=1&mute=1&controls=0&loop=1&playlist=' + slide.url + '&modestbranding=1&rel=0&showinfo=0&iv_load_policy=3'"
                                        class="absolute inset-0 w-full h-full object-cover" frameborder="0"
                                        allow="autoplay; encrypted-media"></iframe>
                                </div>
                            </template>

                            <!-- Caption Overlay -->
                            <div
                                class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/80 to-transparent p-[2vh] lg:p-[3vh] pt-[8vh]">
                                <h2 class="text-[2.5vh] lg:text-[3vh] font-bold text-white shadow-sm"
                                    x-text="slide.title"></h2>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Fallback if no slides -->
            <template x-if="slides.length === 0">
                <div class="flex flex-col items-center justify-center text-gray-500">
                    <svg class="w-[8vh] h-[8vh] lg:w-[10vh] lg:h-[10vh] mb-[2vh] opacity-50" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p class="text-[2vh]">Tidak ada konten slide aktif.</p>
                </div>
            </template>
        </div>

        <!-- Right Side: QR & Information (50%) -->
        <div class="w-full flex-1 lg:w-[50%] lg:h-full relative flex flex-col">
            <!-- Background Decoration -->
            <div class="absolute inset-0 bg-gray-900 z-0">
                <div
                    class="absolute top-0 right-0 w-[40vh] h-[40vh] bg-green-600/20 rounded-full blur-3xl -mr-[5vh] -mt-[5vh]">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-[30vh] h-[30vh] bg-blue-600/10 rounded-full blur-3xl -ml-[5vh] -mb-[5vh]">
                </div>
            </div>

            <div class="relative z-10 flex-1 flex flex-col p-[3vh] justify-between h-full">

                <!-- Header: School Info -->
                <div class="flex items-center gap-[1.5vh] border-b border-gray-800 pb-[2vh]">
                    @if($profile->logo)
                        <img src="{{ asset('storage/' . $profile->logo) }}"
                            class="w-[6vh] h-[6vh] object-contain drop-shadow-lg">
                    @else
                        <div class="w-[6vh] h-[6vh] bg-gray-800 rounded-full flex items-center justify-center">
                            <span class="text-[2.5vh] font-bold text-green-500">M</span>
                        </div>
                    @endif
                    <div>
                        <h1
                            class="text-[2.2vh] font-bold text-white leading-tight uppercase tracking-wider line-clamp-1">
                            {{ $profile->nama_madrasah ?? 'Madrasah Digital' }}
                        </h1>
                        <p class="text-green-400 text-[1.4vh] font-medium tracking-wide">Sistem Absensi Digital Terpadu
                        </p>
                    </div>
                </div>

                <!-- Clock Widget -->
                <div class="flex-1 flex flex-col justify-center items-center py-[2vh]">
                    <div class="text-[12vh] font-black text-white tracking-tight leading-none" x-text="time">00:00</div>
                    <div class="text-[2.5vh] text-gray-400 font-medium mt-[1vh]" x-text="date">Senin, 1 Januari 2024
                    </div>
                </div>

                <!-- QR Codes Container -->
                <div class="flex items-center justify-center flex-grow-[2] gap-[4vh] w-full px-[4vh]">

                    <!-- 1. Attendance QR -->
                    <div
                        class="flex flex-col items-center justify-center {{ $isPpdbActive ? 'w-1/2' : 'w-full' }} h-full">
                        <div style="aspect-ratio: 1/1;"
                            class="relative group p-[0.5vh] bg-gradient-to-br from-green-500 to-blue-600 rounded-[2vh] shadow-2xl w-full {{ $isPpdbActive ? 'max-w-[35vh]' : 'max-w-[50vh]' }}">
                            <div
                                class="bg-white p-[1.5vh] rounded-[1.5vh] w-full h-full flex items-center justify-center">
                                <div id="qrcode" class="w-full h-full"></div>
                            </div>

                            <!-- Loading Overlay -->
                            <div x-show="loading"
                                class="absolute inset-0 bg-white/90 rounded-[2vh] flex items-center justify-center backdrop-blur-sm z-20">
                                <div
                                    class="w-[5vh] h-[5vh] border-4 border-green-600 border-t-transparent rounded-full animate-spin">
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar for Attendance -->
                        <div class="mt-[2vh] w-[80%]">
                            <div class="h-[1vh] bg-gray-800 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-green-500 to-blue-500 transition-all duration-1000 ease-linear"
                                    :style="'width: ' + progress + '%'"></div>
                            </div>
                            <div
                                class="text-center text-[1.5vh] text-gray-400 mt-[0.5vh] uppercase font-bold tracking-wider">
                                Scan Absensi
                            </div>
                        </div>
                    </div>

                    @if($isPpdbActive)
                        <!-- 2. PPDB QR -->
                        <div class="flex flex-col items-center justify-center w-1/2 h-full">
                            <div style="aspect-ratio: 1/1;"
                                class="relative group p-[0.5vh] bg-gradient-to-br from-purple-500 to-pink-600 rounded-[2vh] shadow-2xl w-full max-w-[35vh]">
                                <div
                                    class="bg-white p-[1.5vh] rounded-[1.5vh] w-full h-full flex items-center justify-center">
                                    <div id="qrcode-ppdb" class="w-full h-full"></div>
                                </div>
                            </div>
                            <div class="mt-[2vh] w-[80%] text-center">
                                <div
                                    class="text-[2vh] font-bold text-white uppercase tracking-wider bg-white/10 py-[0.5vh] rounded-lg">
                                    Pendaftaran PPDB
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Connection Status -->
                <div class="pt-[2vh] flex justify-center items-center gap-[1vh] text-[1.2vh] text-green-500/80">
                    <span class="relative flex h-[1.2vh] w-[1.2vh]">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-full w-full bg-green-500"></span>
                    </span>
                    <span class="font-mono">ONLINE SERVER CONNECTED</span>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer: Running Text (Marquee) -->
    <div
        class="h-[6vh] min-h-[40px] bg-gradient-to-r from-green-900 via-green-800 to-blue-900 border-t border-green-700/30 flex items-center z-50 shadow-[0_-5px_20px_rgba(0,0,0,0.5)]">
        <div class="bg-green-600/20 px-[2vh] h-full flex items-center justify-center z-10 border-r border-green-500/30">
            <span
                class="bg-indigo-600 text-white text-[1.5vh] font-bold px-[1vh] py-[0.5vh] rounded shadow-lg uppercase tracking-wider">Info</span>
        </div>
        <div class="marquee-container flex-1 h-full flex items-center">
            <div class="marquee-content text-[2vh] font-medium text-green-50 tracking-wide px-4">
                {{ $profile->running_text ?? 'Selamat Datang di Sistem Informasi Madrasah Digital. Silakan lakukan scan QR Code untuk melakukan absensi.' }}
                &nbsp;&nbsp; • &nbsp;&nbsp;
                {{ $profile->running_text ?? 'Selamat Datang di Sistem Informasi Madrasah Digital. Silakan lakukan scan QR Code untuk melakukan absensi.' }}
            </div>
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
                    @if($isPpdbActive)
                        this.renderPpdbQr();
                    @endif
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

                    // Generate square QR code
                    new QRCode(container, {
                        text: text,
                        width: 500, // Optimized res
                        height: 500,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                },

                renderPpdbQr() {
                    const container = document.getElementById("qrcode-ppdb");
                    container.innerHTML = "";

                    new QRCode(container, {
                        text: '{{ url("/ppdb/daftar") }}',
                        width: 500, // Optimized res
                        height: 500,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                },

                toggleFullscreen() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(err => {
                            console.log(`Error attempting to enable fullscreen: ${err.message}`);
                        });
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        }
                    }
                }
            }
        }
    </script>
</body>

</html>