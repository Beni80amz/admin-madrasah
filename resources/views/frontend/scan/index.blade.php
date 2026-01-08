<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Scan Presensi</title>
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
                        "background-light": "#ffffff",
                        "background-dark": "#102212",
                        "surface-light": "#f6f8f6",
                        "surface-dark": "#1a2e1d",
                        "text-main": "#0d1b0f",
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

        [x-cloak] {
            display: none !important;
        }

        /* Green Corners for Scanner */
        .scanner-frame {
            position: relative;
        }

        .scanner-frame::before,
        .scanner-frame::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            border-color: #13ec25;
            border-style: solid;
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 20;
        }

        /* Top Left */
        .scanner-frame::before {
            top: 20px;
            left: 20px;
            border-width: 4px 0 0 4px;
            border-radius: 12px 0 0 0;
        }

        /* Top Right */
        .scanner-frame::after {
            top: 20px;
            right: 20px;
            border-width: 4px 4px 0 0;
            border-radius: 0 12px 0 0;
        }

        .scanner-frame-bottom {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 20;
        }

        .scanner-frame-bottom::before,
        .scanner-frame-bottom::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            border-color: #13ec25;
            border-style: solid;
            transition: all 0.3s ease;
        }

        /* Bottom Left */
        .scanner-frame-bottom::before {
            bottom: 20px;
            left: 20px;
            border-width: 0 0 4px 4px;
            border-radius: 0 0 0 12px;
        }

        /* Bottom Right */
        .scanner-frame-bottom::after {
            bottom: 20px;
            right: 20px;
            border-width: 0 4px 4px 0;
            border-radius: 0 0 12px 0;
        }

        #reader {
            border: none !important;
        }

        video {
            object-fit: cover;
            border-radius: 1.5rem;
        }
    </style>
</head>

