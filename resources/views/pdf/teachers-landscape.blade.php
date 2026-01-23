<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Data Guru {{ optional($profile)->nama_madrasah ?? 'Madrasah' }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1cm 2cm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            padding: 20px 40px;
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

        .badge {
            display: inline-block;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #D1FAE5;
            color: #065F46;
        }

        .badge-warning {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .badge-info {
            background-color: #DBEAFE;
            color: #1E40AF;
        }

        .badge-danger {
            background-color: #FEE2E2;
            color: #991B1B;
        }

        .footer {
            margin-top: 20px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            width: 100%;
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

        .signature {
            text-align: center;
            float: right;
            width: 200px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>DATA GURU DAN TENAGA PENDIDIK</h1>
        <h2>{{ $profile->nama_madrasah ?? 'MADRASAH' }}</h2>
        <p>Tahun Ajaran: {{ $tahunAjaran->nama ?? 'Belum ditentukan' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;" class="center">No</th>
                <th style="width: 15%;">Nama Lengkap</th>
                <th style="width: 12%;">NUPTK</th>
                <th style="width: 12%;">NPK/Peg.ID</th>
                <th>Jabatan</th>
                <th>Tugas Pokok</th>
                <th>Kelas/Rombel</th>
                <th>Tugas Tambahan</th>
                <th style="width: 50px;">Status</th>
                <th style="width: 50px;">Sertifikasi</th>
                <th style="width: 40px;" class="center">Aktif</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $index => $teacher)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td><strong>{{ $teacher->nama_lengkap }}</strong></td>
                    <td>{{ $teacher->nuptk ?? '-' }}</td>
                    <td>{{ $teacher->npk_peg_id ?? '-' }}</td>
                    <td>{{ $teacher->jabatan?->nama ?? '-' }}</td>
                    <td>{{ $teacher->tugasPokok?->nama ?? '-' }}</td>
                    <td>{{ $teacher->kelas_rombel ?? '-' }}</td>
                    <td>{{ $teacher->tugasTambahan?->nama ?? '-' }}</td>
                    <td>
                        <span
                            class="badge {{ $teacher->status === 'PNS' ? 'badge-success' : ($teacher->status === 'P3K' ? 'badge-info' : 'badge-warning') }}">
                            {{ $teacher->status }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $teacher->sertifikasi === 'Sudah' ? 'badge-success' : 'badge-danger' }}">
                            {{ $teacher->sertifikasi }}
                        </span>
                    </td>
                    <td class="center">
                        <span class="badge {{ $teacher->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $teacher->is_active ? 'Ya' : 'Tidak' }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td style="text-align: left; font-size: 8px; color: #666;">
                    <p>Dicetak pada: {{ now()->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }} WIB</p>
                    <p>Total Data: {{ $teachers->count() }} Guru/Staff</p>
                </td>
                <td style="text-align: right;">
                    <img src="{{ $qrCodeImage }}" alt="QR Code" style="height: 50px; width: 50px;">
                </td>
            </tr>
        </table>
    </div>
</body>

</html>