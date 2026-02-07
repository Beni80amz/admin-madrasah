<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h3,
        .header h4 {
            margin: 0;
        }

        .header p {
            margin: 2px 0;
        }

        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .meta table {
            width: 100%;
            border: none;
        }

        .meta td {
            border: none;
            padding: 2px;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-data th,
        .table-data td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        .table-data th {
            background-color: #f0f0f0;
        }

        .summary-box {
            border: 1px solid black;
            padding: 10px;
            margin-bottom: 30px;
        }

        .summary-box h4 {
            margin-top: 0;
            margin-bottom: 5px;
        }

        .footer {
            width: 100%;
            margin-top: 30px;
        }

        .footer table {
            width: 100%;
            border: none;
        }

        .footer td {
            border: none;
            text-align: center;
            vertical-align: top;
        }

        .signature-section {
            float: right;
            width: 30%;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>LAPORAN ABSENSI</h3>
        <h3>{{ strtoupper($profile->nama_madrasah ?? 'MIS. AL-ISLAMIYAH AMZ') }}</h3>
        <p>{{ $profile->alamat ?? 'Jl. Jasa Warga No.3, Bakti Jaya, Kec. Sukmajaya' }},
            {{ $profile->kota ?? 'Kota Depok, Jawa Barat 16418' }}</p>
        <p>Telp: {{ $profile->no_hp ?? '+6282110863967' }} | Email: {{ $profile->email ?? 'miamzdepok@gmail.com' }}</p>
    </div>

    @php
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $monthName = $months[$month];
    @endphp

    <div class="meta">
        <table>
            <tr>
                <td style="text-align: left; font-weight: bold;">Periode: {{ $monthName }} {{ $year }}</td>
                <td style="text-align: right; font-weight: bold;">Nama:
                    {{ strtoupper($user->name) }}{{ $teacher && $teacher->gelar ? ', ' . $teacher->gelar : '' }}</td>
            </tr>
        </table>
        <hr style="border-top: 1px solid black; margin-top: 5px; margin-bottom: 15px;">
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Hari</th>
                <th>Masuk</th>
                <th>Pulang</th>
                <th>Status</th>
                <th>Telat</th>
                <th>Lembur</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->date)->translatedFormat('d F Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->date)->translatedFormat('l') }}</td>
                    <td>{{ $record->time_in ?? '-' }}</td>
                    <td>{{ $record->time_out ?? '-' }}</td>
                    <td>{{ $record->status }}</td>
                    <td>{{ $record->keterlambatan > 0 ? $record->keterlambatan . 'm' : '0m' }}</td>
                    <td>{{ $record->lembur > 0 ? $record->lembur . 'm' : '0m' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Tidak ada data absensi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-box">
        <h4>Rekapitulasi:</h4>
        <span>Hadir: {{ $summary['Hadir'] }}</span> &nbsp;&nbsp;
        <span>Telat: {{ $summary['Telat'] }}</span> &nbsp;&nbsp;
        <span>Izin: {{ $summary['Izin'] }}</span> &nbsp;&nbsp;
        <span>Sakit: {{ $summary['Sakit'] }}</span> &nbsp;&nbsp;
        <span>Alpha: {{ $summary['Alpha'] }}</span>
    </div>

    <div style="font-style: italic; font-size: 10px; margin-bottom: 20px;">
        Dokumen ini dicetak pada {{ now()->translatedFormat('d F Y H:i') }} WIB
    </div>

    <div class="footer">
        <table>
            <tr>
                <td style="width: 60%;"></td>
                <td style="width: 40%;">
                    <div>{{ $profile->kota ?? 'Depok' }}, {{ now()->translatedFormat('d F Y') }}</div>
                    <div>Mengetahui,</div>
                    <div>Kepala Madrasah</div>
                    <div style="margin-top: 10px; margin-bottom: 10px;">
                        @php
                            // URL verifikasi simulasi
                            $verificationUrl = url('/verify/attendance?user=' . $user->id . '&month=' . $month . '&year=' . $year);
                        @endphp
                        <img
                            src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate($verificationUrl)) !!} ">
                    </div>
                    <div style="font-weight: bold;">{{ $profile->nama_kepala_madrasah ?? 'Jamal, S.Pd.I' }}</div>
                    <div>NIP. {{ $profile->nip_kepala_madrasah ?? '3664520021047' }}</div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>