<body
    class="bg-background-light dark:bg-background-dark text-text-main dark:text-gray-100 min-h-screen flex flex-col font-display overflow-x-hidden selection:bg-primary/30"
    x-data="scanApp()">

    <!-- 1. Header -->
    <header class="w-full pt-6 pb-2 px-6 flex items-center justify-between">
        <a href="{{ route('dashboard.index') }}"
            class="p-2 -ml-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <span class="material-symbols-outlined text-2xl font-bold">arrow_back</span>
        </a>
        <h1 class="font-bold text-xl text-text-main dark:text-white">Scan Presensi</h1>
        <button class="p-2 -mr-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <span class="material-symbols-outlined text-2xl">settings</span>
        </button>
    </header>

    <main class="flex-1 w-full max-w-md mx-auto px-6 flex flex-col items-center gap-6 pb-24">

        <!-- 2. Clock & Date -->
        <div class="text-center mt-4">
            <div
                class="text-6xl font-black tracking-tight text-text-main dark:text-white flex items-baseline justify-center gap-1">
                <span x-text="time">00:00</span>
            </div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1" x-text="date">Senin, 1 Januari 2024</p>
        </div>

        <!-- 3. Location Status -->
        <div class="flex flex-col items-center gap-2">
            <div class="bg-primary/10 border border-primary/20 px-4 py-2 rounded-full flex items-center gap-2"
                :class="locationValid ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-50 text-red-600 border-red-100'">
                <div class="size-5 rounded-full flex items-center justify-center"
                    :class="locationValid ? 'bg-green-500 text-white' : 'bg-red-500 text-white'">
                    <span class="material-symbols-outlined text-[14px]">check</span>
                </div>
                <span class="text-sm font-bold" x-text="locationStatus">Mencari Lokasi...</span>
            </div>
            <p class="text-xs text-gray-400 flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">my_location</span>
                Akurasi GPS: <span x-text="accuracy ? '±' + Math.round(accuracy) + 'm' : '-'">-</span>
            </p>
        </div>

        <!-- 4. Scanner Area -->
        <div
            class="w-full aspect-square bg-black rounded-[2rem] relative scanner-frame shadow-2xl overflow-hidden group">

            <!-- Scanner Content -->
            <div class="absolute inset-0 z-10 flex items-center justify-center">
                <!-- QR Mode -->
                <div x-show="mode === 'qr'" id="reader" class="w-full h-full"></div>

                <!-- Selfie Mode -->
                <video x-show="mode === 'selfie'" id="selfie-video" autoplay playsinline
                    class="w-full h-full object-cover transform -scale-x-100"></video>
                <canvas id="selfie-canvas" class="hidden"></canvas>

                <!-- Captured Image Preview -->
                <img x-show="capturedImage" :src="capturedImage"
                    class="absolute inset-0 w-full h-full object-cover z-20 transform -scale-x-100">
            </div>

            <div class="scanner-frame-bottom"></div>

            <!-- Overlay Text/Icon -->
            <div x-show="!capturedImage"
                class="absolute inset-0 z-30 flex flex-col items-center justify-center pointer-events-none">
                <!-- Toggle Mode Small Pills -->
                <div
                    class="pointer-events-auto absolute top-6 bg-black/40 backdrop-blur-md rounded-full p-1 flex border border-white/10">
                    <button @click="setMode('qr')" class="px-3 py-1 rounded-full text-xs font-bold transition-colors"
                        :class="mode === 'qr' ? 'bg-white text-black' : 'text-white/60 hover:text-white'">QR</button>
                    <button @click="setMode('selfie')"
                        class="px-3 py-1 rounded-full text-xs font-bold transition-colors"
                        :class="mode === 'selfie' ? 'bg-white text-black' : 'text-white/60 hover:text-white'">Selfie</button>
                </div>

                <div
                    class="mt-8 bg-black/60 backdrop-blur-sm px-4 py-2 rounded-xl border border-white/10 flex flex-col items-center text-center">
                    <span class="material-symbols-outlined text-white text-3xl mb-1"
                        x-text="mode === 'qr' ? 'qr_code_Scanner' : 'face'"></span>
                    <p class="text-white text-xs font-medium"
                        x-text="mode === 'qr' ? 'Arahkan ke QR Code' : 'Ambil Foto Selfie'"></p>
                </div>
            </div>

            <!-- Selfie Trigger Action -->
            <div x-show="mode === 'selfie' && !capturedImage"
                class="absolute bottom-6 w-full flex justify-center z-40 pointer-events-auto">
                <button @click="takePhoto()"
                    class="size-14 rounded-full border-4 border-white flex items-center justify-center bg-white/20 backdrop-blur-sm hover:bg-white/40 active:scale-95 transition-all">
                    <div class="size-10 bg-white rounded-full"></div>
                </button>
            </div>

            <!-- Retake Button -->
            <button x-show="capturedImage" @click="retakePhoto()"
                class="absolute top-4 right-4 z-50 p-2 rounded-full bg-black/50 text-white hover:bg-black/80 backdrop-blur-sm pointer-events-auto">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

    </main>

    <!-- 5. Bottom Navigation Buttons -->
    <div
        class="fixed bottom-0 left-0 w-full bg-white dark:bg-surface-dark border-t border-gray-100 dark:border-gray-800 p-4 z-50 rounded-t-[2rem] shadow-[0_-5px_20px_rgba(0,0,0,0.05)]">
        <div class="max-w-md mx-auto grid grid-cols-2 gap-4">

            <!-- Scan Masuk -->
            <button @click="setAction('masuk')"
                class="flex flex-col items-center justify-center gap-1.5 py-4 rounded-2xl transition-all duration-300"
                :class="activeAction === 'masuk' ? 'bg-primary text-text-main shadow-lg shadow-primary/30 scale-[1.02]' : 'bg-gray-100 dark:bg-gray-800 text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'">
                <div class="size-10 rounded-full bg-black/5 flex items-center justify-center mb-1">
                    <span class="material-symbols-outlined font-bold transform rotate-180">logout</span>
                    <!-- Login icon usually arrow pointing in, logout arrow pointing out. 'login' icon exists. 'logout' with rotate looks like 'in' -->
                </div>
                <span class="font-black text-sm tracking-wide">SCAN MASUK</span>
            </button>

            <!-- Scan Pulang -->
            <button @click="setAction('pulang')"
                class="flex flex-col items-center justify-center gap-1.5 py-4 rounded-2xl transition-all duration-300"
                :class="activeAction === 'pulang' ? 'bg-primary text-text-main shadow-lg shadow-primary/30 scale-[1.02]' : 'bg-gray-100 dark:bg-gray-800 text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'">
                <div class="size-10 rounded-full bg-black/5 flex items-center justify-center mb-1">
                    <span class="material-symbols-outlined font-bold">logout</span>
                </div>
                <span class="font-black text-sm tracking-wide">SCAN PULANG</span>
            </button>

        </div>
    </div>

    <!-- Loading/Alert Overlay -->
    <div x-show="submitting" class="fixed inset-0 z-[60] bg-black/50 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-2xl flex flex-col items-center animate-bounce-in">
            <span class="material-symbols-outlined text-primary text-5xl animate-spin mb-4">progress_activity</span>
            <p class="font-bold text-lg text-text-main dark:text-white">Memproses Absensi...</p>
        </div>
    </div>

    <!-- Alert Toast -->
    <div x-show="alert.show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="translate-y-full opacity-0"
        class="fixed bottom-32 left-0 w-full z-[60] px-4 pointer-events-none">
        <div
            class="max-w-md mx-auto bg-surface-light dark:bg-surface-dark border border-gray-100 dark:border-gray-800 shadow-2xl rounded-2xl p-4 flex items-center gap-4 pointer-events-auto">
            <div class="size-12 rounded-full flex items-center justify-center shrink-0"
                :class="alert.type === 'success' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'">
                <span class="material-symbols-outlined"
                    x-text="alert.type === 'success' ? 'check_circle' : 'error'"></span>
            </div>
            <div>
                <h4 class="font-bold text-text-main dark:text-white" x-text="alert.title"></h4>
                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="alert.message"></p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        function scanApp() {
            return {
                mode: 'qr', // qr, selfie
                activeAction: 'masuk', // masuk, pulang
                time: '00:00',
                ampm: 'AM',
                date: 'Senin, 1 Januari 2024',

                locationValid: false,
                locationStatus: 'Mencari Lokasi...',
                accuracy: null,
                latitude: null,
                longitude: null,

                capturedImage: null,
                html5QrcodeScanner: null,
                submitting: false,
                alert: { show: false, type: 'success', title: '', message: '' },

                init() {
                    this.startClock();
                    this.getLocation();

                    this.$watch('mode', (value) => {
                        this.switchCameraMode(value);
                    });

                    this.$nextTick(() => {
                        this.startQrScanner();
                    });
                },

                startClock() {
                    const updateTime = () => {
                        const now = new Date();
                        const hours = now.getHours().toString().padStart(2, '0');
                        const minutes = now.getMinutes().toString().padStart(2, '0');

                        this.time = `${hours}:${minutes}`;

                        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                        this.date = now.toLocaleDateString('id-ID', options);
                    };
                    updateTime();
                    setInterval(updateTime, 1000);
                },

                getLocation() {
                    if (!navigator.geolocation) {
                        this.locationStatus = 'GPS tidak didukung browser ini';
                        return;
                    }

                    const successCallback = (position) => {
                        this.latitude = position.coords.latitude;
                        this.longitude = position.coords.longitude;
                        this.accuracy = position.coords.accuracy;
                        this.locationValid = true;
                        this.locationStatus = 'Lokasi Terkunci';
                    };

                    const errorCallback = (error) => {
                        this.locationValid = false;
                        this.accuracy = null;
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                this.locationStatus = 'Izin Lokasi Ditolak via Browser';
                                this.showAlert('error', 'Izin Ditolak', 'Mohon izinkan akses lokasi di pengaturan browser.');
                                break;
                            case error.POSITION_UNAVAILABLE:
                                this.locationStatus = 'Informasi Lokasi Tidak Tersedia';
                                break;
                            case error.TIMEOUT:
                                this.locationStatus = 'Waktu Habis (Timeout)';
                                // Retry with lower accuracy?
                                break;
                            default:
                                this.locationStatus = 'Gagal Deteksi Lokasi: ' + error.message;
                        }
                    };

                    // Try High Accuracy first
                    navigator.geolocation.watchPosition(successCallback, errorCallback, {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 0
                    });
                },

                setMode(newMode) {
                    if (this.mode === newMode) return;
                    this.mode = newMode;
                    this.capturedImage = null;
                },

                setAction(action) {
                    this.activeAction = action;
                    // Optional: Reset captured image when switching actions? 
                    // this.capturedImage = null;
                },

                switchCameraMode(mode) {
                    if (this.html5QrcodeScanner) {
                        this.html5QrcodeScanner.clear().catch(err => { });
                    }
                    const video = document.getElementById('selfie-video');
                    if (video && video.srcObject) {
                        video.srcObject.getTracks().forEach(track => track.stop());
                    }

                    setTimeout(() => {
                        if (mode === 'qr') {
                            this.startQrScanner();
                        } else {
                            this.startSelfieCamera();
                        }
                    }, 300);
                },

                startQrScanner() {
                    const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                    if (!document.getElementById("reader")) return;

                    this.html5QrcodeScanner = new Html5QrcodeScanner("reader", config, false);
                    this.html5QrcodeScanner.render(this.onScanSuccess.bind(this), this.onScanFailure.bind(this));
                },

                startSelfieCamera() {
                    const video = document.getElementById('selfie-video');

                    // Check if browser supports mediaDevices
                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        this.showAlert('error', 'Kamera Error', 'Browser tidak mendukung akses kamera. Pastikan menggunakan HTTPS.');
                        return;
                    }

                    navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } })
                        .then((stream) => {
                            video.srcObject = stream;
                        })
                        .catch((err) => {
                            console.error("Camera Error: ", err);
                            let msg = 'Izin kamera diperlukan.';
                            if (err.name === 'NotAllowedError') msg = 'Akses kamera ditolak. Izinkan di pengaturan.';
                            if (err.name === 'NotFoundError') msg = 'Kamera tidak ditemukan.';
                            if (err.name === 'NotReadableError') msg = 'Kamera sedang digunakan aplikasi lain.';
                            this.showAlert('error', 'Kamera Error', msg);
                        });
                },

                takePhoto() {
                    const video = document.getElementById('selfie-video');
                    const canvas = document.getElementById('selfie-canvas');
                    const context = canvas.getContext('2d');

                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    context.translate(canvas.width, 0);
                    context.scale(-1, 1);
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    this.capturedImage = canvas.toDataURL('image/jpeg');

                    // Auto submit on selfie take? Or wait for confirmation?
                    // User flow: Click "Scan Masuk" -> If Selfie Mode -> Take Photo -> Submit
                    // But here we are IN selfie mode taking a photo.
                    // Let's autosubmit after small delay
                    setTimeout(() => {
                        this.submitAttendance('selfie');
                    }, 500);
                },

                retakePhoto() {
                    this.capturedImage = null;
                },

                onScanSuccess(decodedText, decodedResult) {
                    this.html5QrcodeScanner.clear();
                    this.submitAttendance('qr', decodedText);
                },

                onScanFailure(error) { },

                submitAttendance(type, qrContent = null) {
                    if (!this.latitude || !this.longitude) {
                        this.showAlert('error', 'Lokasi Belum Siap', 'Tunggu sampai lokasi terkunci (hijau). Akurasi: ' + (this.accuracy ? this.accuracy + 'm' : 'Menunggu...'));
                        return;
                    }

                    this.submitting = true;

                    const formData = new FormData();
                    formData.append('latitude', this.latitude);
                    formData.append('longitude', this.longitude);
                    formData.append('type', type);
                    formData.append('action_status', this.activeAction); // 'masuk' or 'pulang'

                    if (qrContent) formData.append('qr_content', qrContent);
                    if (type === 'selfie' && this.capturedImage) formData.append('image', this.capturedImage);

                    formData.append('_token', '{{ csrf_token() }}');

                    fetch('{{ route("scan.store") }}', {
                        method: 'POST',
                        headers: { 'Accept': 'application/json' },
                        body: formData
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.text().then(text => { throw new Error(text || 'Server Error'); });
                            }
                            return response.json();
                        })
                        .then(data => {
                            this.submitting = false;
                            if (data.status === 'success') {
                                this.showAlert('success', 'Berhasil', data.message);
                                setTimeout(() => {
                                    window.location.href = '{{ route("dashboard.index") }}';
                                }, 2000);
                            } else {
                                this.showAlert('error', 'Gagal', data.message);
                                if (type === 'qr') setTimeout(() => this.startQrScanner(), 2000);
                            }
                        })
                        .catch(error => {
                            this.submitting = false;
                            console.error('Submission Error:', error);

                            // Parse JSON error if possible
                            let errorMessage = 'Terjadi kesalahan jaringan atau server.';
                            try {
                                const errorObj = JSON.parse(error.message);
                                if (errorObj.message) errorMessage = errorObj.message;
                            } catch (e) {
                                errorMessage = error.message.replace(/<[^>]*>?/gm, '').substring(0, 100); // Strip HTML tags and limit length
                            }

                            this.showAlert('error', 'Error Sistem', errorMessage);
                        });
                },

                showAlert(type, title, message) {
                    this.alert.type = type;
                    this.alert.title = title;
                    this.alert.message = message;
                    this.alert.show = true;
                    setTimeout(() => { this.alert.show = false; }, 4000);
                }
            }
        }
    </script>
</body>

</html>