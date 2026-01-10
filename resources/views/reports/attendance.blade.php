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
            position: relative;
        }

        .logo {
            position: absolute;
            top: 0;
            left: 0;
            width: 60px;
            height: auto;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0;
            font-size: 11px;
        }

        .header-title {
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }

        .meta {
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
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
            width: 100%;
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        .footer {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .signature-box {
            float: right;
            width: 250px;
            text-align: center;
        }

        .qr-code-container {
            margin: 10px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>
    <div class="header">
        {{-- Optional Logo --}}
        @if(isset($profile) && $profile->logo)
            {{-- Assuming logo is stored in public disk. For PDF, we might need absolute path --}}
            {{-- <img src="{{ public_path('storage/' . $profile->logo) }}" class="logo"> --}}
        @endif

        <div class="header-title">LAPORAN ABSENSI</div>
        <h1>{{ $profile->nama_madrasah ?? 'MADRASAH' }}</h1>
        <p>{{ $profile->alamat ?? 'Alamat Madrasah' }}</p>
        <p>
            Telp: {{ $profile->no_hp ?? '-' }}
            @if($profile->email) | Email: {{ $profile->email }} @endif
        </p>
    </div>

    <div class="meta">
        <table style="width: 100%;">
            <tr>
                <td width="50%"><strong>Periode:</strong> {{ $period }}</td>
                <td width="50%" style="text-align: right;"><strong>Nama:</strong> {{ $teacherName }}</td>
            </tr>
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
                        {{-- Attempt to find teacher name if lazy loaded, otherwise fallback to user name --}}
                        @php
                            $tName = $row->user->name ?? '-';
                        @endphp
                        <td>{{ $tName }}</td>
                    @endif
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
        <strong>Rekapitulasi:</strong>
        <div style="margin-top: 5px;">
            <span style="margin-right: 20px;">Hadir: {{ $summary['hadir'] }}</span>
            <span style="margin-right: 20px;">Telat: {{ $summary['telat'] }}</span>
            <span style="margin-right: 20px;">Izin: {{ $summary['izin'] }}</span>
            <span style="margin-right: 20px;">Sakit: {{ $summary['sakit'] }}</span>
            <span>Alpha: {{ $summary['alpha'] }}</span>
        </div>
    </div>

    <div class="footer">
        <div style="float: left; width: 50%; font-size: 9px; font-style: italic;">
            Dokumen ini dicetak pada {{ now()->locale('id')->isoFormat('D MMMM Y HH:mm') }} WIB
        </div>
        <div class="signature-box">
            <p>
                Depok, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }} <br>
                Mengetahui,<br>
                Kepala Madrasah
            </p>

            {{-- QR Code Positioned Between Title and Name --}}
            <div class="qr-code-container">
                <img
                    src="data:image/svg+xml;base64, {{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(70)->generate($qrData)) }}">
            </div>

            <p><strong>{{ $profile->nama_kepala_madrasah ?? '______________________' }}</strong></p>
            @if(isset($profile->nip_kepala_madrasah))
                <p>NIP. {{ $profile->nip_kepala_madrasah }}</p>
            @endif
        </div>
        <div class="clear"></div>
    </div>
</body>

</html>