<?php

namespace App\Filament\Resources\HeroSliders\Pages;

use App\Filament\Resources\HeroSliders\HeroSliderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHeroSlider extends CreateRecord
{
    protected static string $resource = HeroSliderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $this->dispatch('swal:success', [
            'title' => 'Data Tersimpan!',
            'text' => 'Hero slider baru berhasil ditambahkan.',
        ]);
    }
}
