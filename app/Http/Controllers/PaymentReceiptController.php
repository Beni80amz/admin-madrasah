<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\ProfileMadrasah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PaymentReceiptController extends Controller
{
    /**
     * Download payment receipt as PDF
     */
    public function download(Payment $payment)
    {
        $pdf = $this->generatePdf($payment);

        return $pdf->download('kwitansi-' . $payment->receipt_number . '.pdf');
    }

    /**
     * Stream/preview payment receipt as PDF
     */
    public function stream(Payment $payment)
    {
        $pdf = $this->generatePdf($payment);

        return $pdf->stream('kwitansi-' . $payment->receipt_number . '.pdf');
    }

    /**
     * Generate PDF
     */
    private function generatePdf(Payment $payment)
    {
        // Fetch ALL payments with the same receipt number
        $payments = Payment::with(['studentBill.student', 'studentBill.feeItem.feeCategory', 'studentBill.feeItem.tahunAjaran', 'user'])
            ->where('receipt_number', $payment->receipt_number)
            ->get();

        // Use the first payment for common data (student, user, date)
        $mainPayment = $payments->first();
        $bill = $mainPayment->studentBill;
        $student = $bill->student;
        $profile = ProfileMadrasah::first();

        // Calculate total amount paid across all payments in this receipt
        $totalPaid = $payments->sum('amount_paid');
        $terbilang = $this->terbilang($totalPaid);

        // Fetch other unpaid bills for reminder (excluding the ones currently being paid)
        $currentBillIds = $payments->pluck('student_bill_id')->toArray();

        $unpaidBills = \App\Models\StudentBill::with(['feeItem.feeCategory'])
            ->where('student_id', $student->id)
            ->where('status', '!=', 'paid')
            ->orderBy('created_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('pdf.payment-receipt', [
            'payments' => $payments, // Pass collection
            'payment' => $mainPayment, // Keep single variable for backwards compatibility/metadata
            'student' => $student,
            'profile' => $profile,
            'terbilang' => $terbilang,
            'unpaidBills' => $unpaidBills,
        ]);

        $pdf->setPaper('a5', 'landscape');

        return $pdf;
    }

    /**
     * Convert number to Indonesian words
     */
    private function terbilang($angka): string
    {
        $angka = abs($angka);
        $huruf = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
        $temp = '';

        if ($angka < 12) {
            $temp = ' ' . $huruf[$angka];
        } elseif ($angka < 20) {
            $temp = $this->terbilang($angka - 10) . ' belas';
        } elseif ($angka < 100) {
            $temp = $this->terbilang($angka / 10) . ' puluh' . $this->terbilang($angka % 10);
        } elseif ($angka < 200) {
            $temp = ' seratus' . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $temp = $this->terbilang($angka / 100) . ' ratus' . $this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $temp = ' seribu' . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $temp = $this->terbilang($angka / 1000) . ' ribu' . $this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $temp = $this->terbilang($angka / 1000000) . ' juta' . $this->terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $temp = $this->terbilang($angka / 1000000000) . ' milyar' . $this->terbilang(fmod($angka, 1000000000));
        } elseif ($angka < 1000000000000000) {
            $temp = $this->terbilang($angka / 1000000000000) . ' trilyun' . $this->terbilang(fmod($angka, 1000000000000));
        }

        return trim($temp);
    }
}
