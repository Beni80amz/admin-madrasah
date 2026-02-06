<?php

namespace App\Console\Commands;

use App\Models\FeeCategory;
use App\Models\Income;
use App\Models\IncomeCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncExistingPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:sync {--force : Force re-sync all categories}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync aggregated Madrasah Pay payments to Pelacakan Keuangan per category';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting aggregated payment sync...');

        if ($this->option('force')) {
            $deleted = Income::where('is_synced', true)->delete();
            $this->info("Deleted {$deleted} existing synced incomes.");
        }

        // Find or create "Madrasah Pay" income category
        $madrasahPayCategory = IncomeCategory::firstOrCreate(
            ['name' => 'Madrasah Pay'],
            ['description' => 'Pemasukan dari pembayaran siswa via Madrasah Pay', 'is_active' => true]
        );

        $feeCategories = FeeCategory::all();
        $created = 0;
        $updated = 0;

        foreach ($feeCategories as $feeCategory) {
            // Calculate total payments for this fee category
            $totalAmount = DB::table('payments')
                ->join('student_bills', 'payments.student_bill_id', '=', 'student_bills.id')
                ->join('fee_items', 'student_bills.fee_item_id', '=', 'fee_items.id')
                ->where('fee_items.fee_category_id', $feeCategory->id)
                ->sum('payments.amount_paid');

            if ($totalAmount > 0) {
                $existingIncome = Income::where('is_synced', true)
                    ->where('fee_category_id', $feeCategory->id)
                    ->first();

                if ($existingIncome) {
                    if ((float) $existingIncome->amount !== (float) $totalAmount) {
                        $existingIncome->update([
                            'amount' => $totalAmount,
                            'transaction_date' => now(),
                        ]);
                        $updated++;
                        $this->line("  ✓ Updated: {$feeCategory->name} = Rp " . number_format($totalAmount, 0, ',', '.'));
                    }
                } else {
                    Income::create([
                        'income_category_id' => $madrasahPayCategory->id,
                        'user_id' => 1,
                        'fee_category_id' => $feeCategory->id,
                        'amount' => $totalAmount,
                        'transaction_date' => now(),
                        'source' => 'Madrasah Pay - ' . $feeCategory->name,
                        'description' => 'Total akumulasi pembayaran ' . $feeCategory->name,
                        'payment_method' => 'transfer',
                        'is_synced' => true,
                    ]);
                    $created++;
                    $this->line("  + Created: {$feeCategory->name} = Rp " . number_format($totalAmount, 0, ',', '.'));
                }
            }
        }

        $this->newLine();
        $this->info("Sync complete! Created: {$created}, Updated: {$updated}");

        return 0;
    }
}
