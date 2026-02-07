<x-filament-panels::page>
    <div class="space-y-6">
        <header class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1
                    class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                    {{ $this->getTitle() }}
                </h1>
                <p class="fi-header-subheading mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Kelola informasi profil dan detail kepegawaian Anda di sini.
                </p>
            </div>
        </header>

        {{ $this->getSchema('form') }}

        <div class="fi-page-actions flex flex-wrap items-center gap-3 justify-start">
            @foreach ($this->getFormActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    </div>
</x-filament-panels::page>