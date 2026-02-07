<?php

namespace App\Filament\Resources\TeacherAdministrations\Pages;

use App\Enums\AdministrationCategory;
use App\Filament\Resources\TeacherAdministrations\TeacherAdministrationResource;
use App\Models\TeacherAdministration;
use App\Services\GoogleDriveService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateTeacherAdministration extends CreateRecord
{
    protected static string $resource = TeacherAdministrationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        // Check if user is connected to Google Drive
        if (!$user->isConnectedToGoogleDrive()) {
            Notification::make()
                ->title('Google Drive Belum Terhubung')
                ->body('Silakan hubungkan akun Google Drive Anda terlebih dahulu.')
                ->danger()
                ->send();

            $this->halt();
        }

        // Get the temporary file
        $tempFile = $data['temp_file'] ?? null;

        if (!$tempFile) {
            Notification::make()
                ->title('File Tidak Ditemukan')
                ->body('Silakan pilih file yang akan diunggah.')
                ->danger()
                ->send();

            $this->halt();
        }

        try {
            $googleDriveService = app(GoogleDriveService::class);
            $googleDriveService->initializeClient($user);

            // Determine the parent folder based on category
            $categoryEnum = AdministrationCategory::tryFrom($data['category']);
            $folderKey = $categoryEnum?->folderKey() ?? 'planning';
            $parentFolderId = $user->googleToken->getFolderIdForCategory($folderKey);

            if (!$parentFolderId) {
                throw new \Exception('Folder kategori tidak ditemukan. Silakan segarkan struktur folder di menu Pengaturan Google Drive.');
            }

            // Determine the sub-category folder
            $subcategory = $data['subcategory'] ?? null;
            if ($subcategory) {
                $subcategoryEnum = \App\Enums\AdministrationSubcategory::tryFrom($subcategory);
                $subfolderName = $subcategoryEnum ? $subcategoryEnum->label() : $subcategory;

                // Find or create sub-folder inside the category folder
                $folderId = $googleDriveService->findOrCreateFolder($subfolderName, $parentFolderId);
            } else {
                $folderId = $parentFolderId;
            }

            $disk = Storage::disk('public');

            // Check if file exists on disk
            if (!$disk->exists($tempFile)) {
                // Try checking if it's still in livewire-tmp
                // (Sometimes Filament hasn't moved it yet)
                $msg = "File tidak ditemukan di storage: " . $tempFile;
                \Log::error($msg);
                throw new \Exception('File gagal diproses oleh sistem. Silakan coba unggah kembali.');
            }

            // Get file info using Storage abstraction
            $filePath = $disk->path($tempFile);
            $mimeType = mime_content_type($filePath);
            $fileSize = $disk->size($tempFile);
            $fileName = basename($tempFile);

            \Log::info('Upload Debug - Data:', $data);
            \Log::info('Upload Debug - Temp File:', ['path' => $tempFile, 'base' => $fileName]);

            $originalName = $data['file_name'] ?? $fileName;

            \Log::info('Upload Debug - Original Name:', ['name' => $originalName]);

            // Create a temporary UploadedFile object for the service
            // We use the absolute path but avoid calling mime_content_type directly
            $uploadedFile = new \Illuminate\Http\UploadedFile(
                $filePath,
                $fileName,
                $mimeType,
                null,
                true
            );

            // Upload to Google Drive using original name
            $result = $googleDriveService->uploadFile($uploadedFile, $folderId, $originalName);

            // Clean up temp file
            $disk->delete($tempFile);

            // Set the data for database
            $data['user_id'] = $user->id;
            $data['file_name'] = $result['name'];
            $data['google_drive_file_id'] = $result['id'];
            $data['file_url'] = $result['webContentLink'] ?? null;
            $data['web_view_link'] = $result['webViewLink'] ?? null;
            $data['mime_type'] = $result['mimeType'] ?? null;
            $data['file_size'] = $result['size'] ?? null;
            $data['status'] = TeacherAdministration::STATUS_SUBMITTED;

            // Remove temp_file from data as it's not a database field
            unset($data['temp_file']);

        } catch (\Exception $e) {
            // Clean up temp file on error if it exists
            if (isset($tempFile) && Storage::disk('public')->exists($tempFile)) {
                Storage::disk('public')->delete($tempFile);
            }

            \Log::error('Upload Error: ' . $e->getMessage());

            Notification::make()
                ->title('Gagal Upload ke Google Drive')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->halt();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Berkas berhasil diunggah ke Google Drive';
    }
}
