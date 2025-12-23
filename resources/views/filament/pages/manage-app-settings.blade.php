<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit" size="lg">
                <x-slot name="icon">
                    <x-heroicon-m-check class="w-5 h-5" />
                </x-slot>
                Simpan Pengaturan
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>