<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Guru</title>
    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
        }

        @page {
            size: A4 portrait;
            margin: 20mm 20mm 20mm 20mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            /* margin: 20px; Removed body margin to let @page handle it */
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #10B981;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #10B981;
            font-size: 18px;
            margin: 0 0 5px 0;
        }

        .header p {
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
        }

        td {
            padding: 6px 5px;
            border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
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
            text-align: right;
            font-size: 9px;
            color: #999;
        }

        .signature {
            margin-top: 40px;
            float: right;
            text-align: center;
            width: 200px;
        }

        .signature p {
            margin: 5px 0;
        }

        .signature-image {
            margin: 15px 0 15px 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>DATA GURU / TENAGA PENDIDIK</h1>
        <h2 style="margin: 5px 0; font-size: 16px; color: #333;">{{ $profile->nama_madrasah ?? 'Madrasah Prototype' }}
        </h2>
        <p style="margin: 0; font-size: 10px; color: #666;">
            Tahun Ajaran:
            <strong>{{ $tahunAjaran->nama ?? 'Belum ditentukan' }}</strong>
        </p>
        <p style="margin-top: 5px; font-size: 9px; color: #999;">Dicetak pada: {{ now()->format('d F Y H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>Nama Lengkap</th>
                <th>NUPTK</th>
                <th>NPK</th>
                <th style="width: 15%;">Jabatan</th>
                <th style="width: 15%;">Tugas Pokok</th>
                <th style="width: 50px;">Status</th>
                <th style="width: 60px;">Sertifikasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $index => $teacher)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $teacher->nama_lengkap }}</strong></td>
                    <td>{{ is_numeric($teacher->nuptk) ? number_format($teacher->nuptk, 0, '', '') : ($teacher->nuptk ?? '-') }}
                    </td>
                    <td>{{ $teacher->npk_peg_id ?? '-' }}</td>
                    <td>{{ $teacher->jabatan?->nama ?? '-' }}</td>
                    <td>{{ $teacher->tugasPokok?->nama ?? '-' }}</td>
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
                </tr>
            @endforeach
        </tbody>

    </table>

    <div class="signature">
        <p>Mengetahui,</p>
        <p>Kepala {{ $profile->nama_madrasah ?? 'Madrasah' }}</p>

        <div class="signature-image">
            @php
                $qrText = ($profile->nama_kepala_madrasah ?? 'Kepala Madrasah') . ' - ' . ($profile->nama_madrasah ?? 'Madrasah');
                $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' . urlencode($qrText);
            @endphp
            <img src="{{ $qrUrl }}" alt="QR Code" style="height: 70px; width: 70px;">
        </div>

        <p><strong>{{ $profile->nama_kepala_madrasah ?? '_______________________' }}</strong></p>
    </div>

    <div class="footer">
        Total: {{ $teachers->count() }} guru | Generated by Admin Madrasah
    </div>
</body>

</html>