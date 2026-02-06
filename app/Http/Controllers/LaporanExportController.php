<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKeuanganExport;
use App\Models\Expense;
use App\Models\Income;
use App\Models\ProfileMadrasah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class LaporanExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $type = $request->query('type');
        $startDate = Carbon::parse($request->query('start'));
        $endDate = Carbon::parse($request->query('end'));

        $data = $this->getReportData($type, $startDate, $endDate);
        $siteProfile = ProfileMadrasah::first();

        $pdf = Pdf::loadView('pdf.laporan-keuangan', [
            'data' => $data,
            'type' => $type,
            'siteProfile' => $siteProfile,
        ]);

        $pdf->setPaper('A4', $type === 'arus_kas' ? 'landscape' : 'portrait');
        $pdf->setOptions(['isRemoteEnabled' => true]);

        $filename = 'Laporan-' . ucfirst(str_replace('_', '-', $type)) . '-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }

    public function exportExcel(Request $request)
    {
        $type = $request->query('type');
        $startDate = $request->query('start');
        $endDate = $request->query('end');

        $filename = 'Laporan-' . ucfirst(str_replace('_', '-', $type)) . '-' . Carbon::parse($startDate)->format('Ymd') . '-' . Carbon::parse($endDate)->format('Ymd') . '.xlsx';

        return Excel::download(new LaporanKeuanganExport($type, $startDate, $endDate), $filename);
    }

    protected function getReportData(string $type, Carbon $startDate, Carbon $endDate): array
    {
        return match ($type) {
            'pemasukan' => $this->getIncomeReport($startDate, $endDate),
            'pengeluaran' => $this->getExpenseReport($startDate, $endDate),
            'arus_kas' => $this->getCashFlowReport($startDate, $endDate),
            default => [],
        };
    }

    protected function getIncomeReport(Carbon $startDate, Carbon $endDate): array
    {
        $incomes = Income::with('incomeCategory')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date')
            ->get();

        return [
            'title' => 'Laporan Keuangan',
            'period' => $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y'),
            'type' => 'pemasukan',
            'items' => $incomes,
            'total' => $incomes->sum('amount'),
        ];
    }

    protected function getExpenseReport(Carbon $startDate, Carbon $endDate): array
    {
        $expenses = Expense::with('expenseCategory')
            ->approved()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date')
            ->get();

        return [
            'title' => 'Laporan Keuangan',
            'period' => $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y'),
            'type' => 'pengeluaran',
            'items' => $expenses,
            'total' => $expenses->sum('amount'),
        ];
    }

    protected function getCashFlowReport(Carbon $startDate, Carbon $endDate): array
    {
        $incomes = Income::whereBetween('transaction_date', [$startDate, $endDate])->get();
        $expenses = Expense::approved()->whereBetween('transaction_date', [$startDate, $endDate])->get();

        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');

        $openingIncome = Income::where('transaction_date', '<', $startDate)->sum('amount');
        $openingExpense = Expense::approved()->where('transaction_date', '<', $startDate)->sum('amount');
        $openingBalance = $openingIncome - $openingExpense;

        return [
            'title' => 'Laporan Arus Kas',
            'period' => $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y'),
            'type' => 'arus_kas',
            'openingBalance' => $openingBalance,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netCashFlow' => $totalIncome - $totalExpense,
            'closingBalance' => $openingBalance + ($totalIncome - $totalExpense),
            'incomeItems' => $incomes,
            'expenseItems' => $expenses,
        ];
    }
}
