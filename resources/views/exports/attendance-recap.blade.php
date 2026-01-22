<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Absensi GTK</title>
    <style>
        @page {
            margin: 3mm;
            /* Reduced margin */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 6pt;
            /* Reduced font size */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 1px;
            text-align: center;
            vertical-align: middle;
        }

        /* Teal/Green styling for Header */
        th.kop-style {
            background-color: #008080;
            color: white;
            font-weight: bold;
        }

        /* Specific widths */
        .col-no {
            width: 15px;
            /* Aggressively reduced */
        }

        .col-name {
            width: 110px;
            /* Fixed width, reduced per user request */
            white-space: normal;
            /* Allow wrapping */
        }

        .col-date {
            width: 21px;
            /* Aggressively reduced */
        }

        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 5px;
            text-transform: uppercase;
            color: #008080;
        }

        .header-subtitle {
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 10px;
            background-color: #008080;
            color: white;
            padding: 5px;
        }

        /* Recap Colors */
        .bg-hadir {
            background-color: #00FA9A;
        }

        .bg-sakit {
            background-color: #FFD700;
        }

        .bg-izin {
            background-color: #87CEFA;
        }

        .bg-alpha {
            background-color: #FF0000;
            color: white;
        }

        /* Table Cell Colors */
        .status-hadir {
            font-size: 5pt;
            /* Smaller for content */
        }

        .status-sakit {
            background-color: #FFD700;
            font-weight: bold;
            font-size: 6pt;
        }

        .status-izin {
            background-color: #87CEFA;
            font-weight: bold;
            font-size: 6pt;
        }

        .status-alpha {
            background-color: #FF0000;
            color: white;
            font-weight: bold;
            font-size: 6pt;
        }

        .status-libur {
            color: red;
            font-size: 6pt;
        }

        .footer-section {
            margin-top: 20px;
            width: 100%;
        }

        .footer-table td {
            border: none;
            text-align: center;
            vertical-align: top;
        }

        /* Helper for 2-row data */
        .row-top {
            border-bottom: 1px dotted #ccc;
        }

        .row-bottom {}
    </style>
</head>

<body>

    <div style="text-align: center; margin-bottom: 10px;">
        <div style="font-size: 14pt; font-weight: bold; color: #008080;">REKAPITULASI KEHADIRAN GURU DAN TENAGA
            KEPENDIDIKAN</div>
        <div style="font-size: 12pt; font-weight: bold; color: #008080;">{{ $profile->nama_madrasah ?? 'MADRASAH' }}
        </div>
        <div
            style="font-size: 10pt; font-weight: bold; color: #008080; background-color: #d1f2eb; display: inline-block; width: 100%; padding: 3px 0; margin-top: 5px;">
            PERIODE BULAN {{ strtoupper($monthName) }}
        </div>
    </div>

    <!-- Percentage Recap Bar -->
    <table style="width: auto; margin-bottom: 5px; border: 1px solid black; font-size: 8pt;">
        <tr>
            <td style="width: 100px; font-weight: bold; text-align: left; padding-left: 10px;">Rekapitulasi :</td>
            <td class="bg-hadir" style="width: 100px;">Hadir {{ $percentages['Hadir'] }}%</td>
            <td class="bg-sakit" style="width: 100px;">Sakit {{ $percentages['Sakit'] }}%</td>
            <td class="bg-izin" style="width: 100px;">Izin {{ $percentages['Izin'] }}%</td>
            <td class="bg-alpha" style="width: 100px;">Alpha {{ $percentages['Alpha'] }}%</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr style="background-color: #008080; color: white;">
                <th rowspan="2" class="col-no" style="background-color: #008080; color: white;">NO</th>
                <th rowspan="2" class="col-name" style="background-color: #008080; color: white;">NAMA GTK</th>
                @foreach ($validDates as $d)
                                <?php
                    $dateObj = \Carbon\Carbon::createFromDate($year, $month, $d);
                    $dateNum = $dateObj->format('d/m');
                                                                    ?>
                                <th class="col-date" style="background-color: #008080; color: white; font-size: 7pt;">
                                    {{ $dateNum }}
                                </th>
                @endforeach
            </tr>
            <tr style="background-color: #008080; color: white;">
                @foreach ($validDates as $d)
                                <?php
                    $dateObj = \Carbon\Carbon::createFromDate($year, $month, $d);
                    $dayName = $dateObj->locale('id')->isoFormat('ddd');
                                                                    ?>
                                <th class="col-date" style="background-color: #008080; color: white; font-size: 7pt;">
                                    {{ $dayName }}
                                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $row)
                <!-- Row 1: Time In -->
                <tr>
                    <td rowspan="2">{{ $index + 1 }}</td>
                    <td rowspan="2" style="text-align: left; padding-left: 5px;">{{ strtoupper($row['name']) }}</td>

                    @foreach ($validDates as $d)
                        @php
                            $attendance = $row['dates'][$d] ?? null;
                            $status = $attendance ? strtolower($attendance->status) : null;
                        @endphp

                        @if ($attendance)
                            @if ($status == 'hadir' || $status == 'telat')
                                <td style="font-size: 6pt; border-bottom: none;">
                                    {{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('H:i') : '-' }}
                                </td>
                            @elseif ($status == 'sakit')
                                <td rowspan="2" class="status-sakit">Sakit</td>
                            @elseif ($status == 'izin')
                                <td rowspan="2" class="status-izin">Izin</td>
                            @elseif ($status == 'alpha')
                                <td rowspan="2" class="status-alpha">Alpha</td>
                            @else
                                <td rowspan="2">-</td>
                            @endif
                        @else
                            <td rowspan="2">-</td>
                        @endif
                    @endforeach
                </tr>
                <!-- Row 2: Time Out -->
                <tr>
                    @foreach ($validDates as $d)
                        @php
                            $attendance = $row['dates'][$d] ?? null;
                            $status = $attendance ? strtolower($attendance->status) : null;
                        @endphp

                        @if ($attendance && ($status == 'hadir' || $status == 'telat'))
                            <td style="font-size: 6pt; border-top: none;">
                                {{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('H:i') : '-' }}
                            </td>
                        @else
                            <!-- Handled by rowspan above for other statuses -->
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-section">
        <table class="footer-table" style="border: none;">
            <tr>
                <td style="width: 70%; border: none;"></td>
                <td style="width: 30%; border: none;">
                    Depok, {{ $daysInMonth }} {{ $monthName }}<br>
                    Mengetahui,<br>
                    Kepala Madrasah
                    <br><br>
                    @if ($profile && $profile->tanda_tangan_kepala_madrasah)
                        <!-- Placeholder -->
                    @endif

                    @php
                        $qrContent = "Verifikasi Dokumen:\n" .
                            "Dokumen: Rekap Absensi GTK\n" .
                            "Periode: $monthName\n" .
                            "Oleh: " . ($profile->nama_kepala_madrasah ?? '-');
                    @endphp
                    <div>
                        <img src="data:image/svg+xml;base64, {{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->generate($qrContent)) }} "
                            alt="QR Code">
                    </div>

                    <br>
                    <u><b>{{ $profile->nama_kepala_madrasah ?? '.........................' }}</b></u><br>
                    NIP. {{ $profile->nip_kepala_madrasah ?? '.........................' }}
                </td>
            </tr>
        </table>
    </div>

</body>

</html>