@extends('layouts.frontend')

@section('content')
    <div class="px-4 py-6 max-w-lg mx-auto">
        <!-- Header -->
        <div class="flex items-center mb-6">
            <a href="{{ route('leave.index') }}" class="mr-4 p-2 rounded-full hover:bg-gray-100">
                <span class="material-symbols-outlined text-gray-600">arrow_back</span>
            </a>
            <h1 class="text-xl font-bold text-gray-900">Buat Pengajuan</h1>
        </div>

        @if(session('error'))
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('leave.store') }}" method="POST" enctype="multipart/form-data" id="leaveForm"
            class="space-y-6">
            @csrf

            <!-- Tipe Izin -->
            <div class="grid grid-cols-2 gap-4">
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="izin" class="peer sr-only" checked>
                    <div
                        class="p-4 rounded-xl border-2 border-gray-100 peer-checked:border-green-600 peer-checked:bg-green-50 transition-all text-center">
                        <span
                            class="material-symbols-outlined text-3xl mb-1 text-gray-500 peer-checked:text-green-600">assignment_ind</span>
                        <p class="font-medium text-sm text-gray-600 peer-checked:text-green-800">Izin</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="sakit" class="peer sr-only">
                    <div
                        class="p-4 rounded-xl border-2 border-gray-100 peer-checked:border-red-600 peer-checked:bg-red-50 transition-all text-center">
                        <span
                            class="material-symbols-outlined text-3xl mb-1 text-gray-500 peer-checked:text-red-600">sick</span>
                        <p class="font-medium text-sm text-gray-600 peer-checked:text-red-800">Sakit</p>
                    </div>
                </label>
            </div>

            <!-- Tanggal -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mulai</label>
                    <input type="date" name="start_date" required
                        class="w-full rounded-xl border-gray-200 focus:border-green-500 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Selesai</label>
                    <input type="date" name="end_date" required
                        class="w-full rounded-xl border-gray-200 focus:border-green-500 focus:ring-green-500">
                </div>
            </div>

            <!-- Alasan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alasan / Keterangan</label>
                <textarea name="reason" rows="3" required
                    class="w-full rounded-xl border-gray-200 focus:border-green-500 focus:ring-green-500"
                    placeholder="Jelaskan alasan pengajuan..."></textarea>
            </div>

            <!-- Kamera / Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Lampiran (Foto)</label>

                <div class="relative">
                    <input type="file" id="cameraInput" accept="image/*" capture="environment" class="hidden"
                        onchange="handleFileSelect(event)">
                    <input type="hidden" name="attachment_base64" id="attachmentBase64">

                    <div id="uploadPlaceholder"
                        class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:bg-gray-50 transition-colors"
                        onclick="document.getElementById('cameraInput').click()">
                        <span class="material-symbols-outlined text-4xl text-gray-400 mb-2">add_a_photo</span>
                        <p class="text-sm text-gray-500">Ketuk untuk ambil foto / upload</p>
                        <p class="text-xs text-gray-400 mt-1">Maksimal 2MB (Otomatis Kompres)</p>
                    </div>

                    <div id="imagePreviewContainer" class="hidden relative mt-2">
                        <img id="imagePreview" src="" alt="Preview"
                            class="w-full rounded-xl shadow-sm max-h-64 object-cover">
                        <button type="button" onclick="resetImage()"
                            class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full shadow-lg">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                        <p id="fileSizeInfo" class="text-xs text-center mt-2 text-gray-500"></p>
                    </div>
                </div>
                @error('attachment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <button type="submit" id="submitBtn"
                class="w-full bg-green-600 text-white py-3 rounded-xl font-semibold shadow-lg shadow-green-200 hover:bg-green-700 transition-colors">
                Kirim Pengajuan
            </button>
        </form>
    </div>

    <script>
        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                compressImage(file);
            }
        }

        function compressImage(file) {
            const reader = new FileReader();
            reader.readAsDataURL(file);

            reader.onload = function (event) {
                const img = new Image();
                img.src = event.target.result;

                img.onload = function () {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;

                    // Max dimensions
                    const MAX_WIDTH = 1200;
                    const MAX_HEIGHT = 1200;

                    if (width > height) {
                        if (width > MAX_WIDTH) {
                            height *= MAX_WIDTH / width;
                            width = MAX_WIDTH;
                        }
                    } else {
                        if (height > MAX_HEIGHT) {
                            width *= MAX_HEIGHT / height;
                            height = MAX_HEIGHT;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    // Compress to JPEG with 0.7 quality
                    const dataUrl = canvas.toDataURL('image/jpeg', 0.7);

                    // Checking size
                    const head = 'data:image/jpeg;base64,';
                    const size = Math.round((dataUrl.length - head.length) * 3 / 4);
                    const sizeInMB = (size / (1024 * 1024)).toFixed(2);

                    // Update UI
                    document.getElementById('imagePreview').src = dataUrl;
                    document.getElementById('attachmentBase64').value = dataUrl;
                    document.getElementById('uploadPlaceholder').classList.add('hidden');
                    document.getElementById('imagePreviewContainer').classList.remove('hidden');
                    document.getElementById('fileSizeInfo').innerText = `Ukuran: ${sizeInMB} MB`;
                }
            }
        }

        function resetImage() {
            document.getElementById('cameraInput').value = '';
            document.getElementById('attachmentBase64').value = '';
            document.getElementById('uploadPlaceholder').classList.remove('hidden');
            document.getElementById('imagePreviewContainer').classList.add('hidden');
        }
    </script>
@endsection