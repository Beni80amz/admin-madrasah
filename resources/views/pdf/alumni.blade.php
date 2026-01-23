<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Data Alumni {{ $siteProfile->nama_madrasah ?? 'Madrasah' }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 30mm 20mm 40mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
            padding: 15px 25px;
        }

        .container {
            padding: 0 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #10b981;
        }

        .header h1 {
            font-size: 18px;
            color: #10b981;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 14px;
            color: #333;
            margin-bottom: 3px;
        }

        .header p {
            font-size: 10px;
            color: #666;
        }

        .stats-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .stat-box {
            display: table-cell;
            text-align: center;
            padding: 10px 5px;
            border: 1px solid #e5e7eb;
            background-color: #d1fae5;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #10b981;
        }

        .stat-label {
            font-size: 9px;
            color: #666;
            margin-top: 3px;
        }

        .section-title {
            background-color: #10b981;
            color: white;
            padding: 8px 15px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th {
            background-color: #f3f4f6;
            color: #374151;
            padding: 8px 10px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid #e5e7eb;
        }

        td {
            padding: 7px 10px;
            border: 1px solid #e5e7eb;
            font-size: 9px;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .year-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            background-color: #fef3c7;
            color: #92400e;
        }

        .footer {
            margin-top: 25px;
            padding-top: 15px;
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
            font-size: 9px;
            color: #666;
        }

        .footer-right {
            text-align: right;
            width: 80px;
        }

        .qr-code {
            width: 70px;
            height: 70px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ $siteProfile->nama_madrasah ?? 'MADRASAH' }}</h1>
            <h2>DATA ALUMNI</h2>
            <p>Tahun Ajaran {{ $tahunAjaran->nama ?? '-' }}</p>
        </div>

        <!-- Stats -->
        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-number">{{ $total }}</div>
                <div class="stat-label">Total Alumni</div>
            </div>
        </div>

        <!-- Alumni Table -->
        <div class="section-title">DAFTAR ALUMNI</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Lengkap</th>
                    <th style="width: 12%;">Tahun Lulus</th>
                    <th style="width: 38%;">Alamat</th>
                    <th style="width: 20%;">Nomor Mobile</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alumni as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $item->nama_lengkap }}</td>
                        <td style="text-align: center;">
                            <span class="year-badge">{{ $item->tahun_lulus }}</span>
                        </td>
                        <td>{{ $item->alamat ?? '-' }}</td>
                        <td>{{ $item->nomor_mobile ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #999;">Belum ada data alumni</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Footer with QR Code -->
        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td class="footer-left">
                        <p>Dokumen ini dicetak pada {{ now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB
                        </p>
                        <p>{{ $siteProfile->nama_madrasah ?? 'Madrasah' }} - {{ $siteProfile->alamat ?? 'Alamat' }}</p>
                        <p style="margin-top: 5px; font-size: 7px; color: #999;">Scan QR code untuk verifikasi dokumen
                        </p>
                    </td>
                    <td class="footer-right">
                        <img src="{{ $qrCodeImage }}" class="qr-code" alt="QR Code Verifikasi">
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>