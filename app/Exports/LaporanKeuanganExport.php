<?php

namespace App\Exports;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanKeuanganExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected string $type;
    protected Carbon $startDate;
    protected Carbon $endDate;
    protected int $rowNumber = 0;

    public function __construct(string $type, string $startDate, string $endDate)
    {
        $this->type = $type;
        $this->startDate = Carbon::parse($startDate);
        $this->endDate = Carbon::parse($endDate);
    }

    public function collection()
    {
        if ($this->type === 'pemasukan') {
            return Income::with('incomeCategory')
                ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
                ->orderBy('transaction_date')
                ->get();
        } elseif ($this->type === 'pengeluaran') {
            return Expense::with('expenseCategory')
                ->approved()
                ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
                ->orderBy('transaction_date')
                ->get();
        }

        // Arus Kas - combine income and expense
        $incomes = Income::whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->get()
            ->map(function ($item) {
                $item->report_type = 'Pemasukan';
                $item->category_name = $item->incomeCategory->name ?? '-';
                return $item;
            });

        $expenses = Expense::approved()
            ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->get()
            ->map(function ($item) {
                $item->report_type = 'Pengeluaran';
                $item->category_name = $item->expenseCategory->name ?? '-';
                return $item;
            });

        return $incomes->merge($expenses)->sortBy('transaction_date');
    }

    public function headings(): array
    {
        if ($this->type === 'arus_kas') {
            return ['No', 'Tanggal', 'Jenis', 'No. Transaksi', 'Kategori', 'Keterangan', 'Nominal'];
        }

        return ['No', 'Tanggal', 'No. Transaksi', 'Kategori', 'Keterangan', 'Nominal'];
    }

    public function map($row): array
    {
        $this->rowNumber++;

        if ($this->type === 'pemasukan') {
            return [
                $this->rowNumber,
                $row->transaction_date->format('d/m/Y'),
                $row->transaction_number,
                $row->incomeCategory->name ?? '-',
                $row->description ?? $row->source ?? '-',
                $row->amount,
            ];
        } elseif ($this->type === 'pengeluaran') {
            return [
                $this->rowNumber,
                $row->transaction_date->format('d/m/Y'),
                $row->transaction_number,
                $row->expenseCategory->name ?? '-',
                $row->description ?? '-',
                $row->amount,
            ];
        }

        // Arus Kas
        return [
            $this->rowNumber,
            $row->transaction_date->format('d/m/Y'),
            $row->report_type,
            $row->transaction_number,
            $row->category_name,
            $row->description ?? $row->source ?? '-',
            $row->report_type === 'Pemasukan' ? $row->amount : -$row->amount,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function title(): string
    {
        return match ($this->type) {
            'pemasukan' => 'Laporan Pemasukan',
            'pengeluaran' => 'Laporan Pengeluaran',
            'arus_kas' => 'Laporan Arus Kas',
            default => 'Laporan',
        };
    }
}
