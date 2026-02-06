<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $data['title'] ?? 'Laporan Keuangan' }}</title>
    <style>
        @page {
            size: {{ $type === 'arus_kas' ? 'A4 landscape' : 'A4 portrait' }};
            margin: 15mm 20mm 15mm 25mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #333;
            line-height: 1.3;
            padding: 15px 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #10b981;
        }

        .header h1 {
            font-size: 14px;
            color: #10b981;
            margin-bottom: 3px;
        }

        .header h2 {
            font-size: 12px;
            color: #333;
            margin-bottom: 2px;
        }

        .header p {
            font-size: 9px;
            color: #666;
        }

        .stats-container {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .stat-box {
            display: table-cell;
            text-align: center;
            padding: 8px 5px;
            border: 1px solid #e5e7eb;
        }

        .stat-box.green { background-color: #d1fae5; }
        .stat-box.red { background-color: #fee2e2; }
        .stat-box.blue { background-color: #dbeafe; }
        .stat-box.purple { background-color: #ede9fe; }
        .stat-box.gray { background-color: #f3f4f6; }

        .stat-number {
            font-size: 18px;
            font-weight: bold;
        }

        .stat-number.green { color: #059669; }
        .stat-number.red { color: #dc2626; }
        .stat-number.blue { color: #2563eb; }
        .stat-number.purple { color: #7c3aed; }
        .stat-number.gray { color: #4b5563; }

        .stat-label {
            font-size: 8px;
            color: #666;
            margin-top: 2px;
        }

        .section-title {
            background-color: #10b981;
            color: white;
            padding: 6px 12px;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 8px;
            margin-top: 15px;
        }

        .section-title.red {
            background-color: #dc2626;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        th {
            background-color: #f3f4f6;
            color: #374151;
            padding: 6px 5px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            border: 1px solid #e5e7eb;
        }

        td {
            padding: 5px;
            border: 1px solid #e5e7eb;
            font-size: 8px;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            background-color: #e5e7eb !important;
            font-weight: bold;
        }

        .income-text { color: #059669; }
        .expense-text { color: #dc2626; }

        .category-badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: bold;
        }

        .badge-green {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-red {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
        }

        .footer-table {
            width: 100%;
            border: none;
        }

        .footer-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .footer-left {
            text-align: left;
            font-size: 8px;
            color: #666;
        }

        .footer-right {
            text-align: right;
            width: 60px;
        }

        .qr-code {
            width: 50px;
            height: 50px;
        }

        /* Cash Flow specific styles */
        .two-column {
            width: 100%;
        }

        .two-column td {
            width: 50%;
            vertical-align: top;
            padding: 5px;
        }

        .column-table {
            width: 100%;
            border-collapse: collapse;
        }

        .column-table th,
        .column-table td {
            border: 1px solid #e5e7eb;
            padding: 4px;
            font-size: 8px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ optional($siteProfile)->nama_madrasah ?? 'MADRASAH' }}</h1>
        <h2>{{ strtoupper($data['title'] ?? 'LAPORAN KEUANGAN') }}</h2>
        <p>Periode: {{ $data['period'] ?? '-' }}</p>
    </div>

    @if($type === 'arus_kas')
        <!-- Cash Flow Stats -->
        <div class="stats-container">
            <div class="stat-box gray">
                <div class="stat-number gray">Rp {{ number_format($data['openingBalance'], 0, ',', '.') }}</div>
                <div class="stat-label">Saldo Awal</div>
            </div>
            <div class="stat-box green">
                <div class="stat-number green">+ {{ number_format($data['totalIncome'], 0, ',', '.') }}</div>
                <div class="stat-label">Total Pemasukan</div>
            </div>
            <div class="stat-box red">
                <div class="stat-number red">- {{ number_format($data['totalExpense'], 0, ',', '.') }}</div>
                <div class="stat-label">Total Pengeluaran</div>
            </div>
            <div class="stat-box blue">
                <div class="stat-number blue">{{ number_format($data['netCashFlow'], 0, ',', '.') }}</div>
                <div class="stat-label">Arus Kas Bersih</div>
            </div>
            <div class="stat-box purple">
                <div class="stat-number purple">Rp {{ number_format($data['closingBalance'], 0, ',', '.') }}</div>
                <div class="stat-label">Saldo Akhir</div>
            </div>
        </div>

        <!-- Two Column Tables for Cash Flow -->
        <table class="two-column" style="border: none;">
            <tr>
                <td style="border: none; padding-right: 10px;">
                    <div class="section-title">PEMASUKAN</div>
                    <table class="column-table">
                        <thead>
                            <tr>
                                <th style="width: 22%;">Tanggal</th>
                                <th style="width: 53%;">Keterangan</th>
                                <th style="width: 25%;" class="text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['incomeItems'] as $index => $item)
                                <tr>
                                    <td>{{ $item->transaction_date->format('d/m/Y') }}</td>
                                    <td>{{ ($index + 1) }}. {{ Str::limit($item->source ?? $item->description, 25) }}</td>
                                    <td class="text-right income-text">+{{ number_format($item->amount, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">Tidak ada data</td></tr>
                            @endforelse
                            <tr class="total-row">
                                <td colspan="2">Total</td>
                                <td class="text-right income-text">+{{ number_format($data['totalIncome'], 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="border: none; padding-left: 10px;">
                    <div class="section-title red">PENGELUARAN</div>
                    <table class="column-table">
                        <thead>
                            <tr>
                                <th style="width: 22%;">Tanggal</th>
                                <th style="width: 53%;">Keterangan</th>
                                <th style="width: 25%;" class="text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['expenseItems'] as $index => $item)
                                <tr>
                                    <td>{{ $item->transaction_date->format('d/m/Y') }}</td>
                                    <td>{{ ($index + 1) }}. {{ Str::limit($item->description, 25) }}</td>
                                    <td class="text-right expense-text">-{{ number_format($item->amount, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">Tidak ada data</td></tr>
                            @endforelse
                            <tr class="total-row">
                                <td colspan="2">Total</td>
                                <td class="text-right expense-text">-{{ number_format($data['totalExpense'], 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

    @else
        <!-- Income/Expense Stats -->
        <div class="stats-container">
            <div class="stat-box {{ $type === 'pemasukan' ? 'green' : 'red' }}">
                <div class="stat-number {{ $type === 'pemasukan' ? 'green' : 'red' }}">
                    Rp {{ number_format($data['total'], 0, ',', '.') }}
                </div>
                <div class="stat-label">{{ $type === 'pemasukan' ? 'Total Pemasukan' : 'Total Pengeluaran' }}</div>
            </div>
            <div class="stat-box blue">
                <div class="stat-number blue">{{ $data['items']->count() }}</div>
                <div class="stat-label">Jumlah Transaksi</div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="section-title {{ $type === 'pengeluaran' ? 'red' : '' }}">
            DAFTAR {{ strtoupper($type === 'pemasukan' ? 'PEMASUKAN' : 'PENGELUARAN') }}
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">Tanggal</th>
                    <th style="width: 18%;">No. Transaksi</th>
                    <th style="width: 18%;">Kategori</th>
                    <th style="width: 30%;">Keterangan</th>
                    <th style="width: 17%;" class="text-right">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['items'] as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->transaction_date->format('d/m/Y') }}</td>
                        <td style="font-size: 7px;">{{ $item->transaction_number }}</td>
                        <td>
                            <span class="category-badge {{ $type === 'pemasukan' ? 'badge-green' : 'badge-red' }}">
                                @if($type === 'pemasukan')
                                    {{ $item->incomeCategory->name ?? '-' }}
                                @else
                                    {{ $item->expenseCategory->name ?? '-' }}
                                @endif
                            </span>
                        </td>
                        <td>{{ Str::limit($item->description ?? $item->source ?? '-', 35) }}</td>
                        <td class="text-right {{ $type === 'pemasukan' ? 'income-text' : 'expense-text' }}">
                            Rp {{ number_format($item->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data untuk periode ini</td>
                    </tr>
                @endforelse
                @if($data['items']->count() > 0)
                    <tr class="total-row">
                        <td colspan="5" class="text-right">GRAND TOTAL</td>
                        <td class="text-right {{ $type === 'pemasukan' ? 'income-text' : 'expense-text' }}">
                            Rp {{ number_format($data['total'], 0, ',', '.') }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endif

    <!-- Footer -->
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    <p>Dokumen ini dicetak pada {{ now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</p>
                    <p>{{ optional($siteProfile)->nama_madrasah ?? 'Madrasah' }} - {{ optional($siteProfile)->alamat ?? 'Alamat' }}</p>
                    <p style="margin-top: 5px; font-size: 7px; color: #999;">Scan QR code untuk verifikasi</p>
                </td>
                <td class="footer-right">
                    @php
                        $verificationUrl = url('/profil/verifikasi');
                        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=50x50&data=' . urlencode($verificationUrl);
                    @endphp
                    <img src="{{ $qrUrl }}" class="qr-code" alt="QR Code">
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
