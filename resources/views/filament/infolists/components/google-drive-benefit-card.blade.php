@php
    $accentColor = match ($color) {
        'primary' => 'emerald',
        'success' => 'blue',
        'info' => 'indigo',
        default => 'gray'
    };
@endphp

<div class="fi-in-view-entry">
    <div
        class="group flex items-start gap-x-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 transition-all duration-300 hover:shadow-xl dark:bg-gray-900 dark:ring-white/10 dark:hover:ring-primary-400">
        <div
            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-{{ $accentColor }}-500/10 transition-transform duration-500 group-hover:scale-110">
            <x-dynamic-component :component="$icon" style="width: 26px; height: 26px;"
                class="text-{{ $accentColor }}-600 dark:text-{{ $accentColor }}-400" />
        </div>
        <div class="flex-1 min-w-0">
            <h4 class="text-sm font-black tracking-tight text-gray-950 dark:text-white">
                {{ $title }}
            </h4>
            <p class="mt-2 text-xs leading-relaxed text-gray-500 dark:text-gray-400">
                {{ $description }}
            </p>
        </div>
    </div>
</div>