<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'is_synced' => 'boolean',
    ];

    /**
     * Boot the model
     */
    protected static function booted(): void
    {
        // Auto-generate transaction number before creating
        static::creating(function (Income $income) {
            if (empty($income->transaction_number)) {
                $income->transaction_number = self::generateTransactionNumber();
            }
        });
    }

    /**
     * Generate unique transaction number
     * Format: INC-YYYYMMDD-XXXXX
     */
    public static function generateTransactionNumber(): string
    {
        $date = now()->format('Ymd');
        $prefix = "INC-{$date}-";

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

    public function incomeCategory(): BelongsTo
    {
        return $this->belongsTo(IncomeCategory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function feeCategory(): BelongsTo
    {
        return $this->belongsTo(FeeCategory::class);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Payment method options
     */
    public static function getPaymentMethodOptions(): array
    {
        return [
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'qris' => 'QRIS',
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
     * Scope for non-synced incomes (manual entries)
     */
    public function scopeManual($query)
    {
        return $query->where('is_synced', false);
    }

    /**
     * Scope for synced incomes (from Madrasah Pay)
     */
    public function scopeSynced($query)
    {
        return $query->where('is_synced', true);
    }
}
