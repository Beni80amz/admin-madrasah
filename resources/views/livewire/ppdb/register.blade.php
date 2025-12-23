<div>
    {{-- Custom styles for dark mode select dropdowns --}}
    <style>
        .dark select,
        .dark select option {
            color-scheme: dark;
            background-color: #1f2937 !important;
            color: #fff !important;
        }

        select option {
            background-color: #fff;
            color: #111827;
        }

        .dark select option {
            background-color: #1f2937;
            color: #fff;
        }
    </style>

    <div class="min-h-screen bg-surface-light dark:bg-background-dark py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-10">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                    Penerimaan Peserta Didik Baru
                </h2>
                <p class="mt-4 text-lg text-primary font-semibold">
                    Tahun Ajaran {{ $tahunAjaran }}
                </p>
            </div>

            @if($success)
                <!-- Success Message -->
                <div
                    class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-xl border border-primary/20 p-8 text-center animate-fade-in">
                    <div
                        class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 dark:bg-green-900/30 mb-6">
                        <span
                            class="material-symbols-outlined text-4xl text-green-600 dark:text-green-400">check_circle</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pendaftaran Berhasil!</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Data Anda telah berhasil dikirim. Silakan simpan koda pendaftaran Anda untuk pengecekan status
                        selanjutnya.
                    </p>
                    <div class="bg-gray-100 dark:bg-white/5 rounded-lg p-4 mb-8 inline-block">
                        <span class="block text-sm text-gray-500 dark:text-gray-400 uppercase tracking-widest">Koede
                            Pendaftaran</span>
                        <span class="block text-3xl font-mono font-bold text-primary mt-1">{{ $registrationCode }}</span>
                    </div>
                    <div>
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-white bg-primary hover:bg-primary-dark transition-colors shadow-lg shadow-primary/30">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            @else
                <!-- Form Container -->
                <div
                    class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-xl border border-border-light dark:border-border-dark overflow-hidden">

                    <!-- Progress Bar -->
                    <div class="bg-gray-100 dark:bg-white/5 px-6 py-4 border-b border-border-light dark:border-border-dark">
                        <div
                            class="flex items-center justify-between text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">
                            <span>Langkah {{ $currentStep }} dari {{ $totalSteps }}</span>
                            <span>{{ round(($currentStep / $totalSteps) * 100) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                            <div class="bg-primary h-2.5 rounded-full transition-all duration-500 ease-out"
                                style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
                        </div>
                        <div class="flex justify-between mt-4">
                            <div
                                class="flex flex-col items-center {{ $currentStep >= 1 ? 'text-primary' : 'text-gray-400' }}">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center border-2 {{ $currentStep >= 1 ? 'border-primary bg-primary text-white' : 'border-gray-300' }}">
                                    1</div>
                                <span class="text-xs mt-1 font-medium">Data Diri</span>
                            </div>
                            <div
                                class="flex-1 border-t-2 {{ $currentStep >= 2 ? 'border-primary' : 'border-gray-200 dark:border-gray-700' }} mt-4 mx-2">
                            </div>
                            <div
                                class="flex flex-col items-center {{ $currentStep >= 2 ? 'text-primary' : 'text-gray-400' }}">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center border-2 {{ $currentStep >= 2 ? 'border-primary bg-primary text-white' : 'border-gray-300' }}">
                                    2</div>
                                <span class="text-xs mt-1 font-medium">Orang Tua</span>
                            </div>
                            <div
                                class="flex-1 border-t-2 {{ $currentStep >= 3 ? 'border-primary' : 'border-gray-200 dark:border-gray-700' }} mt-4 mx-2">
                            </div>
                            <div
                                class="flex flex-col items-center {{ $currentStep >= 3 ? 'text-primary' : 'text-gray-400' }}">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center border-2 {{ $currentStep >= 3 ? 'border-primary bg-primary text-white' : 'border-gray-300' }}">
                                    3</div>
                                <span class="text-xs mt-1 font-medium">Dokumen</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <form wire:submit.prevent="submit">

                            <!-- Step 1: Data Diri -->
                            @if($currentStep === 1)
                                <div class="space-y-6 animate-fade-in-up">
                                    <h3
                                        class="text-xl font-bold text-gray-900 dark:text-white border-b pb-2 border-border-light dark:border-border-dark">
                                        Data Pribadi Calon Siswa</h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama
                                                Lengkap <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model.blur="nama_lengkap"
                                                placeholder="Masukkan nama lengkap"
                                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-white/5 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 transition-colors">
                                            @error('nama_lengkap') <span
                                            class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NIK
                                                <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model.blur="nik" placeholder="16 digit NIK" maxlength="16"
                                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-white/5 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 transition-colors">
                                            @error('nik') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NISN
                                                <span class="text-gray-400 text-xs">(Opsional)</span></label>
                                            <input type="text" wire:model.blur="nisn" placeholder="Nomor Induk Siswa Nasional"
                                                maxlength="10"
                                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-white/5 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 transition-colors">
                                            @error('nisn') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis
                                                Kelamin <span class="text-red-500">*</span></label>
                                            <select wire:model.live="jenis_kelamin"
                                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-white/5 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 transition-colors">
                                                <option value="" class="bg-white dark:bg-gray-900">Pilih Jenis Kelamin</option>
                                                <option value="L" class="bg-white dark:bg-gray-900">Laki-laki</option>
                                                <option value="P" class="bg-white dark:bg-gray-900">Perempuan</option>
                                            </select>
                                            @error('jenis_kelamin') <span
                                            class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tempat
                                                Lahir <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model.blur="tempat_lahir" placeholder="Contoh: Jakarta"
                                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-white/5 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 transition-colors">
                                            @error('tempat_lahir') <span
                                            class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal
                                                Lahir <span class="text-red-500">*</span></label>
                                            <input type="date" wire:model.blur="tanggal_lahir"
                                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-white/5 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 transition-colors">
                                            @error('tanggal_lahir') <span
                                            class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Agama
                                                <span class="text-red-500">*</span></label>
                                            <select wire:model.live="agama"
                                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-white/5 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 transition-colors">
                                                <option value="Islam" class="bg-white dark:bg-gray-900">Islam</option>
                                                <option value="Kristen" class="bg-white dark:bg-gray-900">Kristen</option>
                                                <option value="Katolik" class="bg-white dark:bg-gray-900">Katolik</option>
                                                <option value="Hindu" class="bg-white dark:bg-gray-900">Hindu</option>
                                                <option value="Buddha" class="bg-white dark:bg-gray-900">Buddha</option>
                                                <option value="Konghucu" class="bg-white dark:bg-gray-900">Konghucu</option>
                                            </select>
                                            @error('agama') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div x-data="{ asalSekolah: @entangle('asal_sekolah') }">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Asal
                                                    Sekolah <span class="text-red-500">*</span></label>
                                                <select x-model="asalSekolah" wire:model.live="asal_sekolah"
                                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-white/5 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 transition-colors">
                                                    <option value="" class="bg-white dark:bg-gray-900">Pilih Asal Sekolah
                                                    </option>
                                                    <option value="TK" class="bg-white dark:bg-gray-900">TK</option>
                                                    <option value="RA" class="bg-white dark:bg-gray-900">RA</option>
                                                    <option value="PAUD" class="bg-white dark:bg-gray-900">PAUD</option>
                                                    <option value="BIMBA" class="bg-white dark:bg-gray-900">BIMBA</option>
                                                    <option value="Orang Tua" class="bg-white dark:bg-gray-900">Orang Tua
                                                    </option>
                                                    <option value="Pindahan" class="bg-white dark:bg-gray-900">Pindahan</option>
                                                    <option value="Lainnya" class="bg-white dark:bg-gray-900">Lainnya</option>
                                                </select>
                                                @error('asal_sekolah') <span
                                                class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                            </div>

                                            {{-- Field Nama Sekolah Asal - muncul jika asal_sekolah bukan "Orang Tua" dan sudah
                                            dipilih --}}
                                            <div x-show="asalSekolah && asalSekolah !== 'Orang Tua'" x-transition class="mt-6">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama
                                                    Sekolah Asal <span class="text-red-500">*</span></label>
                                                <input type="text" wire:model.blur="nama_sekolah_asal"
                                                    placeholder="Masukkan nama sekolah asal (contoh: TK Al-Ikhlas)"
                                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-white/5 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 transition-colors">
                                                @error('nama_sekolah_asal') <span
                                                class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat
                                                Domisili <span class="text-red-500">*</span></label>
                                            <textarea wire:model.blur="alamat" rows="3"
                                                placeholder="Masukkan alamat domisili/tempat tinggal saat ini"
                                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-white/5 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 transition-colors"></textarea>
                                            @error('alamat') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat
                                                Sesuai KK <span class="text-gray-400 text-xs">(Opsional)</span></label>
                                            <textarea wire:model.blur="alamat_kk" rows="3"
                                                placeholder="Masukkan alamat sesuai Kartu Keluarga"
                                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-white/5 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 transition-colors"></textarea>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jika alamat KK sama dengan
                                                alamat domisili, kosongkan field ini.</p>
                                            @error('alamat_kk') <span
                                            class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Step 2: Data Ortu -->
                            @if($currentStep === 2)
                                <div class="space-y-6 animate-fade-in-up">
                                    <h3
                                        class="text-xl font-bold text-gray-900 dark:text-white border-b pb-2 border-border-light dark:border-border-dark">
                                        Data Orang Tua / Wali</h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama
                                                Ayah Kandung <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model.blur="nama_ayah"
                                                placeholder="Masukkan nama ayah kandung"
                                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-white/5 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 transition-colors">
                                            @error('nama_ayah') <span
                                            class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama
                                                Ibu Kandung <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model.blur="nama_ibu"
                                                placeholder="Masukkan nama ibu kandung"
                                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-white/5 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 transition-colors">
                                            @error('nama_ibu') <span
                                            class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No.
                                                HP Ortu <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model.blur="no_hp_ortu" placeholder="Contoh: 08123456789"
                                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-white/5 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 transition-colors">
                                            @error('no_hp_ortu') <span
                                            class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama
                                                Wali <span class="text-gray-400 text-xs">(Jika ada)</span></label>
                                            <input type="text" wire:model.blur="nama_wali"
                                                placeholder="Masukkan nama wali jika ada"
                                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-white/5 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 transition-colors">
                                            @error('nama_wali') <span
                                            class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Step 3: Upload Dokumen -->
                            @if($currentStep === 3)
                                <div class="space-y-6 animate-fade-in-up">
                                    <h3
                                        class="text-xl font-bold text-gray-900 dark:text-white border-b pb-2 border-border-light dark:border-border-dark">
                                        Upload Dokumen Persyaratan</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Format yang diperbolehkan: JPG, PNG,
                                        JPEG. Ukuran maks: 2MB per file.</p>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                        @foreach($persyaratanDokumen as $index => $item)
                                            @php
                                                $isOptional = Str::contains(strtolower($item), 'opsional') || Str::contains(strtolower($item), 'jika ada');
                                            @endphp
                                            <div
                                                class="border-2 border-dashed rounded-xl p-4 transition-all bg-white/50 dark:bg-white/5 {{ isset($dokumen[$index]) && $dokumen[$index] ? 'border-green-500 bg-green-50/50 dark:bg-green-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-primary hover:bg-primary/5' }}">
                                                <span
                                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 text-center">{{ $item }}
                                                    @if(!$isOptional)
                                                        <span class="text-red-500">*</span>
                                                    @else
                                                        <span class="text-gray-400 text-xs">(Opsional)</span>
                                                    @endif
                                                </span>
                                                
                                                @if(isset($dokumen[$index]) && $dokumen[$index])
                                                    {{-- Preview Image --}}
                                                    <div class="relative group">
                                                        <div class="relative bg-gray-100 dark:bg-gray-700/50 rounded-lg overflow-hidden">
                                                            <img src="{{ $dokumen[$index]->temporaryUrl() }}"
                                                                class="w-full h-32 object-cover rounded-lg">
                                                            {{-- Overlay with actions --}}
                                                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                                                <button type="button" wire:click="$set('dokumen.{{ $index }}', null)"
                                                                    class="p-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors">
                                                                    <span class="material-symbols-outlined text-lg">delete</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 text-center">
                                                            <span class="text-xs text-green-600 dark:text-green-400 font-medium flex items-center justify-center gap-1">
                                                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                                                File berhasil dipilih
                                                            </span>
                                                        </div>
                                                    </div>
                                                    {{-- Hidden input for re-upload --}}
                                                    <label class="cursor-pointer block mt-2">
                                                        <span class="text-xs text-primary hover:underline flex items-center justify-center gap-1">
                                                            <span class="material-symbols-outlined text-sm">refresh</span>
                                                            Ganti file
                                                        </span>
                                                        <input type="file" wire:model="dokumen.{{ $index }}" class="hidden" accept="image/*">
                                                    </label>
                                                @else
                                                    {{-- Upload Placeholder --}}
                                                    <label class="cursor-pointer block">
                                                        <div class="flex flex-col items-center justify-center py-4">
                                                            <span class="material-symbols-outlined text-4xl text-gray-400 dark:text-gray-500 mb-2">cloud_upload</span>
                                                            <span class="text-sm text-gray-500 dark:text-gray-400">Klik untuk upload</span>
                                                            <span class="text-xs text-gray-400 dark:text-gray-500 mt-1">JPG, PNG, JPEG (Maks 2MB)</span>
                                                        </div>
                                                        <input type="file" wire:model="dokumen.{{ $index }}" class="hidden" accept="image/*">
                                                    </label>
                                                @endif
                                                
                                                {{-- Loading indicator --}}
                                                <div wire:loading wire:target="dokumen.{{ $index }}" class="mt-2">
                                                    <div class="flex items-center justify-center gap-2 text-primary">
                                                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        <span class="text-xs">Mengupload...</span>
                                                    </div>
                                                </div>
                                                
                                                @error("dokumen.{$index}") 
                                                    <span class="text-red-500 text-sm mt-2 block text-center">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            @endif

                            <!-- Navigation Buttons -->
                            <div
                                class="mt-8 flex justify-between pt-6 border-t border-border-light dark:border-border-dark">
                                @if($currentStep > 1)
                                    <button type="button" wire:click="prevStep"
                                        class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 shadow-sm text-base font-medium rounded-full text-gray-700 dark:text-gray-200 bg-white dark:bg-surface-dark hover:bg-gray-50 dark:hover:bg-white/5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all">
                                        <span class="material-symbols-outlined mr-2">arrow_back</span>
                                        Kembali
                                    </button>
                                @else
                                    <div></div>
                                @endif

                                @if($currentStep < $totalSteps)
                                    <button type="button" wire:click="nextStep" wire:loading.attr="disabled"
                                        wire:loading.class="opacity-50 cursor-not-allowed"
                                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all">
                                        <span wire:loading.remove wire:target="nextStep">Lanjut</span>
                                        <span wire:loading wire:target="nextStep">Memproses...</span>
                                        <span class="material-symbols-outlined ml-2" wire:loading.remove
                                            wire:target="nextStep">arrow_forward</span>
                                        <svg wire:loading wire:target="nextStep" class="animate-spin ml-2 h-5 w-5 text-white"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </button>
                                @else
                                    <button type="submit" wire:loading.attr="disabled"
                                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span wire:loading.remove>Kirim Pendaftaran</span>
                                        <span wire:loading class="flex items-center gap-2">
                                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            Mengirim...
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>