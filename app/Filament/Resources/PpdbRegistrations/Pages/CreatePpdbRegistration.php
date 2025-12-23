<?php

namespace App\Filament\Resources\PpdbRegistrations\Pages;

use App\Filament\Resources\PpdbRegistrations\PpdbRegistrationResource;
use App\Models\PpdbRegistration;
use Filament\Resources\Pages\CreateRecord;

class CreatePpdbRegistration extends CreateRecord
{
    protected static string $resource = PpdbRegistrationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate No Daftar
        $year = date('Y');
        $count = PpdbRegistration::whereYear('created_at', $year)->count() + 1;
        $data['no_daftar'] = 'PPDB-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Ensure dokumen is array
        if (!isset($data['dokumen'])) {
            $data['dokumen'] = [];
        }

        return $data;
    }
}
