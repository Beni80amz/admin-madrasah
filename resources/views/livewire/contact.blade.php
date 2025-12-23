<div class="min-h-screen pt-24 pb-16 px-5 md:px-10 lg:px-40">
    <div class="max-w-[1200px] mx-auto w-full flex flex-col gap-10">

        <!-- Header -->
        <div class="flex flex-col gap-2">
            <h1 class="text-text-primary-light dark:text-text-primary-dark text-4xl font-bold">Hubungi Kami</h1>
            <p class="text-text-secondary-light dark:text-text-secondary-dark max-w-2xl">
                Kami siap membantu menjawab pertanyaan Anda seputar pendaftaran, akademik, dan program madrasah lainnya.
            </p>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

            <!-- Left Column: Info -->
            <div class="flex flex-col gap-6">
                <!-- Address Card -->
                <div
                    class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark p-4 rounded-2xl flex items-start gap-4">
                    <div class="size-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary text-2xl">location_on</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <h3 class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg">Alamat Sekolah
                        </h3>
                        <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm leading-relaxed">
                            {{ $profile->alamat ?? 'Alamat belum diinput' }}
                        </p>
                    </div>
                </div>

                <!-- Email Card -->
                <div
                    class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark p-4 rounded-2xl flex items-start gap-4">
                    <div class="size-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary text-2xl">mail</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <h3 class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg">Email Resmi
                        </h3>
                        <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">
                            {{ $profile->email ?? 'Email belum diinput' }}
                        </p>
                    </div>
                </div>

                <!-- Phone Card -->
                <div
                    class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark p-4 rounded-2xl flex items-start gap-4">
                    <div class="size-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary text-2xl">call</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <h3 class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg">Telepon /
                            WhatsApp</h3>
                        <p class="text-text-secondary-light dark:text-text-secondary-dark text-sm">
                            {{ $profile->no_hp ?? 'Telepon belum diinput' }}
                        </p>
                    </div>
                </div>

                <!-- WhatsApp Button -->
                @if($profile->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profile->whatsapp) }}" target="_blank"
                        class="w-full h-14 bg-[#25D366] hover:bg-[#20bd5a] text-white font-bold rounded-full flex items-center justify-center gap-2 transition-transform hover:scale-[1.02]">
                        <span class="material-symbols-outlined text-2xl">chat</span>
                        Chat WhatsApp Sekarang
                    </a>
                @endif

                <!-- Operational Hours -->
                <div class="mt-4">
                    <h3 class="text-text-primary-light dark:text-text-primary-dark font-bold text-lg mb-4">Jam
                        Operasional</h3>
                    <div
                        class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-xl overflow-hidden">
                        @forelse($operationalHours as $index => $hour)
                            <div
                                class="flex justify-between items-center p-4 {{ !$loop->last ? 'border-b border-border-light dark:border-border-dark' : '' }}">
                                <span
                                    class="text-text-secondary-light dark:text-text-secondary-dark">{{ $hour->hari }}</span>
                                @if($hour->is_libur)
                                    <span
                                        class="px-3 py-1 bg-red-500/10 text-red-500 text-xs font-bold rounded-full border border-red-500/20">{{ $hour->waktu }}</span>
                                @else
                                    <span
                                        class="text-text-primary-light dark:text-text-primary-dark font-bold">{{ $hour->waktu }}</span>
                                @endif
                            </div>
                        @empty
                            <div class="p-4 text-center text-text-secondary-light dark:text-text-secondary-dark">
                                Belum ada data jam operasional.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Social Media -->
                <div class="flex gap-4 mt-2">
                    <a href="{{ $profile->facebook ?? '#' }}" target="_blank"
                        class="size-10 rounded-full bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark flex items-center justify-center text-text-secondary-light dark:text-text-secondary-dark hover:text-primary hover:border-primary/50 transition-all">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg>
                    </a>
                    <a href="{{ $profile->instagram ?? '#' }}" target="_blank"
                        class="size-10 rounded-full bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark flex items-center justify-center text-text-secondary-light dark:text-text-secondary-dark hover:text-primary hover:border-primary/50 transition-all">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z" />
                        </svg>
                    </a>
                    <a href="{{ $profile->youtube ?? '#' }}" target="_blank"
                        class="size-10 rounded-full bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark flex items-center justify-center text-text-secondary-light dark:text-text-secondary-dark hover:text-primary hover:border-primary/50 transition-all">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Right Column: Map -->
            <div
                class="h-[500px] w-full rounded-3xl overflow-hidden border border-border-light dark:border-border-dark shadow-lg relative bg-gray-100 dark:bg-[#1c2e24] [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:border-0">
                @if($profile->google_maps_embed)
                    {!! $profile->google_maps_embed !!}
                @else
                    <div
                        class="w-full h-full flex items-center justify-center text-text-secondary-light dark:text-text-secondary-dark">
                        <span>Peta lokasi belum diatur</span>
                    </div>
                @endif

                <!-- Custom overlay for "Lokasi Kami" similar to reference -->
                <div class="absolute bottom-6 left-6 right-6">
                    <div
                        class="bg-[#111714]/90 backdrop-blur-md p-4 rounded-xl border border-white/10 flex items-center gap-4">
                        <div
                            class="size-12 rounded-full bg-[#111714] border border-white/10 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-primary text-2xl">location_on</span>
                        </div>
                        <div>
                            <h4 class="text-white font-bold">Lokasi Kami</h4>
                            <p class="text-xs text-gray-300">Klik pada peta
                                untuk petunjuk arah</p>
                        </div>
                        @if($profile->latitude && $profile->longitude)
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $profile->latitude }},{{ $profile->longitude }}"
                                target="_blank"
                                class="ml-auto flex items-center justify-center size-8 text-gray-300 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined">directions</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>