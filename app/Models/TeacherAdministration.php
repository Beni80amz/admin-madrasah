<?php

namespace App\Models;

use App\Enums\AdministrationCategory;
use App\Enums\AdministrationSubcategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAdministration extends Model
{
    protected $fillable = [
        'user_id',
        'category',
        'subcategory',
        'file_name',
        'google_drive_file_id',
        'file_url',
        'web_view_link',
        'mime_type',
        'file_size',
        'status',
        'verified_by',
        'verified_at',
        'notes',
        'academic_year',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'file_size' => 'integer',
    ];

    /**
     * Status options
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';

    public static function statusOptions(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SUBMITTED => 'Diajukan',
            self::STATUS_VERIFIED => 'Terverifikasi',
            self::STATUS_REJECTED => 'Ditolak',
        ];
    }

    /**
     * Get the teacher/user that owns this administration file.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the verifier user.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get category enum
     */
    public function getCategoryEnumAttribute(): ?AdministrationCategory
    {
        return AdministrationCategory::tryFrom($this->category);
    }

    /**
     * Get subcategory enum
     */
    public function getSubcategoryEnumAttribute(): ?AdministrationSubcategory
    {
        return AdministrationSubcategory::tryFrom($this->subcategory);
    }

    /**
     * Get category label
     */
    public function getCategoryLabelAttribute(): string
    {
        return $this->category_enum?->label() ?? $this->category;
    }

    /**
     * Get subcategory label
     */
    public function getSubcategoryLabelAttribute(): string
    {
        return $this->subcategory_enum?->label() ?? $this->subcategory;
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::statusOptions()[$this->status] ?? $this->status;
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return '-';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->file_size;
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Scope for user's files
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for category
     */
    public function scopeForCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if file is verified
     */
    public function isVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    /**
     * Mark as verified
     */
    public function markAsVerified(int $verifierId, ?string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_VERIFIED,
            'verified_by' => $verifierId,
            'verified_at' => now(),
            'notes' => $notes,
        ]);
    }

    /**
     * Mark as rejected
     */
    public function markAsRejected(int $verifierId, ?string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'verified_by' => $verifierId,
            'verified_at' => now(),
            'notes' => $notes,
        ]);
    }
}
