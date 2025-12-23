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
            font-size: 7px;
            color: #333;
            line-height: 1.2;
            padding: 15px 25px;
        }

        .container {
            padding: 0 20px;
            margin: 0 auto;
            max-width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid #10b981;
        }

        .header h1 {
            font-size: 14px;
            color: #10b981;
            margin-bottom: 3px;
        }

        .header h2 {
            font-size: 11px;
            color: #333;
            margin-bottom: 2px;
        }

        .header p {
            font-size: 9px;
            color: #666;
        }

        .semester-title {
            background-color: #10b981;
            color: white;
            padding: 5px 10px;
            font-size: 10px;
            font-weight: bold;
            margin: 10px 0 8px 0;
            display: table;
            width: 100%;
        }

        .semester-title-left {
            display: table-cell;
            text-align: left;
        }

        .semester-title-right {
            display: table-cell;
            text-align: right;
            font-weight: normal;
        }

        .months-grid {
            width: 100%;
        }

        .months-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .month-container {
            display: table-cell;
            width: 50%;
            padding: 0 4px;
            vertical-align: top;
        }

        .month-title {
            background-color: #166534;
            color: white;
            text-align: center;
            padding: 4px;
            font-size: 9px;
            font-weight: bold;
        }

        .calendar-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .calendar-table th {
            background-color: #f0fdf4;
            color: #166534;
            padding: 2px;
            text-align: center;
            font-size: 7px;
            font-weight: bold;
            border: 1px solid #d1d5db;
        }

        .calendar-table th.sunday {
            color: #dc2626;
        }

        .calendar-table td {
            padding: 2px;
            text-align: center;
            font-size: 8px;
            border: 1px solid #d1d5db;
            height: 18px;
            vertical-align: middle;
        }

        .calendar-table td.empty {
            background-color: #f9fafb;
        }

        .calendar-table td.libur {
            background-color: #fecaca;
            color: #991b1b;
            font-weight: bold;
        }

        .calendar-table td.ujian {
            background-color: #fef08a;
            color: #854d0e;
            font-weight: bold;
        }

        .calendar-table td.raport {
            background-color: #c4b5fd;
            color: #5b21b6;
            font-weight: bold;
        }

        .calendar-table td.kegiatan {
            background-color: #86efac;
            color: #166534;
            font-weight: bold;
        }

        .month-stats {
            display: table;
            width: 100%;
            margin-top: 2px;
            font-size: 7px;
        }

        .stat-item {
            display: table-cell;
            text-align: center;
            padding: 2px;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
        }

        .stat-label {
            font-weight: bold;
            color: #166534;
        }

        .stat-value {
            font-weight: bold;
        }

        .month-events {
            margin-top: 3px;
            font-size: 6px;
            line-height: 1.3;
            padding: 2px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .event-item {
            margin-bottom: 1px;
        }

        .event-date {
            font-weight: bold;
            color: #166534;
        }

        .legend {
            margin-top: 15px;
            padding: 8px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .legend-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 9px;
        }

        .legend-items {
            display: table;
            width: 100%;
        }

        .legend-item {
            display: table-cell;
            font-size: 7px;
            padding-right: 15px;
        }

        .legend-color {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin-right: 3px;
            vertical-align: middle;
        }

        .legend-color.libur {
            background-color: #fecaca;
        }

        .legend-color.ujian {
            background-color: #fef08a;
        }

        .legend-color.raport {
            background-color: #c4b5fd;
        }

        .legend-color.kegiatan {
            background-color: #86efac;
        }

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
            width: 70px;
        }

        .qr-code {
            width: 60px;
            height: 60px;
        }

        .summary-box {
            margin-top: 10px;
            padding: 8px;
            background-color: #f0fdf4;
            border: 1px solid #10b981;
            text-align: center;
        }

        .summary-box p {
            font-size: 9px;
            margin: 2px 0;
        }

        .summary-box .total {
            font-size: 11px;
            font-weight: bold;
            color: #166534;
        }

        .page-break {
            page-break-after: always;
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
        <div class="semester-title">
            <span class="semester-title-left">SEMESTER GANJIL (Juli - Desember {{ $tahunGanjil }})</span>
            <span class="semester-title-right">Jumlah Hari Efektif: {{ $hariEfektifGanjil }} Hari</span>
        </div>

        @foreach(array_chunk($semesterGanjilMonths, 2) as $rowIndex => $monthsRow)
            <div class="months-row">
                @foreach($monthsRow as $monthData)
                    <div class="month-container">
                        <div class="month-title">{{ $monthData['name'] }} {{ $monthData['year'] }}</div>
                        <table class="calendar-table">
                            <thead>
                                <tr>
                                    <th class="sunday">Ahad</th>
                                    <th>Senin</th>
                                    <th>Selasa</th>
                                    <th>Rabu</th>
                                    <th>Kamis</th>
                                    <th>Jum'a</th>
                                    <th>Sabtu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthData['weeks'] as $week)
                                    <tr>
                                        @foreach($week as $day)
                                            @if($day === null)
                                                <td class="empty"></td>
                                            @else
                                                <td class="{{ $day['category'] ?? '' }}">{{ $day['day'] }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="month-stats">
                            <div class="stat-item"><span class="stat-label">HK:</span> <span
                                    class="stat-value">{{ $monthData['hariKalender'] }}</span></div>
                            <div class="stat-item"><span class="stat-label">HL:</span> <span
                                    class="stat-value">{{ $monthData['hariLibur'] }}</span></div>
                            <div class="stat-item"><span class="stat-label">HE:</span> <span
                                    class="stat-value">{{ $monthData['hariEfektif'] }}</span></div>
                        </div>
                        @if(count($monthData['events']) > 0)
                            <div class="month-events">
                                @foreach(array_slice($monthData['events'], 0, 4) as $event)
                                    <div class="event-item">
                                        <span
                                            class="event-date">{{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d') }}{{ $event->tanggal_selesai && $event->tanggal_selesai != $event->tanggal_mulai ? '-' . \Carbon\Carbon::parse($event->tanggal_selesai)->format('d') : '' }}</span>
                                        {{ Str::limit($event->nama_kegiatan, 35) }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="page-break"></div>

        <!-- Semester Genap -->
        <div class="semester-title">
            <span class="semester-title-left">SEMESTER GENAP (Januari - Juni {{ $tahunGenap }})</span>
            <span class="semester-title-right">Jumlah Hari Efektif: {{ $hariEfektifGenap }} Hari</span>
        </div>

        @foreach(array_chunk($semesterGenapMonths, 2) as $rowIndex => $monthsRow)
            <div class="months-row">
                @foreach($monthsRow as $monthData)
                    <div class="month-container">
                        <div class="month-title">{{ $monthData['name'] }} {{ $monthData['year'] }}</div>
                        <table class="calendar-table">
                            <thead>
                                <tr>
                                    <th class="sunday">Ahad</th>
                                    <th>Senin</th>
                                    <th>Selasa</th>
                                    <th>Rabu</th>
                                    <th>Kamis</th>
                                    <th>Jum'a</th>
                                    <th>Sabtu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthData['weeks'] as $week)
                                    <tr>
                                        @foreach($week as $day)
                                            @if($day === null)
                                                <td class="empty"></td>
                                            @else
                                                <td class="{{ $day['category'] ?? '' }}">{{ $day['day'] }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="month-stats">
                            <div class="stat-item"><span class="stat-label">HK:</span> <span
                                    class="stat-value">{{ $monthData['hariKalender'] }}</span></div>
                            <div class="stat-item"><span class="stat-label">HL:</span> <span
                                    class="stat-value">{{ $monthData['hariLibur'] }}</span></div>
                            <div class="stat-item"><span class="stat-label">HE:</span> <span
                                    class="stat-value">{{ $monthData['hariEfektif'] }}</span></div>
                        </div>
                        @if(count($monthData['events']) > 0)
                            <div class="month-events">
                                @foreach(array_slice($monthData['events'], 0, 4) as $event)
                                    <div class="event-item">
                                        <span
                                            class="event-date">{{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d') }}{{ $event->tanggal_selesai && $event->tanggal_selesai != $event->tanggal_mulai ? '-' . \Carbon\Carbon::parse($event->tanggal_selesai)->format('d') : '' }}</span>
                                        {{ Str::limit($event->nama_kegiatan, 35) }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach

        <!-- Legend -->
        <div class="legend">
            <div class="legend-title">Keterangan:</div>
            <div class="legend-items">
                <div class="legend-item"><span class="legend-color libur"></span> Hari Libur</div>
                <div class="legend-item"><span class="legend-color ujian"></span> Ujian/Asesmen</div>
                <div class="legend-item"><span class="legend-color raport"></span> Pembagian Raport</div>
                <div class="legend-item"><span class="legend-color kegiatan"></span> Kegiatan Sekolah</div>
            </div>
            <div style="margin-top: 5px; font-size: 7px;">
                <strong>HK</strong> = Hari Kalender | <strong>HL</strong> = Hari Libur | <strong>HE</strong> = Hari
                Efektif
            </div>
        </div>

        <!-- Summary -->
        <div class="summary-box">
            <p>Jumlah Hari Kalender:
                {{ collect($semesterGanjilMonths)->sum('hariKalender') + collect($semesterGenapMonths)->sum('hariKalender') }}
                Hari
            </p>
            <p>Jumlah Hari Libur:
                {{ collect($semesterGanjilMonths)->sum('hariLibur') + collect($semesterGenapMonths)->sum('hariLibur') }}
                Hari
            </p>
            <p class="total">Jumlah Hari Efektif: {{ $hariEfektifGanjil + $hariEfektifGenap }} Hari</p>
        </div>

        <!-- Footer with QR Code -->
        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td class="footer-left">
                        <p>Dokumen ini dicetak pada {{ now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB
                        </p>
                        <p>{{ $siteProfile->nama_madrasah ?? 'Madrasah' }} - {{ $siteProfile->alamat ?? 'Alamat' }}</p>
                        <p style="margin-top: 5px; font-size: 6px; color: #999;">Scan QR code untuk verifikasi dokumen
                        </p>
                    </td>
                    <td class="footer-right">
                        @php
                            $verificationUrl = url('/profil/verifikasi');
                            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=' . urlencode($verificationUrl);
                        @endphp
                        <img src="{{ $qrUrl }}" class="qr-code" alt="QR Code Verifikasi">
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>