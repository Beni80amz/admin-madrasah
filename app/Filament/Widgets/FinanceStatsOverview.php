<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Student;
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
        $month = now()->format('m');
        $year = now()->format('Y');

        // Total pemasukan dari tabel incomes (all-time)
        $totalIncomes = Income::sum('amount');

        // Total pengeluaran yang sudah approved (all-time)
        $totalExpenses = Expense::approved()->sum('amount');

        // Saldo Kas = Total Pemasukan - Total Pengeluaran
        $saldoKas = $totalIncomes - $totalExpenses;

        // Pemasukan bulan ini
        $incomeThisMonth = Income::whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->sum('amount');

        // Pengeluaran bulan ini (approved only)
        $expenseThisMonth = Expense::approved()
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->sum('amount');

        // Pending approvals
        $pendingApprovals = Expense::pending()->count();

        // Total siswa aktif
        $totalStudents = Student::where('status', 'aktif')
            ->orWhere(function ($query) {
                $query->whereNull('status')->where('is_active', true);
            })
            ->count();

        return [
            Stat::make('Saldo Kas', 'Rp ' . number_format($saldoKas, 0, ',', '.'))
                ->description('Total pemasukan - pengeluaran')
                ->descriptionIcon('heroicon-m-wallet')
                ->color($saldoKas >= 0 ? 'success' : 'danger'),

            Stat::make('Total Pemasukan', 'Rp ' . number_format($totalIncomes, 0, ',', '.'))
                ->description('Seluruh pemasukan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Total Pengeluaran', 'Rp ' . number_format($totalExpenses, 0, ',', '.'))
                ->description('Pengeluaran approved')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('danger'),

            Stat::make('Pemasukan Bulan Ini', 'Rp ' . number_format($incomeThisMonth, 0, ',', '.'))
                ->description('Bulan ' . now()->locale('id')->monthName)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Pengeluaran Bulan Ini', 'Rp ' . number_format($expenseThisMonth, 0, ',', '.'))
                ->description('Bulan ' . now()->locale('id')->monthName)
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('warning'),

            Stat::make('Menunggu Approval', $pendingApprovals)
                ->description('Pengeluaran > Rp 5jt')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingApprovals > 0 ? 'danger' : 'gray'),

            Stat::make('Total Siswa Aktif', $totalStudents)
                ->description('Siswa terdaftar aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
