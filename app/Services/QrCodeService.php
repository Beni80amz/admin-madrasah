<?php

namespace App\Services;

use Carbon\Carbon;

class QrCodeService
{
    /**
     * Secret key for TOTP generation.
     * In production, this should be in .env.
     */
    protected $secret = 'MADRASAH_SECURE_QR_SECRET_KEY_2024';

    /**
     * Generate a time-based token for the QR code.
     * Changes every 30 seconds.
     */
    public function generateQrToken()
    {
        // Simple TOTP implementation: Hash(Secret + TimeWindow)
        // TimeWindow = Current Timestamp / 30
        $timeWindow = floor(Carbon::now()->timestamp / 30);

        return hash_hmac('sha256', $timeWindow, $this->secret);
    }

    /**
     * Validate a token from the client.
     * Checks current and previous window to allow for slight network delay.
     */
    public function validateQrToken($token)
    {
        $currentWindow = floor(Carbon::now()->timestamp / 30);
        $previousWindow = $currentWindow - 1;

        $validTokenCurrent = hash_hmac('sha256', $currentWindow, $this->secret);
        $validTokenPrevious = hash_hmac('sha256', $previousWindow, $this->secret);

        return hash_equals($validTokenCurrent, $token) || hash_equals($validTokenPrevious, $token);
    }

    /**
     * Generate a standardized QR Code for document verification.
     * Includes the madrasah logo in the center and points to the verification URL.
     * Returns raw binary image data (PNG).
     */
    public function generateDocumentVerificationQrCode()
    {
        $verificationUrl = url('/profil/verifikasi');
        $profile = \App\Models\ProfileMadrasah::first();
        $logoPath = $profile && $profile->logo ? storage_path('app/public/' . $profile->logo) : null;

        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->size(100)
            ->errorCorrection('H') // High error correction to allow logo overlay
            ->margin(1); // Small margin

        if ($logoPath && file_exists($logoPath)) {
            $qrCode = $qrCode->merge($logoPath, .2, true); // 20% size, centered
        }

        return $qrCode->generate($verificationUrl);
    }
}
