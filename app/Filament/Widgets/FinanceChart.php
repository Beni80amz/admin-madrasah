<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Payment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class FinanceChart extends ChartWidget
{
    protected static ?int $sort = 2;

    public function getHeading(): string
    {
        return 'Arus Kas 6 Bulan Terakhir';
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole('Admin Keuangan') || auth()->user()->hasRole('Superadmin');
    }

    protected function getData(): array
    {
        $months = [];
        $incomeData = [];
        $expenseData = [];

        // Ambil data 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('m');
            $year = $date->format('Y');

            $months[] = $date->locale('id')->shortMonthName . ' ' . $date->format('y');

            // Total pemasukan (incomes + payments)
            $incomeAmount = Income::whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->sum('amount');
            $paymentAmount = Payment::whereMonth('payment_date', $month)
                ->whereYear('payment_date', $year)
                ->sum('amount_paid');
            $incomeData[] = $incomeAmount + $paymentAmount;

            // Total pengeluaran (approved only)
            $expenseAmount = Expense::approved()
                ->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->sum('amount');
            $expenseData[] = $expenseAmount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $incomeData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'fill' => true,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => $expenseData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.5)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'fill' => true,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => "function(value) { return 'Rp ' + value.toLocaleString('id-ID'); }",
                    ],
                ],
            ],
            'plugins' => [
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context) { return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID'); }",
                    ],
                ],
            ],
        ];
    }
}
