<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Monitor QR Absensi</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- QRCode JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        body {
            font-family: 'Lexend', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-6" x-data="qrMonitor()">

    <div
        class="max-w-4xl w-full bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-gray-700 flex flex-col md:flex-row">

        <!-- Left Side: Info & Clock -->
        <div
            class="w-full md:w-1/2 p-10 flex flex-col justify-between bg-gradient-to-br from-green-600 to-green-900 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 rounded-full bg-black/20 blur-3xl"></div>

            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Absensi Digital</h1>
                <p class="text-green-100 text-lg">Silakan scan QR Code di samping untuk melakukan absensi Masuk/Pulang.
                </p>
            </div>

            <div class="mt-8">
                <!-- Dynamic School Name -->
                <h2 class="text-2xl font-bold text-white mb-2 uppercase">{{ $profile->nama_madrasah ?? 'Madrasah App' }}
                </h2>

                <div class="text-7xl font-black text-white tracking-tighter" x-text="time">00:00</div>
                <div class="text-xl text-green-100 font-medium mt-2" x-text="date">Senin, 1 Januari 2024</div>
            </div>

            <div class="mt-10 flex items-center gap-3 text-sm text-green-200">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                <span>System Online • Auto Refresh (30s)</span>
            </div>

            <!-- Hardcoded Footer -->
            <div class="mt-auto pt-8">
                <p class="text-white font-bold text-lg tracking-wide opacity-90">KKMI Sukmajaya</p>
            </div>
        </div>

        <!-- Right Side: QR Code -->
        <div class="w-full md:w-1/2 p-10 flex flex-col items-center justify-center bg-white text-gray-900 relative">
            <div class="relative group">
                <!-- QR Container -->
                <div id="qrcode" class="p-4 bg-white rounded-2xl shadow-lg border-2 border-gray-100"></div>

                <!-- Loading Overlay -->
                <div x-show="loading"
                    class="absolute inset-0 bg-white/80 flex items-center justify-center backdrop-blur-sm rounded-2xl">
                    <div class="w-8 h-8 border-4 border-green-500 border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>

            <p class="mt-6 text-gray-400 text-sm font-medium text-center">
                QR Code berubah secara otomatis<br>setiap 30 detik demi keamanan.
            </p>

            <!-- ProgressBar -->
            <div class="w-48 h-1.5 bg-gray-200 rounded-full mt-6 overflow-hidden">
                <div class="h-full bg-green-500 transition-all duration-1000 ease-linear"
                    :style="'width: ' + progress + '%'"></div>
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
                timer: null,
                refreshInterval: 30, // seconds
                timeLeft: 30,

                init() {
                    this.startClock();
                    this.fetchQr();

                    // Countdown timer for progress bar
                    setInterval(() => {
                        this.timeLeft--;
                        this.progress = (this.timeLeft / this.refreshInterval) * 100;

                        if (this.timeLeft <= 0) {
                            this.fetchQr();
                        }
                    }, 1000);
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
                        .finally(() => {
                            // Delay hiding loader slightly for effect
                            setTimeout(() => { this.loading = false; }, 500);
                        });
                },

                renderQr(text) {
                    const container = document.getElementById("qrcode");
                    container.innerHTML = ""; // Clear previous
                    new QRCode(container, {
                        text: text,
                        width: 256,
                        height: 256,
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