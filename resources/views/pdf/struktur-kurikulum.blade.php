<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Struktur Kurikulum</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 5px 10px;
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
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .header p {
            font-size: 9px;
            color: #666;
            margin: 2px 0;
        }

        .header-info {
            font-size: 10px;
            font-weight: bold;
            color: #333;
            margin-top: 8px;
        }

        .title {
            text-align: center;
            margin-bottom: 15px;
        }

        .title h2 {
            font-size: 14px;
            margin: 0;
            color: #1f2937;
        }

        .title span {
            font-size: 10px;
            color: #10b981;
        }

        .grid {
            width: 100%;
        }

        .section {
            margin-bottom: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 10px;
            background: #fafafa;
        }

        .section-header {
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }

        .section-header table {
            width: auto;
        }

        .section-header td {
            vertical-align: middle;
            padding: 0;
            white-space: nowrap;
        }

        .section-badge {
            display: inline-block;
            font-size: 8px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 10px;
            margin-right: 8px;
            vertical-align: middle;
        }

        .badge-a {
            background: #8b5cf6;
            color: white;
        }

        .badge-b {
            background: #f59e0b;
            color: white;
        }

        .badge-c {
            background: #06b6d4;
            color: white;
        }

        .badge-kokurikuler {
            background: #10b981;
            color: white;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #1f2937;
            vertical-align: middle;
        }

        .subject-table {
            width: 100%;
            border-collapse: collapse;
        }

        .subject-table tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .subject-table tr:last-child {
            border-bottom: none;
        }

        .subject-table td {
            padding: 8px 8px;
            vertical-align: middle;
        }

        .subject-table td:first-child {
            width: 70%;
        }

        .subject-table td:last-child {
            width: 30%;
            text-align: right;
        }

        .hours-badge {
            display: inline-block;
            font-size: 8px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 8px;
        }

        .hours-a {
            background: #ede9fe;
            color: #7c3aed;
        }

        .hours-b {
            background: #fef3c7;
            color: #d97706;
        }

        .hours-c {
            background: #cffafe;
            color: #0891b2;
        }

        .hours-kokurikuler {
            background: #d1fae5;
            color: #059669;
        }

        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
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

        .two-column {
            width: 100%;
        }

        .two-column td {
            width: 50%;
            vertical-align: top;
            padding: 0 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $profile->nama_madrasah ?? 'Madrasah' }}</h1>
        <p>{{ $profile->alamat ?? '' }}</p>
        <div class="header-info">Tahun Ajaran {{ $tahunAjaran->nama ?? '-' }}</div>
    </div>

    <div class="title">
        <h2>STRUKTUR KURIKULUM</h2>
        <span>Pembagian Mata Pelajaran dan Beban Belajar</span>
    </div>

    <table class="two-column">
        <tr>
            <td>
                <!-- Kelompok A -->
                <div class="section">
                    <div class="section-header">
                        <table>
                            <tr>
                                <td><span class="section-badge badge-a">Kelompok A</span></td>
                                <td><span class="section-title">Mata Pelajaran Wajib</span></td>
                            </tr>
                        </table>
                    </div>
                    <table class="subject-table">
                        @forelse($subjectsA as $subject)
                            <tr>
                                <td>{{ $subject->nama }}</td>
                                <td><span
                                        class="hours-badge hours-a">{{ $subject->beban_jam_minggu ? $subject->beban_jam_minggu . ' JP/Minggu' : '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" style="text-align:center; color:#999;">Belum ada data</td>
                            </tr>
                        @endforelse
                    </table>
                </div>

                <!-- Kelompok C -->
                <div class="section">
                    <div class="section-header">
                        <table>
                            <tr>
                                <td><span class="section-badge badge-c">Kelompok C</span></td>
                                <td><span class="section-title">Muatan Lokal</span></td>
                            </tr>
                        </table>
                    </div>
                    <table class="subject-table">
                        @forelse($subjectsC as $subject)
                            <tr>
                                <td>{{ $subject->nama }}</td>
                                <td><span
                                        class="hours-badge hours-c">{{ $subject->beban_jam_minggu ? $subject->beban_jam_minggu . ' JP/Minggu' : '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" style="text-align:center; color:#999;">Belum ada data</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </td>
            <td>
                <!-- Kelompok B -->
                <div class="section">
                    <div class="section-header">
                        <table>
                            <tr>
                                <td><span class="section-badge badge-b">Kelompok B</span></td>
                                <td><span class="section-title">Mata Pelajaran Pilihan</span></td>
                            </tr>
                        </table>
                    </div>
                    <table class="subject-table">
                        @forelse($subjectsB as $subject)
                            <tr>
                                <td>{{ $subject->nama }}</td>
                                <td><span
                                        class="hours-badge hours-b">{{ $subject->beban_jam_minggu ? $subject->beban_jam_minggu . ' JP/Minggu' : '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" style="text-align:center; color:#999;">Belum ada data</td>
                            </tr>
                        @endforelse
                    </table>
                </div>

                <!-- Kokurikuler -->
                <div class="section">
                    <div class="section-header">
                        <table>
                            <tr>
                                <td><span class="section-badge badge-kokurikuler">Kokurikuler</span></td>
                                <td><span class="section-title">Kegiatan Kokurikuler</span></td>
                            </tr>
                        </table>
                    </div>
                    <table class="subject-table">
                        @forelse($subjectsKokurikuler as $subject)
                            <tr>
                                <td>{{ $subject->nama }}</td>
                                <td><span
                                        class="hours-badge hours-kokurikuler">{{ $subject->beban_jam_minggu ? $subject->beban_jam_minggu . ' JP/Minggu' : 'Terintegrasi' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" style="text-align:center; color:#999;">Belum ada data</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    <p>Dokumen ini dicetak pada {{ now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</p>
                    <p>{{ $profile->nama_madrasah ?? 'Madrasah' }} - {{ $profile->alamat ?? '' }}</p>
                    <p style="margin-top: 3px; font-size: 6px; color: #999;">Scan QR code untuk verifikasi dokumen</p>
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
</body>

</html>