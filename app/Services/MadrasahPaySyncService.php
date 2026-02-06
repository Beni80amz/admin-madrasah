<?php

namespace App\Services;

use App\Models\FeeCategory;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class MadrasahPaySyncService
{
    /**
     * Sync agregat pemasukan dari Madrasah Pay per kategori
     * 
     * @param string $period Periode sinkronisasi (misal: "Januari 2026" atau "2025/2026")
     * @param string|null $startDate Format Y-m-d
     * @param string|null $endDate Format Y-m-d
     */
    public function syncIncomeByCategory(string $period, ?string $startDate = null, ?string $endDate = null): array
    {
        $results = [];

        // Pastikan ada kategori "Madrasah Pay" di income_categories
        $madrasahPayCategory = IncomeCategory::firstOrCreate(
            ['name' => 'Madrasah Pay'],
            ['description' => 'Pemasukan dari pembayaran siswa via Madrasah Pay', 'is_active' => true]
        );

        // Ambil semua kategori biaya dari Madrasah Pay
        $feeCategories = FeeCategory::all();

        foreach ($feeCategories as $feeCategory) {
            // Hitung total pembayaran per kategori
            $query = Payment::query()
                ->join('student_bills', 'payments.student_bill_id', '=', 'student_bills.id')
                ->join('fee_items', 'student_bills.fee_item_id', '=', 'fee_items.id')
                ->where('fee_items.fee_category_id', $feeCategory->id);

            if ($startDate) {
                $query->whereDate('payments.payment_date', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('payments.payment_date', '<=', $endDate);
            }

            $totalAmount = $query->sum('payments.amount_paid');

            if ($totalAmount > 0) {
                // Cek apakah sudah ada income sync untuk kategori dan periode ini
                $existingIncome = Income::where('is_synced', true)
                    ->where('fee_category_id', $feeCategory->id)
                    ->where('period', $period)
                    ->first();

                if ($existingIncome) {
                    // Update amount yang sudah ada
                    $existingIncome->update(['amount' => $totalAmount]);
                    $results[] = [
                        'category' => $feeCategory->name,
                        'period' => $period,
                        'amount' => $totalAmount,
                        'action' => 'updated',
                    ];
                } else {
                    // Buat income baru
                    Income::create([
                        'income_category_id' => $madrasahPayCategory->id,
                        'user_id' => auth()->id() ?? 1,
                        'fee_category_id' => $feeCategory->id,
                        'amount' => $totalAmount,
                        'transaction_date' => now(),
                        'period' => $period,
                        'source' => 'Madrasah Pay - ' . $feeCategory->name,
                        'description' => 'Akumulasi pembayaran ' . $feeCategory->name . ' periode ' . $period,
                        'payment_method' => 'transfer',
                        'is_synced' => true,
                    ]);
                    $results[] = [
                        'category' => $feeCategory->name,
                        'period' => $period,
                        'amount' => $totalAmount,
                        'action' => 'created',
                    ];
                }
            }
        }

        return $results;
    }

    /**
     * Get summary pemasukan dari Madrasah Pay (untuk dashboard)
     */
    public function getMadrasahPaySummary(?string $startDate = null, ?string $endDate = null): array
    {
        $query = Payment::query();

        if ($startDate) {
            $query->whereDate('payment_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('payment_date', '<=', $endDate);
        }

        $totalPayments = $query->sum('amount_paid');
        $countPayments = $query->count();

        // Per category summary
        $perCategory = DB::table('payments')
            ->join('student_bills', 'payments.student_bill_id', '=', 'student_bills.id')
            ->join('fee_items', 'student_bills.fee_item_id', '=', 'fee_items.id')
            ->join('fee_categories', 'fee_items.fee_category_id', '=', 'fee_categories.id')
            ->when($startDate, fn($q) => $q->whereDate('payments.payment_date', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('payments.payment_date', '<=', $endDate))
            ->select('fee_categories.name', DB::raw('SUM(payments.amount_paid) as total'))
            ->groupBy('fee_categories.id', 'fee_categories.name')
            ->get();

        return [
            'total' => $totalPayments,
            'count' => $countPayments,
            'per_category' => $perCategory,
        ];
    }
}
