<div class="fi-in-view-entry">
    <a href="{{ $link }}" target="_blank" rel="noopener noreferrer"
        class="group flex items-center gap-x-4 rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 transition duration-300 hover:ring-primary-500 hover:shadow-md dark:bg-gray-900 dark:ring-white/10 dark:hover:ring-primary-400">
        <div
            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl transition duration-300 group-hover:scale-110 {{ $isMain ? 'bg-primary-500/10 dark:bg-primary-500/20' : 'bg-amber-500/10 dark:bg-amber-500/20' }}">
            @if($isMain)
                <x-heroicon-o-cloud style="width: 24px; height: 24px;" class="text-primary-600 dark:text-primary-400" />
            @else
                <x-heroicon-o-folder style="width: 24px; height: 24px;" class="text-amber-600 dark:text-amber-400" />
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <p class="truncate text-sm font-semibold text-gray-950 dark:text-white">
                {{ $name }}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                {{ $isMain ? 'Cloud Storage Utama' : 'Sub-folder Terhubung' }}
            </p>
        </div>
        <div
            class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-50 dark:bg-gray-800 transition group-hover:bg-primary-500 group-hover:text-white">
            <x-heroicon-m-arrow-top-right-on-square style="width: 14px; height: 14px;"
                class="transition group-hover:scale-110" />
        </div>
    </a>
</div>