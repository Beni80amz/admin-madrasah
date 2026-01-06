<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Data Siswa {{ $siteProfile?->nama_madrasah ?? 'Madrasah' }}</title>
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
            font-size: 9px;
            color: #333;
            line-height: 1.3;
            padding: 15px 25px;
        }

        .container {
            padding: 0 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #10b981;
        }

        .header h1 {
            font-size: 16px;
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
            background-color: #d1fae5;
        }

        .stat-number {
            font-size: 20px;
            font-weight: bold;
            color: #10b981;
        }

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

        .gender-badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: bold;
            color: white;
        }

        .gender-l {
            background-color: #3b82f6;
        }

        .gender-p {
            background-color: #ec4899;
        }

        .kelas-badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: bold;
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: bold;
            color: white;
        }

        .status-aktif {
            background-color: #10b981;
        }

        .status-tidak {
            background-color: #ef4444;
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
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ $siteProfile?->nama_madrasah ?? 'MADRASAH' }}</h1>
            <h2>DATA SISWA AKTIF</h2>
            <p>Tahun Ajaran {{ $tahunAjaran?->nama ?? '-' }}</p>
        </div>

        <!-- Stats -->
        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-number">{{ $total }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>
            <div class="stat-box" style="background-color: #dbeafe;">
                <div class="stat-number" style="color: #3b82f6;">
                    {{ $totalLakiLaki ?? $students->where('gender', 'Laki-laki')->count() }}
                </div>
                <div class="stat-label">Laki-laki</div>
            </div>
            <div class="stat-box" style="background-color: #fce7f3;">
                <div class="stat-number" style="color: #ec4899;">
                    {{ $totalPerempuan ?? $students->where('gender', 'Perempuan')->count() }}
                </div>
                <div class="stat-label">Perempuan</div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="section-title">DAFTAR SISWA</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 30%;">Nama Lengkap</th>
                    <th style="width: 12%;">NIS Lokal</th>
                    <th style="width: 15%;">NISN</th>
                    <th style="width: 12%;">Kelas</th>
                    <th style="width: 13%;">L/P</th>
                    <th style="width: 13%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $student->nama_lengkap }}</td>
                        <td>{{ $student->nis_lokal }}</td>
                        <td>{{ $student->nisn }}</td>
                        <td style="text-align: center;">
                            <span class="kelas-badge">{{ $student->kelas }}</span>
                        </td>
                        <td style="text-align: center;">
                            <span class="gender-badge {{ $student->gender == 'Laki-laki' ? 'gender-l' : 'gender-p' }}">
                                {{ $student->gender == 'Laki-laki' ? 'L' : 'P' }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <span class="status-badge {{ $student->is_active ? 'status-aktif' : 'status-tidak' }}">
                                {{ $student->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #999;">Belum ada data siswa</td>
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
                        <p>{{ $siteProfile?->nama_madrasah ?? 'Madrasah' }} - {{ $siteProfile?->alamat ?? 'Alamat' }}
                        </p>
                        <p style="margin-top: 5px; font-size: 7px; color: #999;">Scan QR code untuk verifikasi dokumen
                        </p>
                    </td>
                    <td class="footer-right">
                        @php
                            $verificationUrl = url('/profil/verifikasi');
                            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=50x50&data=' . urlencode($verificationUrl);
                        @endphp
                        <img src="{{ $qrUrl }}" class="qr-code" alt="QR Code Verifikasi">
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>