<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Jadwal Pelajaran - {{ $rombel->kelas?->nama ?? '' }} {{ $rombel->nama ?? '' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            /* Smaller font for layout */
            line-height: 1.3;
            color: #1f2937;
        }

        .container {
            padding: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #10b981;
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .header p {
            font-size: 9px;
            color: #4b5563;
        }

        .page-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            color: #10b981;
            margin: 10px 0;
            text-transform: uppercase;
        }

        .info-box {
            background: #f0fdf4;
            border: 1px solid #10b981;
            padding: 5px 10px;
            /* Reduced padding */
            margin-bottom: 15px;
        }

        .info-box table {
            width: 100%;
        }

        .info-box td {
            padding: 1px 0;
            /* Reduced padding */
            font-size: 9px;
            vertical-align: top;
        }

        .info-box td.label {
            width: 80px;
            font-weight: bold;
        }

        .info-box td.value {
            font-weight: normal;
        }

        /* Grid Layout */
        .grid-container {
            display: table;
            /* Simulate Flexbox using table for PDF/Dompdf */
            width: 100%;
            border-spacing: 10px;
            table-layout: fixed;
        }

        .grid-row {
            display: table-row;
        }

        .day-column {
            display: table-cell;
            width: 33.33%;
            /* 3 columns */
            vertical-align: top;
            padding-bottom: 10px;
        }

        .day-header {
            background: #10b981;
            color: white;
            padding: 4px;
            /* Reduced padding */
            font-weight: bold;
            font-size: 10px;
            text-align: center;
            border: 1px solid #059669;
            text-transform: uppercase;
        }

        table.jadwal {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            /* Compact */
        }

        table.jadwal th,
        table.jadwal td {
            border: 1px solid #6b7280;
            /* Darker border for visibility */
            padding: 3px;
            /* Reduced padding */
        }

        table.jadwal thead th {
            background: #e5e7eb;
            font-weight: bold;
            text-align: center;
        }

        table.jadwal tbody tr:nth-child(even) {
            background: #f3f4f6;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 5px;
            text-align: right;
            font-size: 8px;
            color: #9ca3af;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- Madrasah Header --}}
        <div class="header">
            <h1>{{ $profile->nama_madrasah ?? 'MADRASAH DINIYAH' }}</h1>
            <p>{{ $profile->alamat ?? '' }}
                @if($profile->kelurahan), {{ $profile->kelurahan }} @endif
                @if($profile->kecamatan), {{ $profile->kecamatan }} @endif
                @if($profile->kota), {{ $profile->kota }} @endif
                @if($profile->provinsi), {{ $profile->provinsi }} @endif
            </p>
        </div>

        <div class="page-title">JADWAL PELAJARAN</div>

        <div class="info-box">
            <table style="width: 100%;">
                <tr>
                    {{-- Left Info --}}
                    <td style="width: 50%; vertical-align: top;">
                        <table style="width: 100%;">
                            <tr>
                                <td class="label">Kelas/Rombel</td>
                                <td class="value">: Kelas {{ $rombel->kelas?->nama ?? '' }} - {{ $rombel->nama ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Wali Kelas</td>
                                <td class="value">: {{ $rombel->waliKelas?->nama_lengkap ?? '-' }}</td>
                            </tr>
                        </table>
                    </td>
                    {{-- Right Info --}}
                    <td style="width: 50%; vertical-align: top;">
                        <table style="width: 100%;">
                            <tr>
                                <td class="label">Tahun Ajaran</td>
                                <td class="value">: {{ $tahunAjaran->nama ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Semester</td>
                                <td class="value">: {{ ucfirst($semester) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        @php
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $chunks = array_chunk($days, 3);
        @endphp

        <div class="grid-container">
            @foreach($chunks as $chunk)
                <div class="grid-row">
                    @foreach($chunk as $day)
                        <div class="day-column">
                            <div class="day-header">{{ $day }}</div>
                            <table class="jadwal">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">KE</th>
                                        <th style="width: 60px;">WAKTU</th>
                                        <th>MATA PELAJARAN</th>
                                        <th style="width: 35%;">GURU</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i = 1; $i <= 8; $i++)
                                        @php
                                            $jadwal = $jadwals->where('hari', $day)->where('jam_ke', $i)->first();
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $i }}</td>
                                            <td class="text-center">
                                                @if($jadwal)
                                                    {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                                                @endif
                                            </td>
                                            <td>{{ $jadwal->mataPelajaran?->nama ?? '' }}</td>
                                            <td>{{ $jadwal->teacher?->nama_lengkap ?? '' }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div class="footer">
            Dicetak pada: {{ now()->format('d F Y H:i') }} WIB
        </div>
    </div>
</body>

</html>