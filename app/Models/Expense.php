<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Approval threshold: Rp 5.000.000
    const APPROVAL_THRESHOLD = 5000000;

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'approved_at' => 'datetime',
        'requires_approval' => 'boolean',
    ];

    /**
     * Boot the model
     */
    protected static function booted(): void
    {
        // Auto-generate transaction number and check approval requirement before creating
        static::creating(function (Expense $expense) {
            if (empty($expense->transaction_number)) {
                $expense->transaction_number = self::generateTransactionNumber();
            }

            // Check if requires approval (amount > 5jt)
            if ((float) $expense->amount > self::APPROVAL_THRESHOLD) {
                $expense->requires_approval = true;
                $expense->status = self::STATUS_PENDING;
            } else {
                $expense->requires_approval = false;
                $expense->status = self::STATUS_APPROVED;
                $expense->approved_at = now();
            }
        });
    }

    /**
     * Generate unique transaction number
     * Format: EXP-YYYYMMDD-XXXXX
     */
    public static function generateTransactionNumber(): string
    {
        $date = now()->format('Ymd');
        $prefix = "EXP-{$date}-";

        // Get the latest transaction number for today
        $latest = static::where('transaction_number', 'like', $prefix . '%')
            ->orderBy('transaction_number', 'desc')
            ->first();

        if ($latest) {
            // Extract the sequence number and increment
            $lastNumber = (int) substr($latest->transaction_number, -5);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }

        return $prefix . $newNumber;
    }

    public function expenseCategory(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get status options
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu Approval',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
        ];
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    /**
     * Payment method options
     */
    public static function getPaymentMethodOptions(): array
    {
        return [
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
        ];
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return self::getPaymentMethodOptions()[$this->payment_method] ?? $this->payment_method ?? '-';
    }

    /**
     * Approve this expense
     */
    public function approve(int $approverId): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $approverId,
            'approved_at' => now(),
        ]);
    }

    /**
     * Reject this expense
     */
    public function reject(int $approverId): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'approved_by' => $approverId,
            'approved_at' => now(),
        ]);
    }

    /**
     * Check if expense is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if expense is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Scope for pending expenses
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for approved expenses
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }
}
