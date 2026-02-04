<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentBill extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS_UNPAID = 'unpaid';
    const STATUS_PARTIALLY_PAID = 'partially_paid';
    const STATUS_PAID = 'paid';

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_UNPAID => 'Belum Lunas',
            self::STATUS_PARTIALLY_PAID => 'Cicilan',
            self::STATUS_PAID => 'Lunas',
        ];
    }

    public static function getMonthOptions(): array
    {
        return [
            'Januari' => 'Januari',
            'Februari' => 'Februari',
            'Maret' => 'Maret',
            'April' => 'April',
            'Mei' => 'Mei',
            'Juni' => 'Juni',
            'Juli' => 'Juli',
            'Agustus' => 'Agustus',
            'September' => 'September',
            'Oktober' => 'Oktober',
            'November' => 'November',
            'Desember' => 'Desember',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function feeItem(): BelongsTo
    {
        return $this->belongsTo(FeeItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get remaining amount to be paid
     */
    public function getRemainingAmountAttribute(): float
    {
        return (float) $this->total_amount - (float) $this->paid_amount;
    }

    /**
     * Check if bill is fully paid
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Check if bill has any payment
     */
    public function hasPayment(): bool
    {
        return (float) $this->paid_amount > 0;
    }

    /**
     * Update status based on paid amount
     */
    public function updatePaymentStatus(): void
    {
        $remaining = $this->remaining_amount;

        if ($remaining <= 0) {
            $this->status = self::STATUS_PAID;
        } elseif ($this->hasPayment()) {
            $this->status = self::STATUS_PARTIALLY_PAID;
        } else {
            $this->status = self::STATUS_UNPAID;
        }

        $this->save();
    }

    /**
     * Scope for unpaid bills only
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', self::STATUS_UNPAID);
    }

    /**
     * Scope for partially paid bills
     */
    public function scopePartiallyPaid($query)
    {
        return $query->where('status', self::STATUS_PARTIALLY_PAID);
    }

    /**
     * Scope for paid bills
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    /**
     * Scope for outstanding bills (unpaid or partially paid)
     */
    public function scopeOutstanding($query)
    {
        return $query->whereIn('status', [self::STATUS_UNPAID, self::STATUS_PARTIALLY_PAID]);
    }

    /**
     * Get bill description for display
     */
    public function getBillDescriptionAttribute(): string
    {
        $description = $this->feeItem?->feeCategory?->name ?? 'Tagihan';

        if ($this->month) {
            $description .= ' - ' . $this->month;
        }

        return $description;
    }
}
