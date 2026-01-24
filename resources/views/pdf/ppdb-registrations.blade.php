<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Data PPDB {{ optional($siteProfile)->nama_madrasah ?? 'Madrasah' }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm 20mm 15mm 20mm;
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
            padding: 10px 15px;
        }

        .container {
            padding: 0 10px;
        }

        /* KOP Header */
        .kop-header {
            display: table;
            width: 100%;
            border-bottom: 3px double #10b981;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .kop-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }

        .kop-logo img {
            width: 60px;
            height: 60px;
        }

        .kop-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .kop-text h1 {
            font-size: 16px;
            color: #10b981;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .kop-text h2 {
            font-size: 12px;
            color: #333;
            margin-bottom: 2px;
        }

        .kop-text p {
            font-size: 8px;
            color: #666;
        }

        .document-title {
            text-align: center;
            margin-bottom: 15px;
        }

        .document-title h3 {
            font-size: 12px;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .document-title p {
            font-size: 9px;
            color: #666;
        }

        /* Stats */
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
            font-size: 18px;
            font-weight: bold;
            color: #10b981;
        }

        .stat-label {
            font-size: 7px;
            color: #666;
            margin-top: 2px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        th {
            background-color: #10b981;
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-size: 7px;
            font-weight: bold;
            border: 1px solid #10b981;
        }

        td {
            padding: 4px;
            border: 1px solid #e5e7eb;
            font-size: 7px;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .status-badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 6px;
            font-size: 6px;
            font-weight: bold;
            color: white;
        }

        .status-new {
            background-color: #3b82f6;
        }

        .status-verified {
            background-color: #f59e0b;
        }

        .status-accepted {
            background-color: #10b981;
        }

        .status-rejected {
            background-color: #ef4444;
        }

        .status-enrolled {
            background-color: #8b5cf6;
        }

        .gender-badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 6px;
            font-size: 6px;
            font-weight: bold;
            color: white;
        }

        .gender-l {
            background-color: #3b82f6;
        }

        .gender-p {
            background-color: #ec4899;
        }

        /* Footer */
        .footer {
            margin-top: 15px;
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
            font-size: 7px;
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
        <!-- KOP Header -->
        <div class="kop-header">
            <div class="kop-logo">
                @if($siteProfile && $siteProfile->logo)
                    @php
                        $logoPath = storage_path('app/public/' . $siteProfile->logo);
                        $logoData = '';
                        if (file_exists($logoPath)) {
                            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                            $data = file_get_contents($logoPath);
                            $logoData = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        }
                    @endphp
                    @if($logoData)
                        <img src="{{ $logoData }}" alt="Logo">
                    @endif
                @endif
            </div>
            <div class="kop-text">
                <h1>{{ optional($siteProfile)->nama_madrasah ?? 'MADRASAH' }}</h1>
                <p>NSM: {{ optional($siteProfile)->nsm ?? '-' }} | NPSN: {{ optional($siteProfile)->npsn ?? '-' }}</p>
                <p>{{ optional($siteProfile)->alamat ?? 'Alamat Madrasah' }}</p>
                <p>Email: {{ optional($siteProfile)->email ?? '-' }} | Telp: {{ optional($siteProfile)->no_hp ?? '-' }}
                </p>
            </div>
            <div class="kop-logo"></div>
        </div>

        <!-- Document Title -->
        <div class="document-title">
            <h3>Daftar Pendaftaran Peserta Didik Baru (PPDB)</h3>
            <p>Tahun Ajaran {{ $tahunAjaran ?? '-' }}
                @if($filterStatus) | Filter Status: {{ ucfirst($filterStatus) }} @endif
            </p>
        </div>

        <!-- Combined Stats & Origin Table -->
        <table style="width: 100%; border: none; margin-bottom: 20px;">
            <tr>
                <!-- Left: Stats -->
                <td style="width: 40%; vertical-align: top; padding: 0; border: none;">
                    <div class="stats-container" style="display: table; width: 100%;">
                        <div class="stat-box">
                            <div class="stat-number">{{ $total }}</div>
                            <div class="stat-label">Total Pendaftar</div>
                        </div>
                        <div class="stat-box" style="background-color: #dbeafe;">
                            <div class="stat-number" style="color: #3b82f6;">{{ $statusCounts['new'] ?? 0 }}</div>
                            <div class="stat-label">Baru</div>
                        </div>
                        <div class="stat-box" style="background-color: #fef3c7;">
                            <div class="stat-number" style="color: #f59e0b;">{{ $statusCounts['verified'] ?? 0 }}</div>
                            <div class="stat-label">Diverifikasi</div>
                        </div>
                        <div class="stat-box" style="background-color: #d1fae5;">
                            <div class="stat-number" style="color: #10b981;">{{ $statusCounts['accepted'] ?? 0 }}</div>
                            <div class="stat-label">Diterima</div>
                        </div>
                        <div class="stat-box" style="background-color: #fce7f3;">
                            <div class="stat-number" style="color: #ec4899;">{{ $statusCounts['rejected'] ?? 0 }}</div>
                            <div class="stat-label">Ditolak</div>
                        </div>
                    </div>
                </td>

                <!-- Spacer -->
                <td style="width: 2%; border: none;"></td>

                <!-- Right: Origin Stats -->
                <td style="width: 58%; vertical-align: top; padding: 0; border: none;">
                    <table style="width: 100%; border: 1px solid #e5e7eb;">
                        <thead>
                            <tr>
                                <th colspan="7"
                                    style="text-align: center; background-color: #f3f4f6; color: #333; border: 1px solid #e5e7eb;">
                                    Asal Sekolah</th>
                            </tr>
                            <tr>
                                <th
                                    style="background-color: #ffffff; color: #333; text-align: center; border-bottom: 1px solid #e5e7eb;">
                                    RA</th>
                                <th
                                    style="background-color: #ffffff; color: #333; text-align: center; border-bottom: 1px solid #e5e7eb; border-left: 1px solid #e5e7eb;">
                                    TK</th>
                                <th
                                    style="background-color: #ffffff; color: #333; text-align: center; border-bottom: 1px solid #e5e7eb; border-left: 1px solid #e5e7eb;">
                                    BIMBA</th>
                                <th
                                    style="background-color: #ffffff; color: #333; text-align: center; border-bottom: 1px solid #e5e7eb; border-left: 1px solid #e5e7eb;">
                                    PAUD</th>
                                <th
                                    style="background-color: #ffffff; color: #333; text-align: center; border-bottom: 1px solid #e5e7eb; border-left: 1px solid #e5e7eb;">
                                    Orang Tua</th>
                                <th
                                    style="background-color: #ffffff; color: #333; text-align: center; border-bottom: 1px solid #e5e7eb; border-left: 1px solid #e5e7eb;">
                                    Pindahan</th>
                                <th
                                    style="background-color: #ffffff; color: #333; text-align: center; border-bottom: 1px solid #e5e7eb; border-left: 1px solid #e5e7eb;">
                                    Lainnya</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: center; font-weight: bold; border-right: 1px solid #e5e7eb;">
                                    {{ $originCounts['RA'] ?? 0 }}
                                </td>
                                <td style="text-align: center; font-weight: bold; border-right: 1px solid #e5e7eb;">
                                    {{ $originCounts['TK'] ?? 0 }}
                                </td>
                                <td style="text-align: center; font-weight: bold; border-right: 1px solid #e5e7eb;">
                                    {{ $originCounts['BIMBA'] ?? 0 }}
                                </td>
                                <td style="text-align: center; font-weight: bold; border-right: 1px solid #e5e7eb;">
                                    {{ $originCounts['PAUD'] ?? 0 }}
                                </td>
                                <td style="text-align: center; font-weight: bold; border-right: 1px solid #e5e7eb;">
                                    {{ $originCounts['Orang Tua'] ?? 0 }}
                                </td>
                                <td style="text-align: center; font-weight: bold; border-right: 1px solid #e5e7eb;">
                                    {{ $originCounts['Pindahan'] ?? 0 }}
                                </td>
                                <td style="text-align: center; font-weight: bold;">{{ $originCounts['Lainnya'] ?? 0 }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Data Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 12%;">No Daftar</th>
                    <th style="width: 18%;">Nama Lengkap</th>
                    <th style="width: 5%;">L/P</th>
                    <th style="width: 12%;">NISN</th>
                    <th style="width: 10%;">Asal Sekolah</th>
                    <th style="width: 12%;">Nama Ayah</th>
                    <th style="width: 12%;">Nama Ibu</th>
                    <th style="width: 8%;">No HP</th>
                    <th style="width: 7%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $index => $reg)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $reg->no_daftar }}</td>
                        <td>{{ $reg->nama_lengkap }}</td>
                        <td style="text-align: center;">
                            <span class="gender-badge {{ $reg->jenis_kelamin == 'Laki-laki' ? 'gender-l' : 'gender-p' }}">
                                {{ $reg->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}
                            </span>
                        </td>
                        <td>{{ $reg->nisn }}</td>
                        <td>{{ $reg->asal_sekolah }}</td>
                        <td>{{ $reg->nama_ayah }}</td>
                        <td>{{ $reg->nama_ibu }}</td>
                        <td>{{ $reg->no_hp_ortu }}</td>
                        <td style="text-align: center;">
                            @php
                                $statusClass = match ($reg->status) {
                                    'new' => 'status-new',
                                    'verified' => 'status-verified',
                                    'accepted' => 'status-accepted',
                                    'rejected' => 'status-rejected',
                                    'enrolled' => 'status-enrolled',
                                    default => 'status-new'
                                };
                                $statusLabel = match ($reg->status) {
                                    'new' => 'Baru',
                                    'verified' => 'Verif',
                                    'accepted' => 'Terima',
                                    'rejected' => 'Tolak',
                                    'enrolled' => 'Daftar',
                                    default => 'Baru'
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center; color: #999;">Belum ada data pendaftaran</td>
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
                        <p>{{ optional($siteProfile)->nama_madrasah ?? 'Madrasah' }} -
                            {{ optional($siteProfile)->alamat ?? 'Alamat' }}
                        </p>
                        <p style="margin-top: 5px; font-size: 6px; color: #999;">Scan QR code untuk verifikasi dokumen
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