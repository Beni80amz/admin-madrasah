<?php

namespace App\Filament\Pages;

use App\Models\Expense;
use App\Models\Income;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use UnitEnum;

class LaporanKeuangan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Keuangan';
    protected static UnitEnum|string|null $navigationGroup = 'Pelacakan Keuangan';
    protected static ?int $navigationSort = 5;

    public ?string $report_type = null;
    public ?string $start_date = null;
    public ?string $end_date = null;
    public bool $showReport = false;
    public array $reportData = [];

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('Admin Keuangan') || auth()->user()->hasRole('Superadmin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function getView(): string
    {
        return 'filament.pages.laporan-keuangan';
    }

    public function getTitle(): string
    {
        return 'Laporan Keuangan';
    }

    public function mount(): void
    {
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('report_type')
                    ->label('Jenis Laporan')
                    ->options([
                        'pemasukan' => 'Laporan Pemasukan',
                        'pengeluaran' => 'Laporan Pengeluaran',
                        'arus_kas' => 'Laporan Arus Kas',
                    ])
                    ->required()
                    ->native(false),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->default(now()->startOfMonth()),
                DatePicker::make('end_date')
                    ->label('Tanggal Akhir')
                    ->required()
                    ->default(now()->endOfMonth()),
            ])
            ->columns(3);
    }

    public function generateReport(): void
    {
        $this->validate([
            'report_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);

        $this->reportData = match ($this->report_type) {
            'pemasukan' => $this->generateIncomeReport($startDate, $endDate),
            'pengeluaran' => $this->generateExpenseReport($startDate, $endDate),
            'arus_kas' => $this->generateCashFlowReport($startDate, $endDate),
            default => [],
        };

        $this->showReport = true;
    }

    protected function generateIncomeReport(Carbon $startDate, Carbon $endDate): array
    {
        $incomes = Income::with('incomeCategory')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date')
            ->get();

        $byCategory = $incomes->groupBy('incomeCategory.name')->map(function ($items) {
            return [
                'items' => $items,
                'total' => $items->sum('amount'),
            ];
        });

        return [
            'title' => 'Laporan Keuangan',
            'period' => $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y'),
            'type' => 'pemasukan',
            'items' => $incomes,
            'byCategory' => $byCategory,
            'total' => $incomes->sum('amount'),
        ];
    }

    protected function generateExpenseReport(Carbon $startDate, Carbon $endDate): array
    {
        $expenses = Expense::with('expenseCategory')
            ->approved()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date')
            ->get();

        $byCategory = $expenses->groupBy('expenseCategory.name')->map(function ($items) {
            return [
                'items' => $items,
                'total' => $items->sum('amount'),
            ];
        });

        return [
            'title' => 'Laporan Keuangan',
            'period' => $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y'),
            'type' => 'pengeluaran',
            'items' => $expenses,
            'byCategory' => $byCategory,
            'total' => $expenses->sum('amount'),
        ];
    }

    protected function generateCashFlowReport(Carbon $startDate, Carbon $endDate): array
    {
        $incomes = Income::whereBetween('transaction_date', [$startDate, $endDate])->get();
        $expenses = Expense::approved()->whereBetween('transaction_date', [$startDate, $endDate])->get();

        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');

        // Get opening balance (all transactions before start date)
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

    public function resetReport(): void
    {
        $this->showReport = false;
        $this->reportData = [];
    }
}
