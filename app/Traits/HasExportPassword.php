<?php

namespace App\Traits;

use App\Models\AppSetting;
use Filament\Notifications\Notification;

trait HasExportPassword
{
    public bool $showExportModal = false;
    public string $exportPasswordInput = '';
    public string $exportType = '';

    public function openExportModal(string $type)
    {
        $this->exportType = $type;
        $this->exportPasswordInput = '';

        // If no password is set, proceed immediately
        $savedPassword = AppSetting::getExportPassword();
        if (empty($savedPassword)) {
            return $this->executeExport();
        }

        $this->showExportModal = true;
    }

    public function closeExportModal()
    {
        $this->showExportModal = false;
        $this->exportPasswordInput = '';
    }

    public function confirmExport()
    {
        $savedPassword = AppSetting::getExportPassword();

        if ($this->exportPasswordInput === $savedPassword) {
            $this->showExportModal = false;
            return $this->executeExport();
        }

        Notification::make()
            ->title('Password Salah')
            ->body('Silahkan masukan password yang benar atau hubungi Admin Madrasah.')
            ->danger()
            ->send();
    }

    abstract protected function executeExport();
}
