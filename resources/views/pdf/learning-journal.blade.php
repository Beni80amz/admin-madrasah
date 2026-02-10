<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Jurnal Pembelajaran {{ $profile->nama_madrasah ?? 'Madrasah' }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1cm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            padding: 10px;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #333;
            line-height: 1.3;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #10B981;
            padding-bottom: 10px;
            position: relative;
        }

        .header h1 {
            color: #10B981;
            font-size: 16px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 14px;
            color: #333;
            margin: 0 0 5px 0;
        }

        .header p {
            font-size: 10px;
            color: #666;
            margin: 0;
        }

        .logo {
            position: absolute;
            left: 0;
            top: 0;
            height: 60px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #10B981;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
            border: 1px solid #059669;
        }

        td {
            padding: 6px 5px;
            border: 1px solid #e5e7eb;
            font-size: 8px;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            width: 100%;
        }

        .footer-table {
            width: 100%;
            border: none;
        }

        .footer-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .signature-box {
            text-align: center;
            width: 250px;
        }

        .signature-space {
            height: 60px;
        }

        .qr-section {
            text-align: left;
        }

        .qr-code {
            width: 60px;
            height: 60px;
        }
    </style>
</head>

<body>
    <div class="header">
        @if($profile->logo)
            <img src="{{ public_path('storage/' . $profile->logo) }}" class="logo" alt="Logo">
        @endif
        <h1>JURNAL PEMBELAJARAN</h1>
        <h2>{{ $profile->nama_madrasah ?? 'MADRASAH' }}</h2>
        <p>{{ $profile->alamat ?? '' }} {{ $profile->kelurahan ? 'Kel. ' . $profile->kelurahan : '' }}
            {{ $profile->kecamatan ? 'Kec. ' . $profile->kecamatan : '' }} {{ $profile->kota ?? '' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;" class="center">No</th>
                <th style="width: 70px;">Tanggal</th>
                <th style="width: 100px;">Guru</th>
                <th style="width: 80px;">Mapel</th>
                <th style="width: 60px;">Kelas</th>
                <th style="width: 40px;" class="center">Pert.</th>
                <th style="width: 120px;">Materi</th>
                <th>Absensi (S/I/A)</th>
                <th style="width: 100px;">Evaluasi (Hambatan)</th>
                <th style="width: 100px;">Tindak Lanjut (Solusi)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($journals as $index => $journal)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($journal->date)->locale('id')->translatedFormat('d/m/Y') }}</td>
                    <td>{{ $journal->user?->teacher?->nama_lengkap ?? $journal->user?->name }}</td>
                    <td>{{ $journal->mataPelajaran?->nama }}</td>
                    <td>{{ $journal->rombel?->kelas?->nama }} - {{ $journal->rombel?->nama }}</td>
                    <td class="center">{{ $journal->pertemuan_ke }}</td>
                    <td>{{ $journal->materi }}</td>
                    <td>{{ $journal->getFormattedAttendanceNames() }}</td>
                    <td>{{ $journal->hambatan }}</td>
                    <td>{{ $journal->solusi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="qr-section">
                    <p style="font-size: 7px; color: #666; margin-bottom: 5px;">Scan untuk verifikasi:</p>
                    <img src="{{ $qrCodeImage }}" class="qr-code" alt="QR Code">
                </td>
                <td style="width: 50%;"></td>
                <td class="signature-box">
                    <p>{{ $profile->kota ?? 'Kota' }}, {{ now()->locale('id')->translatedFormat('d F Y') }}</p>
                    <p>Guru Pengampu,</p>
                    <div class="signature-space"></div>
                    <p><strong>{{ Auth::user()->teacher?->nama_lengkap ?? Auth::user()->name }}</strong></p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>