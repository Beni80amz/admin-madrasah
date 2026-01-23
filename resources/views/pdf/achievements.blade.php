<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Prestasi {{ $type }} {{ $siteProfile->nama_madrasah ?? 'Madrasah' }}</title>
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
            width: 25%;
            text-align: center;
            padding: 10px 5px;
            border: 1px solid #e5e7eb;
        }

        .stat-box.gold {
            background-color: #fef3c7;
        }

        .stat-box.silver {
            background-color: #f3f4f6;
        }

        .stat-box.bronze {
            background-color: #ffedd5;
        }

        .stat-box.total {
            background-color: #d1fae5;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
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

        .rank-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            color: white;
        }

        .rank-1 {
            background-color: #f59e0b;
        }

        .rank-2 {
            background-color: #6b7280;
        }

        .rank-3 {
            background-color: #ea580c;
        }

        .rank-other {
            background-color: #10b981;
        }

        .category-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .cat-akademik {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .cat-keagamaan {
            background-color: #d1fae5;
            color: #065f46;
        }

        .cat-olahraga {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .cat-seni {
            background-color: #fce7f3;
            color: #9d174d;
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
            <h2>DAFTAR PRESTASI {{ strtoupper($type) }}</h2>
            <p>Tahun Ajaran {{ $tahunAjaran->nama ?? '-' }}</p>
        </div>

        <!-- Stats -->
        <div class="stats-container">
            <div class="stat-box gold">
                <div class="stat-number">{{ $juara1 }}</div>
                <div class="stat-label">Juara 1</div>
            </div>
            <div class="stat-box silver">
                <div class="stat-number">{{ $juara2 }}</div>
                <div class="stat-label">Juara 2</div>
            </div>
            <div class="stat-box bronze">
                <div class="stat-number">{{ $juara3 }}</div>
                <div class="stat-label">Juara 3</div>
            </div>
            <div class="stat-box total">
                <div class="stat-number">{{ $total }}</div>
                <div class="stat-label">Total Prestasi</div>
            </div>
        </div>

        <!-- Achievements Table -->
        <div class="section-title">DAFTAR PRESTASI</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 18%;">Nama Prestasi</th>
                    <th style="width: 14%;">Event</th>
                    <th style="width: 14%;">{{ $type === 'Siswa' ? 'Nama Siswa' : 'Nama Guru' }}</th>
                    @if($type === 'Siswa')
                        <th style="width: 8%;">Kelas</th>
                    @endif
                    <th style="width: 10%;">Jenis</th>
                    <th style="width: 10%;">Peringkat</th>
                    <th style="width: 12%;">Kategori</th>
                    <th style="width: 8%;">Tahun</th>
                </tr>
            </thead>
            <tbody>
                @forelse($achievements as $index => $achievement)
                    @php
                        $rankClass = match ($achievement->peringkat) {
                            1 => 'rank-1',
                            2 => 'rank-2',
                            3 => 'rank-3',
                            default => 'rank-other',
                        };
                        $rankLabel = match ($achievement->peringkat) {
                            1 => 'Juara 1',
                            2 => 'Juara 2',
                            3 => 'Juara 3',
                            4 => 'Harapan 1',
                            5 => 'Harapan 2',
                            6 => 'Harapan 3',
                            default => 'Lainnya',
                        };
                        $catClass = match ($achievement->kategori) {
                            'Akademik' => 'cat-akademik',
                            'Keagamaan' => 'cat-keagamaan',
                            'Olahraga' => 'cat-olahraga',
                            'Seni dan Budaya' => 'cat-seni',
                            default => 'cat-akademik',
                        };
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $achievement->prestasi }}</td>
                        <td>{{ $achievement->event ?? '-' }}</td>
                        <td>{{ $achievement->nama }}</td>
                        @if($type === 'Siswa')
                            <td>{{ $achievement->kelas ?? '-' }}</td>
                        @endif
                        <td style="text-align: center;">{{ $achievement->jenis ?? 'Perorangan' }}</td>
                        <td style="text-align: center;">
                            <span class="rank-badge {{ $rankClass }}">{{ $rankLabel }}</span>
                        </td>
                        <td>
                            <span class="category-badge {{ $catClass }}">{{ $achievement->kategori }}</span>
                        </td>
                        <td style="text-align: center;">{{ $achievement->tahun }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $type === 'Siswa' ? '9' : '8' }}" style="text-align: center; color: #999;">Belum ada
                            data prestasi</td>
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