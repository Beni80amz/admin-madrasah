<?php

namespace App\Http\Controllers;

use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleDriveController extends Controller
{
    protected GoogleDriveService $googleDriveService;

    public function __construct(GoogleDriveService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }

    /**
     * Redirect to Google OAuth consent screen
     */
    public function redirect()
    {
        $authUrl = $this->googleDriveService->getAuthUrl();

        return redirect()->away($authUrl);
    }

    /**
     * Handle OAuth callback from Google
     */
    public function callback(Request $request)
    {
        // Check for errors
        if ($request->has('error')) {
            Log::error('Google OAuth Error: ' . $request->get('error'));

            return redirect()
                ->route('filament.admin.pages.google-drive-settings')
                ->with('error', 'Gagal menghubungkan ke Google Drive: ' . $request->get('error'));
        }

        $code = $request->get('code');

        if (!$code) {
            return redirect()
                ->route('filament.admin.pages.google-drive-settings')
                ->with('error', 'Kode otorisasi tidak ditemukan');
        }

        try {
            $user = Auth::user();

            // Handle OAuth callback and save tokens
            $googleToken = $this->googleDriveService->handleCallback($user, $code);

            // Setup folder structure
            $this->googleDriveService->setupFolderStructure($user);

            return redirect()
                ->route('filament.admin.pages.google-drive-settings')
                ->with('success', 'Google Drive berhasil terhubung! Struktur folder sudah dibuat.');

        } catch (\Exception $e) {
            Log::error('Google OAuth Callback Error: ' . $e->getMessage());

            return redirect()
                ->route('filament.admin.pages.google-drive-settings')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Disconnect Google Drive
     */
    public function disconnect()
    {
        try {
            $user = Auth::user();

            $this->googleDriveService->disconnect($user);

            return redirect()
                ->route('filament.admin.pages.google-drive-settings')
                ->with('success', 'Google Drive berhasil diputuskan.');

        } catch (\Exception $e) {
            Log::error('Google Drive Disconnect Error: ' . $e->getMessage());

            return redirect()
                ->route('filament.admin.pages.google-drive-settings')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Refresh folder structure
     */
    public function refreshFolders()
    {
        try {
            $user = Auth::user();

            if (!$user->isConnectedToGoogleDrive()) {
                return redirect()
                    ->route('filament.admin.pages.google-drive-settings')
                    ->with('error', 'Anda belum terhubung ke Google Drive');
            }

            $this->googleDriveService->setupFolderStructure($user);

            return redirect()
                ->route('filament.admin.pages.google-drive-settings')
                ->with('success', 'Struktur folder berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Google Drive Refresh Folders Error: ' . $e->getMessage());

            return redirect()
                ->route('filament.admin.pages.google-drive-settings')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
