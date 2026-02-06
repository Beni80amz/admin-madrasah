<div class="fi-in-view-entry">
    <a href="{{ $link }}" target="_blank" rel="noopener noreferrer"
        class="group relative flex items-center gap-x-5 overflow-hidden rounded-2xl bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:bg-gray-900 dark:ring-1 dark:ring-white/10 dark:hover:ring-primary-400">

        <!-- Hover background gradient -->
        <div
            class="absolute inset-0 translate-y-full bg-gradient-to-t from-primary-500/5 to-transparent transition-transform duration-500 group-hover:translate-y-0">
        </div>

        <div
            class="relative flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 {{ $isMain ? 'bg-primary-500/10' : 'bg-blue-500/10' }}">
            @if($isMain)
                <x-heroicon-s-cloud style="width: 28px; height: 28px;" class="text-primary-600 dark:text-primary-400" />
            @else
                <x-heroicon-s-folder style="width: 28px; height: 28px;" class="text-blue-600 dark:text-blue-400" />
            @endif
        </div>

        <div class="relative flex-1 min-w-0">
            <h4 class="truncate text-sm font-black tracking-tight text-gray-950 dark:text-white">
                {{ $name }}
            </h4>
            <div class="mt-1 flex items-center gap-x-1.5">
                <div
                    class="h-1.5 w-1.5 rounded-full {{ $isConnected ? 'bg-emerald-500' : 'bg-gray-300 animate-pulse' }}">
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    {{ $isMain ? 'Root Directory' : 'Sub Directory' }}
                </span>
            </div>
        </div>

        <div
            class="relative flex h-9 w-9 items-center justify-center rounded-xl bg-gray-50 text-gray-400 transition-all duration-300 group-hover:bg-primary-500 group-hover:text-white dark:bg-gray-800">
            <x-heroicon-s-arrow-up-right style="width: 16px; height: 16px;"
                class="transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
        </div>
    </a>
</div>