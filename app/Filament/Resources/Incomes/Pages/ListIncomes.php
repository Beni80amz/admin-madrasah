<?php

namespace App\Filament\Resources\Incomes\Pages;

use App\Filament\Resources\Incomes\IncomeResource;
use App\Models\FeeCategory;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\Payment;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;

class ListIncomes extends ListRecords
{
    protected static string $resource = IncomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncMadrasahPay')
                ->label('Sync Madrasah Pay')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Sync Pembayaran dari Madrasah Pay')
                ->modalDescription('Semua pembayaran dari Madrasah Pay akan diakumulasi per kategori (SPP, Seragam, dll). Data yang sudah ada akan diperbarui. Lanjutkan?')
                ->modalSubmitActionLabel('Ya, Sync Sekarang')
                ->action(function () {
                    $result = $this->syncAggregatedPayments();

                    if ($result['updated'] > 0 || $result['created'] > 0) {
                        Notification::make()
                            ->title('Sync Berhasil!')
                            ->body("Dibuat: {$result['created']} kategori baru, Diperbarui: {$result['updated']} kategori.")
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Tidak Ada Data Baru')
                            ->body('Semua data sudah tersinkronisasi.')
                            ->info()
                            ->send();
                    }
                }),
            CreateAction::make()
                ->label('Tambah Pemasukan')
                ->icon('heroicon-o-plus'),
        ];
    }

    /**
     * Sync payments from Madrasah Pay - AGGREGATED per FeeCategory
     */
    protected function syncAggregatedPayments(): array
    {
        $result = ['created' => 0, 'updated' => 0];

        // Find or create "Madrasah Pay" income category
        $madrasahPayCategory = IncomeCategory::firstOrCreate(
            ['name' => 'Madrasah Pay'],
            ['description' => 'Pemasukan dari pembayaran siswa via Madrasah Pay', 'is_active' => true]
        );

        // Get all fee categories that have payments
        $feeCategories = FeeCategory::all();

        foreach ($feeCategories as $feeCategory) {
            // Calculate total payments for this fee category
            $totalAmount = DB::table('payments')
                ->join('student_bills', 'payments.student_bill_id', '=', 'student_bills.id')
                ->join('fee_items', 'student_bills.fee_item_id', '=', 'fee_items.id')
                ->where('fee_items.fee_category_id', $feeCategory->id)
                ->sum('payments.amount_paid');

            if ($totalAmount > 0) {
                // Check if income already exists for this fee category
                $existingIncome = Income::where('is_synced', true)
                    ->where('fee_category_id', $feeCategory->id)
                    ->first();

                if ($existingIncome) {
                    // Update existing with new total
                    if ((float) $existingIncome->amount !== (float) $totalAmount) {
                        $existingIncome->update([
                            'amount' => $totalAmount,
                            'transaction_date' => now(),
                            'description' => 'Total akumulasi pembayaran ' . $feeCategory->name,
                        ]);
                        $result['updated']++;
                    }
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
                    $result['created']++;
                }
            }
        }

        return $result;
    }
}
