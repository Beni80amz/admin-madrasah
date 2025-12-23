<div class="min-h-screen pt-24 pb-16 px-5 md:px-10 lg:px-20 xl:px-40 w-full flex items-center justify-center">
    <div class="max-w-lg w-full flex flex-col items-center gap-8">

        <!-- Animated Lock Icon -->
        <div class="relative">
            <!-- Outer Ring -->
            <div
                class="size-32 rounded-full bg-gradient-to-br from-red-500/10 to-orange-600/10 border border-red-500/20 flex items-center justify-center animate-pulse">
                <!-- Inner Circle -->
                <div
                    class="size-24 rounded-full bg-gradient-to-br from-red-500/20 to-orange-600/20 border border-red-500/30 flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-400 text-5xl">lock</span>
                </div>
            </div>
            <!-- Decorative Circles -->
            <div class="absolute -top-2 -right-2 size-6 rounded-full bg-red-500/30 animate-ping"></div>
            <div class="absolute -bottom-1 -left-1 size-4 rounded-full bg-orange-500/30 animate-ping"
                style="animation-delay: 0.5s;"></div>
        </div>

        <!-- Content Card -->
        <div class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-3xl p-8 w-full text-center">
            <!-- Title -->
            <h1 class="text-text-primary-light dark:text-text-primary-dark text-2xl md:text-3xl font-bold mb-4">Akses Terbatas</h1>

            <!-- Message -->
            <p class="text-text-secondary-light dark:text-text-secondary-dark text-lg leading-relaxed mb-6">
                {{ $message }}
            </p>

            <!-- Warning Box -->
            <div
                class="bg-gradient-to-r from-amber-500/10 to-orange-500/10 border border-amber-500/30 rounded-2xl p-5 mb-8">
                <div class="flex items-center justify-center gap-3 mb-3">
                    <span class="material-symbols-outlined text-amber-400 text-2xl">shield</span>
                    <span class="text-amber-300 font-bold">Perlindungan Data</span>
                </div>
                <p class="text-amber-200/80 text-sm leading-relaxed">
                    Data siswa merupakan informasi yang bersifat rahasia dan hanya dapat diakses oleh admin atau pihak
                    yang berwenang. Silakan login terlebih dahulu untuk melanjutkan.
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('home') }}" wire:navigate
                    class="flex-1 flex items-center justify-center gap-2 px-6 py-4 bg-white/5 border border-border-dark text-gray-300 hover:text-text-primary-light dark:text-text-primary-dark hover:border-white/20 font-medium rounded-xl transition-all">
                    <span class="material-symbols-outlined text-lg">home</span>
                    Kembali ke Beranda
                </a>
                <a href="/admin/login"
                    class="flex-1 flex items-center justify-center gap-2 px-6 py-4 bg-primary text-text-primary-light dark:text-text-primary-dark font-bold rounded-xl hover:brightness-110 transition-all">
                    <span class="material-symbols-outlined text-lg">login</span>
                    Login Admin
                </a>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="flex items-center gap-2 text-gray-500 text-sm">
            <span class="material-symbols-outlined text-lg">info</span>
            <span>Jika Anda memiliki pertanyaan, silakan hubungi administrator.</span>
        </div>

    </div>
</div>