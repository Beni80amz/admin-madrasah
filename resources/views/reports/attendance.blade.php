<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0;
        }

        .meta {
            margin-bottom: 15px;
        }

        .meta table {
            width: 100%;
            border: none;
        }

        .meta td {
            vertical-align: top;
            padding: 2px;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .table-data th,
        .table-data td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .table-data th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .summary {
            width: 300px;
            margin-bottom: 20px;
            border: 1px solid #000;
            padding: 10px;
        }

        .footer {
            width: 100%;
            margin-top: 30px;
        }

        .signature {
            float: right;
            width: 200px;
            text-align: center;
        }

        .qr-code {
            float: left;
            width: 100px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Absensi Madrasah</h1>
        <p>Jl. Contoh No. 123, Kota Contoh</p>
        <p>Telp: (021) 12345678 | Email: admin@madrasah.sch.id</p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td width="15%"><strong>Periode</strong></td>
                <td width="2%">:</td>
                <td>{{ $period }}</td>
            </tr>
            <tr>
                <td><strong>Nama</strong></td>
                <td>:</td>
                <td>{{ $user ? $user->name : 'Semua Pegawai' }}</td>
            </tr>
            @if($user && $user->nipy)
                <tr>
                    <td><strong>NIPY</strong></td>
                    <td>:</td>
                    <td>{{ $user->nipy }}</td>
                </tr>
            @endif
        </table>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="10%">Hari</th>
                @if(!$user)
                <th width="20%">Nama</th> @endif
                <th width="10%">Masuk</th>
                <th width="10%">Pulang</th>
                <th width="10%">Status</th>
                <th width="10%">Telat</th>
                <th width="10%">Lembur</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $index => $row)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->date)->locale('id')->isoFormat('D MMMM Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->date)->locale('id')->isoFormat('dddd') }}</td>
                    @if(!$user)
                    <td>{{ $row->user->name ?? '-' }}</td> @endif
                    <td style="text-align: center;">{{ $row->time_in ?? '-' }}</td>
                    <td style="text-align: center;">{{ $row->time_out ?? '-' }}</td>
                    <td style="text-align: center; text-transform: capitalize;">{{ $row->status }}</td>
                    <td style="text-align: center;">{{ $row->keterlambatan }}m</td>
                    <td style="text-align: center;">{{ $row->lembur }}m</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <strong>Rekapitulasi:</strong><br>
        Hadir: {{ $summary['hadir'] }}<br>
        Telat: {{ $summary['telat'] }}<br>
        Izin: {{ $summary['izin'] }}<br>
        Sakit: {{ $summary['sakit'] }}<br>
        Alpha: {{ $summary['alpha'] }}
    </div>

    <div class="footer">
        <div class="qr-code">
            <img
                src="data:image/svg+xml;base64, {{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->generate($qrData)) }} ">
        </div>
        <div class="signature">
            <p>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
            <p>Mengetahui,<br>Kepala Madrasah</p>
            <br><br><br>
            <p><strong>(______________________)</strong></p>
        </div>
    </div>
</body>

</html>