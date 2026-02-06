<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['title'] ?? 'Laporan Keuangan' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
            color: #666;
        }

        .summary-cards {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .card {
            flex: 1;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .card-label {
            font-size: 10px;
            color: #666;
        }

        .card-value {
            font-size: 16px;
            font-weight: bold;
        }

        .card-income .card-value {
            color: #059669;
        }

        .card-expense .card-value {
            color: #dc2626;
        }

        .card-balance .card-value {
            color: #7c3aed;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            background-color: #e5e7eb;
            font-weight: bold;
        }

        .income-total {
            color: #059669;
        }

        .expense-total {
            color: #dc2626;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            padding: 5px;
            background-color: #f0f0f0;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }

        .print-btn {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <button class="print-btn no-print" onclick="window.print()">🖨️ Print / Save PDF</button>

    <div class="header">
        <h1>{{ $data['title'] ?? 'LAPORAN KEUANGAN' }}</h1>
        <p>Periode: {{ $data['period'] ?? '-' }}</p>
    </div>

    @if($type === 'arus_kas')
        {{-- Cash Flow Report --}}
        <div class="summary-cards">
            <div class="card">
                <div class="card-label">Saldo Awal</div>
                <div class="card-value">Rp {{ number_format($data['openingBalance'], 0, ',', '.') }}</div>
            </div>
            <div class="card card-income">
                <div class="card-label">Total Pemasukan</div>
                <div class="card-value">+ Rp {{ number_format($data['totalIncome'], 0, ',', '.') }}</div>
            </div>
            <div class="card card-expense">
                <div class="card-label">Total Pengeluaran</div>
                <div class="card-value">- Rp {{ number_format($data['totalExpense'], 0, ',', '.') }}</div>
            </div>
            <div class="card card-balance">
                <div class="card-label">Saldo Akhir</div>
                <div class="card-value">Rp {{ number_format($data['closingBalance'], 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="section-title">PEMASUKAN</div>
        <table>
            <thead>
                <tr>
                    <th style="width:30px;">No</th>
                    <th>Tanggal</th>
                    <th>No. Transaksi</th>
                    <th>Keterangan</th>
                    <th class="text-right">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['incomeItems'] as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->transaction_date->format('d/m/Y') }}</td>
                        <td>{{ $item->transaction_number }}</td>
                        <td>{{ $item->source ?? $item->description }}</td>
                        <td class="text-right">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
                <tr class="total-row">
                    <td colspan="4">Total Pemasukan</td>
                    <td class="text-right income-total">Rp {{ number_format($data['totalIncome'], 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">PENGELUARAN</div>
        <table>
            <thead>
                <tr>
                    <th style="width:30px;">No</th>
                    <th>Tanggal</th>
                    <th>No. Transaksi</th>
                    <th>Keterangan</th>
                    <th class="text-right">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['expenseItems'] as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->transaction_date->format('d/m/Y') }}</td>
                        <td>{{ $item->transaction_number }}</td>
                        <td>{{ $item->description }}</td>
                        <td class="text-right">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
                <tr class="total-row">
                    <td colspan="4">Total Pengeluaran</td>
                    <td class="text-right expense-total">Rp {{ number_format($data['totalExpense'], 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @else
        {{-- Income or Expense Report --}}
        <table>
            <thead>
                <tr>
                    <th style="width:30px;">No</th>
                    <th>Tanggal</th>
                    <th>No. Transaksi</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th class="text-right">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['items'] as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->transaction_date->format('d/m/Y') }}</td>
                        <td>{{ $item->transaction_number }}</td>
                        <td>
                            @if($type === 'pemasukan')
                                {{ $item->incomeCategory->name ?? '-' }}
                            @else
                                {{ $item->expenseCategory->name ?? '-' }}
                            @endif
                        </td>
                        <td>{{ $item->description ?? $item->source ?? '-' }}</td>
                        <td class="text-right">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
                <tr class="total-row">
                    <td colspan="5">TOTAL</td>
                    <td class="text-right {{ $type === 'pemasukan' ? 'income-total' : 'expense-total' }}">
                        Rp {{ number_format($data['total'], 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    @endif

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </div>
</body>

</html>