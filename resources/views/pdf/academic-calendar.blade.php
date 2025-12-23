<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Kalender Akademik {{ $tahunAjaran->nama ?? '-' }}</title>
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
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #10b981;
        }

        .header h1 {
            font-size: 20px;
            color: #10b981;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 14px;
            color: #333;
            margin-bottom: 3px;
        }

        .header p {
            font-size: 11px;
            color: #666;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            background-color: #10b981;
            color: white;
            padding: 8px 15px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
            display: table;
            width: 100%;
        }

        .section-title-left {
            display: table-cell;
            text-align: left;
        }

        .section-title-right {
            display: table-cell;
            text-align: right;
            font-size: 11px;
            font-weight: normal;
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
            font-size: 10px;
            font-weight: bold;
            border: 1px solid #e5e7eb;
        }

        td {
            padding: 7px 10px;
            border: 1px solid #e5e7eb;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .type-kegiatan {
            background-color: #d1fae5;
            color: #065f46;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        .type-libur {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        .type-ujian {
            background-color: #fef3c7;
            color: #92400e;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        .type-raport_pts {
            background-color: #ede9fe;
            color: #5b21b6;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        .type-raport_aas {
            background-color: #fce7f3;
            color: #9d174d;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        .legend {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            page-break-inside: avoid;
        }

        .legend-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 11px;
        }

        .legend-items {
            display: flex;
        }

        .legend-item {
            margin-right: 20px;
            font-size: 10px;
        }

        .legend-color {
            display: inline-block;
            width: 12px;
            height: 12px;
            margin-right: 5px;
            vertical-align: middle;
        }

        .footer {
            margin-top: 30px;
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
            <h2>KALENDER AKADEMIK</h2>
            <p>Tahun Ajaran {{ $tahunAjaran->nama ?? '-' }}</p>
        </div>

        <!-- Semester Ganjil -->
        <div class="section">
            <div class="section-title">
                <span class="section-title-left">SEMESTER GANJIL</span>
                <span class="section-title-right">Jumlah Hari Efektif: {{ $hariEfektifGanjil }} hari</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 25%;">Tanggal</th>
                        <th style="width: 40%;">Kegiatan</th>
                        <th style="width: 35%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($semesterGanjil as $item)
                        @php
                            $dateDisplay = $item->tanggal_mulai->format('d M Y');
                            if ($item->tanggal_selesai && $item->tanggal_selesai != $item->tanggal_mulai) {
                                $dateDisplay = $item->tanggal_mulai->format('d M') . ' - ' . $item->tanggal_selesai->format('d M Y');
                            }
                            $type = match ($item->kategori) {
                                'Hari Libur' => 'libur',
                                'Asesmen/Penilaian', 'Pelaksanaan ATS Ganjil/Genap', 'Pelaksanaan AAS Ganjil/Genap' => 'ujian',
                                'Pembagian Raport PTS Ganjil/Genap' => 'raport_pts',
                                'Pembagian Raport AAS Ganjil/Genap' => 'raport_aas',
                                default => 'kegiatan',
                            };
                        @endphp
                        <tr>
                            <td>{{ $dateDisplay }}</td>
                            <td>{{ $item->nama_kegiatan }}</td>
                            <td>
                                <span class="type-{{ $type }}">{{ $item->keterangan ?? $item->kategori }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: #999;">Belum ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Semester Genap -->
        <div class="section mt-40">
            <div class="section-title">
                <span class="section-title-left">SEMESTER GENAP</span>
                <span class="section-title-right">Jumlah Hari Efektif: {{ $hariEfektifGenap }} hari</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 25%;">Tanggal</th>
                        <th style="width: 40%;">Kegiatan</th>
                        <th style="width: 35%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($semesterGenap as $item)
                        @php
                            $dateDisplay = $item->tanggal_mulai->format('d M Y');
                            if ($item->tanggal_selesai && $item->tanggal_selesai != $item->tanggal_mulai) {
                                $dateDisplay = $item->tanggal_mulai->format('d M') . ' - ' . $item->tanggal_selesai->format('d M Y');
                            }
                            $type = match ($item->kategori) {
                                'Hari Libur' => 'libur',
                                'Asesmen/Penilaian', 'Pelaksanaan ATS Ganjil/Genap', 'Pelaksanaan AAS Ganjil/Genap' => 'ujian',
                                'Pembagian Raport PTS Ganjil/Genap' => 'raport_pts',
                                'Pembagian Raport AAS Ganjil/Genap' => 'raport_aas',
                                default => 'kegiatan',
                            };
                        @endphp
                        <tr>
                            <td>{{ $dateDisplay }}</td>
                            <td>{{ $item->nama_kegiatan }}</td>
                            <td>
                                <span class="type-{{ $type }}">{{ $item->keterangan ?? $item->kategori }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: #999;">Belum ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Legend -->
        <div class="legend">
            <div class="legend-title">Keterangan Warna:</div>
            <div class="legend-items">
                <span class="legend-item">
                    <span class="legend-color" style="background-color: #d1fae5;"></span> Kegiatan Sekolah
                </span>
                <span class="legend-item">
                    <span class="legend-color" style="background-color: #fef3c7;"></span> Ujian / Penilaian
                </span>
                <span class="legend-item">
                    <span class="legend-color" style="background-color: #ede9fe;"></span> Raport PTS
                </span>
                <span class="legend-item">
                    <span class="legend-color" style="background-color: #fce7f3;"></span> Raport AAS
                </span>
                <span class="legend-item">
                    <span class="legend-color" style="background-color: #fee2e2;"></span> Hari Libur
                </span>
            </div>
        </div>

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
                        @php
                            $verificationUrl = url('/profil/verifikasi');
                            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=' . urlencode($verificationUrl);
                        @endphp
                        <img src="{{ $qrUrl }}" class="qr-code" alt="QR Code Verifikasi">
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>