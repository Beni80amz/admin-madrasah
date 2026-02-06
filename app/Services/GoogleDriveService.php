<?php

namespace App\Services;

use App\Models\GoogleToken;
use App\Models\User;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    protected ?GoogleClient $client = null;
    protected ?GoogleDrive $driveService = null;
    protected ?User $user = null;

    /**
     * Initialize Google Client with user's tokens
     */
    public function initializeClient(User $user): GoogleClient
    {
        $this->user = $user;
        $this->client = new GoogleClient();

        $this->client->setClientId(config('google.client_id'));
        $this->client->setClientSecret(config('google.client_secret'));
        $this->client->setRedirectUri(config('google.redirect_uri'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
        $this->client->setScopes(config('google.scopes'));

        $googleToken = $user->googleToken;

        if ($googleToken) {
            $accessToken = [
                'access_token' => $googleToken->getDecryptedAccessToken(),
                'refresh_token' => $googleToken->getDecryptedRefreshToken(),
                'expires_in' => $googleToken->expires_at ? $googleToken->expires_at->diffInSeconds(now()) : 0,
            ];

            $this->client->setAccessToken($accessToken);

            // Refresh token if expired
            if ($this->client->isAccessTokenExpired()) {
                $this->refreshToken($googleToken);
            }
        }

        $this->driveService = new GoogleDrive($this->client);

        return $this->client;
    }

    /**
     * Get OAuth authorization URL
     */
    public function getAuthUrl(): string
    {
        $client = new GoogleClient();
        $client->setClientId(config('google.client_id'));
        $client->setClientSecret(config('google.client_secret'));
        $client->setRedirectUri(config('google.redirect_uri'));
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setScopes(config('google.scopes'));

        return $client->createAuthUrl();
    }

    /**
     * Handle OAuth callback and save tokens
     */
    public function handleCallback(User $user, string $code): GoogleToken
    {
        $client = new GoogleClient();
        $client->setClientId(config('google.client_id'));
        $client->setClientSecret(config('google.client_secret'));
        $client->setRedirectUri(config('google.redirect_uri'));

        $token = $client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            throw new \Exception('Google OAuth Error: ' . $token['error_description'] ?? $token['error']);
        }

        $googleToken = GoogleToken::updateOrCreate(
            ['user_id' => $user->id],
            [
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'] ?? null,
                'expires_at' => isset($token['expires_in'])
                    ? now()->addSeconds($token['expires_in'])
                    : null,
            ]
        );

        return $googleToken;
    }

    /**
     * Refresh access token
     */
    protected function refreshToken(GoogleToken $googleToken): void
    {
        try {
            $refreshToken = $googleToken->getDecryptedRefreshToken();

            if (!$refreshToken) {
                throw new \Exception('No refresh token available');
            }

            $newToken = $this->client->fetchAccessTokenWithRefreshToken($refreshToken);

            if (isset($newToken['error'])) {
                throw new \Exception('Token refresh failed: ' . ($newToken['error_description'] ?? $newToken['error']));
            }

            $googleToken->update([
                'access_token' => $newToken['access_token'],
                'expires_at' => isset($newToken['expires_in'])
                    ? now()->addSeconds($newToken['expires_in'])
                    : null,
            ]);

            $this->client->setAccessToken($newToken);
        } catch (\Exception $e) {
            Log::error('Google Token Refresh Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Setup folder structure in user's Google Drive
     */
    public function setupFolderStructure(User $user): void
    {
        $this->initializeClient($user);
        $googleToken = $user->googleToken;

        if (!$googleToken) {
            throw new \Exception('User is not connected to Google Drive');
        }

        $mainFolderName = config('google.main_folder_name');
        $subfolders = config('google.subfolders');

        // Create or find main folder
        $mainFolderId = $this->findOrCreateFolder($mainFolderName);
        $googleToken->main_folder_id = $mainFolderId;

        // Create subfolders
        $googleToken->planning_folder_id = $this->findOrCreateFolder(
            $subfolders['planning'],
            $mainFolderId
        );

        $googleToken->execution_folder_id = $this->findOrCreateFolder(
            $subfolders['execution'],
            $mainFolderId
        );

        $googleToken->support_folder_id = $this->findOrCreateFolder(
            $subfolders['support'],
            $mainFolderId
        );

        $googleToken->save();
    }

    /**
     * Find existing folder or create new one
     */
    public function findOrCreateFolder(string $name, ?string $parentId = null): string
    {
        // Search for existing folder
        $query = "mimeType = 'application/vnd.google-apps.folder' and name = '{$name}' and trashed = false";

        if ($parentId) {
            $query .= " and '{$parentId}' in parents";
        } else {
            $query .= " and 'root' in parents";
        }

        $results = $this->driveService->files->listFiles([
            'q' => $query,
            'fields' => 'files(id, name)',
            'spaces' => 'drive',
        ]);

        if (count($results->getFiles()) > 0) {
            return $results->getFiles()[0]->getId();
        }

        // Create new folder
        return $this->createFolder($name, $parentId);
    }

    /**
     * Create a new folder
     */
    public function createFolder(string $name, ?string $parentId = null): string
    {
        $fileMetadata = new DriveFile([
            'name' => $name,
            'mimeType' => 'application/vnd.google-apps.folder',
        ]);

        if ($parentId) {
            $fileMetadata->setParents([$parentId]);
        }

        $folder = $this->driveService->files->create($fileMetadata, [
            'fields' => 'id',
        ]);

        return $folder->getId();
    }

    /**
     * Upload file to Google Drive
     */
    public function uploadFile(UploadedFile $file, string $folderId, ?string $customName = null): array
    {
        $fileName = $customName ?? $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $content = file_get_contents($file->getRealPath());

        $fileMetadata = new DriveFile([
            'name' => $fileName,
            'parents' => [$folderId],
        ]);

        $uploadedFile = $this->driveService->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $mimeType,
            'uploadType' => 'multipart',
            'fields' => 'id, name, mimeType, webViewLink, webContentLink, size',
        ]);

        return [
            'id' => $uploadedFile->getId(),
            'name' => $uploadedFile->getName(),
            'mimeType' => $uploadedFile->getMimeType(),
            'webViewLink' => $uploadedFile->getWebViewLink(),
            'webContentLink' => $uploadedFile->getWebContentLink(),
            'size' => $uploadedFile->getSize(),
        ];
    }

    /**
     * Delete file from Google Drive
     */
    public function deleteFile(string $fileId): void
    {
        $this->driveService->files->delete($fileId);
    }

    /**
     * Get file info
     */
    public function getFileInfo(string $fileId): ?array
    {
        try {
            $file = $this->driveService->files->get($fileId, [
                'fields' => 'id, name, mimeType, webViewLink, webContentLink, size',
            ]);

            return [
                'id' => $file->getId(),
                'name' => $file->getName(),
                'mimeType' => $file->getMimeType(),
                'webViewLink' => $file->getWebViewLink(),
                'webContentLink' => $file->getWebContentLink(),
                'size' => $file->getSize(),
            ];
        } catch (\Exception $e) {
            Log::error('Google Drive Get File Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if user is connected to Google Drive
     */
    public function isConnected(User $user): bool
    {
        return $user->googleToken !== null;
    }

    /**
     * Disconnect user from Google Drive
     */
    public function disconnect(User $user): void
    {
        if ($user->googleToken) {
            try {
                $this->initializeClient($user);
                $this->client->revokeToken();
            } catch (\Exception $e) {
                Log::warning('Failed to revoke Google token: ' . $e->getMessage());
            }

            $user->googleToken->delete();
        }
    }

    /**
     * Get Drive service instance
     */
    public function getDriveService(): ?GoogleDrive
    {
        return $this->driveService;
    }
}
