<x-filament-panels::page>
    <div class="space-y-6">
        <header class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1
                    class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                    {{ $this->getTitle() }}
                </h1>
                <p class="fi-header-subheading mt-2 text-lg text-gray-600 dark:text-gray-400">
                    Kelola data diri dan informasi kepegawaian Anda dengan mudah.
                </p>
            </div>
        </header>

        <x-filament::section>
            <form wire:submit="save" class="space-y-6">
                {{ $this->form }}

                <div
                    class="fi-form-actions flex flex-wrap items-center gap-3 justify-start border-t border-gray-200 pt-6 dark:border-white/10">
                    @foreach ($this->getFormActions() as $action)
                        {{ $action }}
                    @endforeach
                </div>
            </form>
        </x-filament::section>
    </div>
</x-filament-panels::page>