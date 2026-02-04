<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinanceStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return auth()->user()->hasRole('Admin Keuangan') || auth()->user()->hasRole('Superadmin');
    }

    protected function getStats(): array
    {
        $today = now()->format('Y-m-d');
        $month = now()->format('m');
        $year = now()->format('Y');

        $incomeToday = \App\Models\Payment::whereDate('payment_date', $today)->sum('amount_paid');
        $incomeMonth = \App\Models\Payment::whereMonth('payment_date', $month)->whereYear('payment_date', $year)->sum('amount_paid');
        $unpaidBills = \App\Models\StudentBill::where('status', '!=', 'paid')->count();

        return [
            Stat::make('Pemasukan Hari Ini', 'Rp ' . number_format($incomeToday, 0, ',', '.'))
                ->description('Total pembayaran diterima hari ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Pemasukan Bulan Ini', 'Rp ' . number_format($incomeMonth, 0, ',', '.'))
                ->description('Total pembayaran bulan ' . now()->locale('id')->monthName)
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),

            Stat::make('Tagihan Belum Lunas', $unpaidBills)
                ->description('Total item tagihan siswa')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),
        ];
    }
}
