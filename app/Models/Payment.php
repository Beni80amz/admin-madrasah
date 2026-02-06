<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public static function getPaymentMethodOptions(): array
    {
        return [
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'qris' => 'QRIS',
        ];
    }

    /**
     * Boot the model
     */
    protected static function booted(): void
    {
        // After creating a payment, update the student bill AND sync aggregated total
        static::created(function (Payment $payment) {
            $bill = $payment->studentBill;
            if ($bill) {
                $bill->paid_amount = (float) $bill->paid_amount + (float) $payment->amount_paid;
                $bill->save();
                $bill->updatePaymentStatus();

                // Auto-sync aggregated total to Pelacakan Keuangan
                static::syncAggregatedToIncome($payment);
            }
        });

        // After deleting a payment, update the student bill AND re-sync aggregated total
        static::deleted(function (Payment $payment) {
            $bill = $payment->studentBill;
            if ($bill) {
                $bill->paid_amount = (float) $bill->paid_amount - (float) $payment->amount_paid;
                if ($bill->paid_amount < 0) {
                    $bill->paid_amount = 0;
                }
                $bill->save();
                $bill->updatePaymentStatus();

                // Re-sync aggregated total (will recalculate without deleted payment)
                static::syncAggregatedToIncome($payment);
            }
        });

        // Auto-generate receipt number before creating
        static::creating(function (Payment $payment) {
            if (empty($payment->receipt_number)) {
                $payment->receipt_number = self::generateReceiptNumber();
            }
        });
    }

    /**
     * Sync AGGREGATED payment total to Income per FeeCategory
     */
    protected static function syncAggregatedToIncome(Payment $payment): void
    {
        $bill = $payment->studentBill;
        $feeItem = $bill?->feeItem;
        $feeCategory = $feeItem?->feeCategory;

        if (!$feeCategory) {
            return;
        }

        // Find or create "Madrasah Pay" income category
        $madrasahPayCategory = IncomeCategory::firstOrCreate(
            ['name' => 'Madrasah Pay'],
            ['description' => 'Pemasukan dari pembayaran siswa via Madrasah Pay', 'is_active' => true]
        );

        // Calculate TOTAL payments for this fee category
        $totalAmount = \DB::table('payments')
            ->join('student_bills', 'payments.student_bill_id', '=', 'student_bills.id')
            ->join('fee_items', 'student_bills.fee_item_id', '=', 'fee_items.id')
            ->where('fee_items.fee_category_id', $feeCategory->id)
            ->sum('payments.amount_paid');

        // Find existing income for this fee category
        $existingIncome = Income::where('is_synced', true)
            ->where('fee_category_id', $feeCategory->id)
            ->first();

        if ($totalAmount > 0) {
            if ($existingIncome) {
                // Update existing with new total
                $existingIncome->update([
                    'amount' => $totalAmount,
                    'transaction_date' => now(),
                ]);
            } else {
                // Create new aggregated income
                Income::create([
                    'income_category_id' => $madrasahPayCategory->id,
                    'user_id' => auth()->id() ?? 1,
                    'fee_category_id' => $feeCategory->id,
                    'amount' => $totalAmount,
                    'transaction_date' => now(),
                    'source' => 'Madrasah Pay - ' . $feeCategory->name,
                    'description' => 'Total akumulasi pembayaran ' . $feeCategory->name,
                    'payment_method' => 'transfer',
                    'is_synced' => true,
                ]);
            }
        } elseif ($existingIncome) {
            // No payments left, delete the income record
            $existingIncome->delete();
        }
    }

    /**
     * Generate unique receipt number
     * Format: INV-YYYYMMDD-XXXXX
     */
    public static function generateReceiptNumber(): string
    {
        $date = now()->format('Ymd');
        $prefix = "INV-{$date}-";

        // Get the latest receipt number for today
        $latest = static::where('receipt_number', 'like', $prefix . '%')
            ->orderBy('receipt_number', 'desc')
            ->first();

        if ($latest) {
            // Extract the sequence number and increment
            $lastNumber = (int) substr($latest->receipt_number, -5);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }

        return $prefix . $newNumber;
    }

    public function studentBill(): BelongsTo
    {
        return $this->belongsTo(StudentBill::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get student through bill relationship
     */
    public function getStudentAttribute()
    {
        return $this->studentBill?->student;
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount_paid, 0, ',', '.');
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return self::getPaymentMethodOptions()[$this->payment_method] ?? $this->payment_method;
    }
}
