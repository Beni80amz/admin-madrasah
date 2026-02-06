<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class GoogleToken extends Model
{
    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
        'expires_at',
        'main_folder_id',
        'planning_folder_id',
        'execution_folder_id',
        'support_folder_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the token.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get decrypted access token
     */
    public function getDecryptedAccessToken(): ?string
    {
        try {
            return $this->access_token ? Crypt::decryptString($this->access_token) : null;
        } catch (\Exception $e) {
            return $this->access_token;
        }
    }

    /**
     * Get decrypted refresh token
     */
    public function getDecryptedRefreshToken(): ?string
    {
        try {
            return $this->refresh_token ? Crypt::decryptString($this->refresh_token) : null;
        } catch (\Exception $e) {
            return $this->refresh_token;
        }
    }

    /**
     * Set encrypted access token
     */
    public function setAccessTokenAttribute($value): void
    {
        $this->attributes['access_token'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Set encrypted refresh token
     */
    public function setRefreshTokenAttribute($value): void
    {
        $this->attributes['refresh_token'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Check if token is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if folder structure is set up
     */
    public function hasFolderStructure(): bool
    {
        return $this->main_folder_id !== null
            && $this->planning_folder_id !== null
            && $this->execution_folder_id !== null
            && $this->support_folder_id !== null;
    }

    /**
     * Get folder ID for a category
     */
    public function getFolderIdForCategory(string $categoryKey): ?string
    {
        return match ($categoryKey) {
            'planning' => $this->planning_folder_id,
            'execution' => $this->execution_folder_id,
            'support' => $this->support_folder_id,
            default => $this->main_folder_id,
        };
    }
}
