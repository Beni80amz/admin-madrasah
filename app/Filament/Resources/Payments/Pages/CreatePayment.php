<?php

namespace App\Filament\Resources\Payments\Pages;

use App\Filament\Resources\Payments\PaymentResource;
use App\Models\StudentBill;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $billIds = $data['bill_ids'] ?? [];
        $amountPaidTotal = $data['amount_paid'];
        $records = [];

        if (empty($billIds)) {
            // Fallback just in case, though validation should prevent this
            return parent::handleRecordCreation($data);
        }

        // Generate ONE receipt number for all these payments
        $receiptNumber = \App\Models\Payment::generateReceiptNumber(); // We need to check if we can call this statically or if it's protected
        // generateReceiptNumber is public static based on previous view_file

        // Calculate total remaining to verify? 
        // For simplicity, we trust the logic: We create a payment for each bill.
        // We will allocate the total paid amount? 
        // Or simply: assume full payment for each selected bill (since amount_paid was auto-calculated to be sum).
        // What if user reduced amount_paid? 
        // Logic: Distribute amount_paid across bills? 
        // Simplest valid approach: Allocate to each bill its remaining amount, until money runs out?
        // Or strictly: Create a payment for each bill with its FULL remaining amount, provided the total matches?
        // Let's go with: Loop through bills, pay them off. 
        // BUT if amount_paid != sum(remaining), we have a problem.
        // We should probably enforce that amount_paid == sum(remaining) for bulk, OR implement distribution.
        // Let's implement distribution (pay oldest/first in list first).

        $bills = \App\Models\StudentBill::whereIn('id', $billIds)->get();
        $remainingToAllocate = $amountPaidTotal;

        DB::beginTransaction();
        try {
            $firstPayment = null;

            foreach ($bills as $bill) {
                if ($remainingToAllocate <= 0)
                    break;

                $payAmount = min($remainingToAllocate, $bill->remaining_amount);

                if ($payAmount > 0) {
                    $payment = new \App\Models\Payment();
                    $payment->fill([
                        'student_bill_id' => $bill->id,
                        'user_id' => Auth::id(),
                        'amount_paid' => $payAmount,
                        'payment_date' => $data['payment_date'],
                        'payment_method' => $data['payment_method'],
                        'note' => $data['note'] ?? null,
                        'receipt_number' => $receiptNumber, // Shared receipt number
                    ]);
                    // Disable auto-generation of receipt number in model event if it's already set?
                    // Model event: if (empty($payment->receipt_number)) ... so it works fine.
                    $payment->save();

                    if (!$firstPayment)
                        $firstPayment = $payment;

                    $remainingToAllocate -= $payAmount;
                }
            }

            DB::commit();
            return $firstPayment; // Return one for redirection

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
