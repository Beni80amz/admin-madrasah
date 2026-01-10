<footer class="w-full bg-background-dark border-t border-border-dark pt-16 pb-8 text-text-primary-dark">
    <div class="layout-container flex justify-center w-full px-5 md:px-10 lg:px-40">
        <div class="w-full max-w-[1200px] flex flex-col gap-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                <!-- Brand & Address -->
                <div class="flex flex-col gap-6">
                    <div class="flex items-center gap-3 text-white">
                        <div class="flex items-center justify-center size-8 rounded-full bg-surface-dark text-primary">
                            <span class="material-symbols-outlined text-[20px]">school</span>
                        </div>
                        <h2 class="text-white text-lg font-bold">
                            {{ $siteProfile->nama_madrasah ?? 'Madrasah Prototype' }}
                        </h2>
                    </div>
                    <p class="text-text-secondary-dark text-sm leading-relaxed">
                        {{ $siteProfile->visi ? strip_tags($siteProfile->visi) : 'Mewujudkan generasi Rabbani yang unggul dalam prestasi, berkarakter Islami, dan berwawasan global.' }}
                    </p>
                    <div class="flex flex-col gap-3 text-sm text-text-secondary-dark">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary text-lg">location_on</span>
                            <span>{{ $siteProfile->alamat ?? 'Jl. Jasa Warga No.6, Bakti Jaya, Kec. Sukmajaya, Kota Depok, Jawa Barat 16418' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-lg">call</span>
                            <span>{{ $siteProfile->no_hp ?? '+62 82****3967' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-lg">mail</span>
                            <span>{{ $siteProfile->email ?? 'miamzdepok@gmail.com' }}</span>
                        </div>
                    </div>
                </div>
                <!-- Quick Links -->
                <div class="flex flex-col gap-4">
                    <h3 class="text-white font-bold text-lg">Tautan Cepat</h3>
                    <div class="flex flex-col gap-2 text-text-secondary-dark text-sm">
                        <a class="hover:text-primary transition-colors" href="{{ route('profile') }}"
                            wire:navigate>Profil Madrasah</a>
                        <a class="hover:text-primary transition-colors" href="{{ route('profile') }}#visi-misi"
                            wire:navigate>Visi &amp; Misi</a>
                        <a class="hover:text-primary transition-colors" href="{{ route('profile') }}#identitas"
                            wire:navigate>Struktur Organisasi</a>

                        @if(\App\Models\AppSetting::isPpdbActive())
                            <a class="hover:text-primary transition-colors" href="{{ route('ppdb') }}"
                                wire:navigate>Informasi PPDB</a>
                        @endif

                        <a class="hover:text-primary transition-colors" href="{{ route('news') }}"
                            wire:navigate>Berita</a>
                        <a class="hover:text-primary transition-colors" href="{{ route('gallery') }}"
                            wire:navigate>Galeri</a>
                        <a class="hover:text-primary transition-colors" href="{{ route('contact') }}"
                            wire:navigate>Kontak</a>
                        <a class="hover:text-primary transition-colors" href="{{ route('akademik.data-guru') }}"
                            wire:navigate>Karir</a>
                    </div>
                </div>
                <!-- Academics -->
                <div class="flex flex-col gap-4">
                    <h3 class="text-white font-bold text-lg">Akademik</h3>
                    <div class="flex flex-col gap-2 text-text-secondary-dark text-sm">
                        <a class="hover:text-primary transition-colors" href="{{ route('akademik.data-siswa') }}"
                            wire:navigate>Data Siswa</a>
                        <a class="hover:text-primary transition-colors" href="{{ route('akademik.data-guru') }}"
                            wire:navigate>Data Guru & Staff</a>
                        <a class="hover:text-primary transition-colors" href="{{ route('akademik.kurikulum') }}"
                            wire:navigate>Kurikulum</a>
                        <a class="hover:text-primary transition-colors" href="{{ route('akademik.kalender') }}"
                            wire:navigate>Kalender Akademik</a>
                        <a class="hover:text-primary transition-colors" href="{{ route('akademik.prestasi-siswa') }}"
                            wire:navigate>Prestasi Siswa</a>
                        <a class="hover:text-primary transition-colors" href="{{ route('akademik.prestasi-guru') }}"
                            wire:navigate>Prestasi Guru</a>
                        <a class="hover:text-primary transition-colors" href="{{ route('akademik.data-alumni') }}"
                            wire:navigate>Alumni</a>
                        <a class="hover:text-primary transition-colors" href="{{ route('login') }}">Absensi Digital</a>
                        <a class="hover:text-primary transition-colors" href="#">Perpustakaan</a>
                    </div>
                </div>
                <!-- Map -->
                <div class="flex flex-col gap-4">
                    <h3 class="text-white font-bold text-lg">Lokasi Kami</h3>
                    <div class="w-full h-40 bg-gray-800 rounded-xl overflow-hidden relative">
                        @if($siteProfile->google_maps_embed)
                            <div
                                class="map-embed-container w-full h-full [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:border-0">
                                {!! $siteProfile->google_maps_embed !!}
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-500">
                                <span class="text-sm">Lokasi belum diatur</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex gap-4 mt-2">
                        @if($siteProfile->facebook)
                            <a class="size-10 rounded-full bg-surface-dark flex items-center justify-center text-text-primary-dark hover:bg-primary hover:text-white transition-colors"
                                href="{{ $siteProfile->facebook }}" target="_blank">
                                <svg class="lucide lucide-facebook" fill="none" height="20" stroke="currentColor"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24"
                                    width="20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                </svg>
                            </a>
                        @endif
                        @if($siteProfile->instagram)
                            <a class="size-10 rounded-full bg-surface-dark flex items-center justify-center text-text-primary-dark hover:bg-primary hover:text-white transition-colors"
                                href="{{ $siteProfile->instagram }}" target="_blank">
                                <svg class="lucide lucide-instagram" fill="none" height="20" stroke="currentColor"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24"
                                    width="20" xmlns="http://www.w3.org/2000/svg">
                                    <rect height="20" rx="5" ry="5" width="20" x="2" y="2"></rect>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                    <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                                </svg>
                            </a>
                        @endif
                        @if($siteProfile->youtube)
                            <a class="size-10 rounded-full bg-surface-dark flex items-center justify-center text-text-primary-dark hover:bg-primary hover:text-white transition-colors"
                                href="{{ $siteProfile->youtube }}" target="_blank">
                                <svg class="lucide lucide-youtube" fill="none" height="20" stroke="currentColor"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24"
                                    width="20" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17">
                                    </path>
                                    <path d="m10 15 5-3-5-3z"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div
                class="border-t border-border-dark pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-text-secondary-dark">
                <p>© {{ date('Y') }} {{ $siteProfile->nama_madrasah ?? 'Madrasah' }}. All rights reserved.</p>
                <div class="flex gap-6">
                    <a class="hover:text-white" href="#">Privacy Policy</a>
                    <a class="hover:text-white" href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>
</footer>