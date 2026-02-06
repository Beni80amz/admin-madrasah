<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kwitansi - {{ $payment->receipt_number }}</title>
    <style>
        @page {
            margin: 5mm;
            size: a5 portrait;
        }

        body {
            font-family: sans-serif;
            font-size: 9pt;
            color: #111;
            line-height: 1.15;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
            position: relative;
        }

        /* Header */
        .header {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .header-table {
            width: 100%;
        }

        .logo {
            width: 45px;
        }

        .school-info {
            text-align: center;
        }

        .school-name {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .school-address {
            font-size: 8pt;
        }

        /* Receipt Info */
        .receipt-info {
            text-align: center;
            margin-bottom: 12px;
            background: #eee;
            padding: 4px;
            border: 1px solid #ccc;
        }

        .receipt-title {
            font-weight: bold;
            font-size: 10pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .receipt-number {
            font-family: monospace;
            font-size: 9pt;
        }

        /* Content Layout */
        .content-section {
            width: 100%;
            margin-bottom: 10px;
        }

        /* Info Tables */
        .info-table {
            width: 100%;
            font-size: 9pt;
        }

        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .label {
            width: 95px;
            color: #444;
        }

        .sep {
            width: 10px;
            text-align: center;
        }

        .val {
            font-weight: bold;
        }

        .amount-box {
            margin-top: 10px;
            border: 2px solid #000;
            padding: 8px;
            background: #f9f9f9;
        }

        .amount-table {
            width: 100%;
            border-collapse: collapse;
        }

        .amount-table td {
            vertical-align: middle;
            padding: 4px;
        }

        .amount-val {
            font-size: 14pt;
            font-weight: bold;
            text-align: left;
        }

        .amount-text {
            font-style: italic;
            font-size: 8pt;
            text-align: right;
            color: #444;
        }

        /* Reminder Section */
        .reminder-box {
            margin-top: 5px;
            border: 1px solid #ddd;
            background: #fffbfb;
            padding: 6px;
            border-radius: 4px;
        }

        .reminder-title {
            font-size: 8pt;
            font-weight: bold;
            color: #cc0000;
            margin-bottom: 4px;
            border-bottom: 1px dotted #cc0000;
            display: inline-block;
        }

        .reminder-table {
            width: 100%;
            font-size: 8pt;
            border-collapse: collapse;
        }

        .reminder-table td {
            border-bottom: 1px dotted #e0e0e0;
            padding: 2px 0;
        }

        .reminder-total {
            text-align: right;
            font-weight: bold;
        }

        /* Signature */
        .signature-section {
            margin-top: 15px;
            text-align: center;
        }

        .sig-date {
            font-size: 8pt;
            margin-bottom: 40px;
        }

        .sig-name {
            font-weight: bold;
            text-decoration: underline;
            font-size: 9pt;
        }

        .sig-role {
            font-size: 8pt;
        }

        .footer-note {
            font-size: 7pt;
            color: #666;
            margin-top: 15px;
            text-align: center;
            font-style: italic;
        }

        /* Detail List */
        .detail-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .detail-item {
            margin-bottom: 4px;
            border-bottom: 1px dashed #eee;
            padding-bottom: 2px;
        }

        .detail-status {
            font-size: 8pt;
            font-weight: normal;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="logo">
                        @if($profile && $profile->logo)
                            <img src="{{ public_path('storage/' . $profile->logo) }}" width="45" style="max-height: 45px;">
                        @endif
                    </td>
                    <td class="school-info">
                        <div class="school-name">{{ $profile->nama_madrasah ?? 'Madrasah' }}</div>
                        <div class="school-address">
                            {{ $profile->alamat ?? '' }}<br>
                            @if($profile->no_hp || $profile->email)
                                Telp: {{ $profile->no_hp }} | Email: {{ $profile->email }}
                            @endif
                        </div>
                    </td>
                    <td style="width: 45px;"></td>
                </tr>
            </table>
        </div>

        <!-- Receipt Info -->
        <div class="receipt-info">
            <div class="receipt-title">KWITANSI PEMBAYARAN</div>
            <div class="receipt-number">{{ $payment->receipt_number }}</div>
        </div>

        <!-- Main Content - Vertical Layout for Portrait -->
        <div class="content-section">
            <!-- Student Info -->
            <table class="info-table">
                <tr>
                    <td class="label">Telah Terima Dari</td>
                    <td class="sep">:</td>
                    <td class="val">{{ $student->nama_lengkap }} ({{ $student->kelas }})</td>
                </tr>
                <tr>
                    <td class="label">NIS / NISN</td>
                    <td class="sep">:</td>
                    <td class="val">{{ $student->nis_lokal }} / {{ $student->nisn }}</td>
                </tr>
                <tr>
                    <td class="label" style="vertical-align:top; padding-top:2px;">Rincian Bayar</td>
                    <td class="sep" style="vertical-align:top; padding-top:2px;">:</td>
                    <td class="val">
                        <ul class="detail-list">
                            @foreach($payments as $p)
                                <li class="detail-item">
                                    {{ $p->studentBill->feeItem->name }}{{ $p->studentBill->month ? ' (' . $p->studentBill->month . ')' : '' }}
                                    - <span class="detail-status">{{ $p->studentBill->feeItem->tahunAjaran->nama }} |
                                        {{ ($p->studentBill->status == 'paid') ? 'LUNAS' : 'Sisa: Rp ' . number_format($p->studentBill->remaining_amount, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td class="label">Metode/Tgl</td>
                    <td class="sep">:</td>
                    <td class="val">{{ $payment->payment_method_label }} /
                        {{ $payment->payment_date->format('d-m-Y') }}
                    </td>
                </tr>
                @if($payment->note)
                    <tr>
                        <td class="label">Catatan</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $payment->note }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <!-- Amount Box -->
        <div class="amount-box">
            <table class="amount-table">
                <tr>
                    <td class="amount-val">Rp {{ number_format($payments->sum('amount_paid'), 0, ',', '.') }}</td>
                    <td class="amount-text">Terbilang: {{ $terbilang }} Rupiah</td>
                </tr>
            </table>
        </div>

        <!-- Reminder Section -->
        @if(isset($unpaidBills) && $unpaidBills->count() > 0)
            <div class="reminder-box">
                <div class="reminder-title">Tagihan Lain Belum Lunas (Pengingat):</div>
                <table class="reminder-table">
                    @foreach($unpaidBills as $unpaid)
                        <tr>
                            <td>{{ $unpaid->feeItem->name }}
                                {{ $unpaid->month ? '(' . $unpaid->month . ')' : '' }}
                            </td>
                            <td class="reminder-total">Rp
                                {{ number_format($unpaid->remaining_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    <!-- Total Row -->
                    <tr style="border-top: 1px solid #ccc;">
                        <td style="text-align: right; padding-right: 5px;">Total:</td>
                        <td class="reminder-total">Rp
                            {{ number_format($unpaidBills->sum('remaining_amount'), 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>
        @endif

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="sig-date">
                {{ $profile->kota ?? 'Kota' }},
                {{ $payment->payment_date->locale('id')->translatedFormat('d F Y') }}
            </div>
            <div class="sig-name">{{ $payment->user->name }}</div>
            <div class="sig-role">Petugas Keuangan</div>
        </div>

        <div class="footer-note">
            * Simpan sebagai bukti pembayaran yang sah
            <br>Dicetak: {{ now()->translatedFormat('d/m/Y H:i') }}
        </div>
    </div>
</body>

</html>