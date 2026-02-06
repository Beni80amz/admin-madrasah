<div
    class="group flex items-start gap-x-4 p-5 text-left rounded-2xl bg-white shadow-sm ring-1 ring-gray-950/5 transition duration-300 hover:shadow-md hover:ring-primary-500/50 dark:bg-gray-900 dark:ring-white/10 dark:hover:ring-primary-400/50">
    <div
        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-{{ $color }}-500/10 transition duration-300 group-hover:scale-110 dark:bg-{{ $color }}-500/20">
        @if($icon === 'heroicon-o-cloud-arrow-up')
            <x-heroicon-o-cloud-arrow-up style="width: 24px; height: 24px;"
                class="text-{{ $color }}-600 dark:text-{{ $color }}-400" />
        @elseif($icon === 'heroicon-o-folder-open')
            <x-heroicon-o-folder-open style="width: 24px; height: 24px;"
                class="text-{{ $color }}-600 dark:text-{{ $color }}-400" />
        @elseif($icon === 'heroicon-o-eye')
            <x-heroicon-o-eye style="width: 24px; height: 24px;" class="text-{{ $color }}-600 dark:text-{{ $color }}-400" />
        @endif
    </div>
    <div class="flex-1 min-w-0">
        <h4
            class="text-sm font-bold text-gray-950 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition">
            {{ $title }}</h4>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
            {{ $description }}
        </p>
    </div>
</div>