<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Absensi GTK</title>
    <style>
        @page {
            margin: 5mm;
            /* Aggressive margin reduction */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 7pt;
            /* Smaller base font */
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
            /* Minimal padding */
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
        }

        .col-name {
            width: 130px;
            /* Reduced width, allow wrapping */
        }

        .col-date {
            width: 25px;
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

        /* Recap Bar */
        .recap-bar {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 8pt;
            border: 1px solid #000;
        }

        .recap-label {
            padding: 5px 10px;
            background-color: white;
            border-right: 1px solid #000;
        }

        .recap-item {
            padding: 5px 15px;
            border-right: 1px solid #000;
            text-align: center;
        }

        /* Recap Colors */
        .bg-hadir {
            background-color: #00FA9A;
        }

        /* Medium Spring Green */
        .bg-sakit {
            background-color: #FFD700;
        }

        /* Gold */
        .bg-izin {
            background-color: #87CEFA;
        }

        /* Light Sky Blue */
        .bg-alpha {
            background-color: #FF0000;
            color: white;
        }

        /* Red */
        /* Table Cell Colors */
        .status-hadir {
            font-size: 6pt;
        }

        /* Just text, plain or maybe checkmark? Image shows times */
        .status-sakit {
            background-color: #FFD700;
            font-weight: bold;
            font-size: 7pt;
        }

        .status-izin {
            background-color: #87CEFA;
            font-weight: bold;
            font-size: 7pt;
        }

        /* Use lighter yellow for display if needed, but matched recap above */
        .status-alpha {
            background-color: #FF0000;
            color: white;
            font-weight: bold;
            font-size: 7pt;
        }

        .status-libur {
            color: red;
            font-size: 7pt;
        }

        .status-empty {
            background-color: #f0f0f0;
        }

        /* Optional for styling empty slots */
        .footer-section {
            margin-top: 20px;
            width: 100%;
        }

        .footer-table td {
            border: none;
            text-align: center;
            vertical-align: top;
        }
    </style>
</head>

<body>

    <div style="text-align: center; margin-bottom: 10px;">
        <div style="font-size: 14pt; font-weight: bold; color: #008080;">REKAPITULASI KEHADIRAN GURU DAN TENAGA
            KEPENDIDIKAN</div>
        <div style="font-size: 12pt; font-weight: bold; color: #008080;">
            {{ $profile->nama_madrasah ?? 'MADRASAH' }}
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
            <!-- Row 1: Titles -->
            <tr style="background-color: #008080; color: white;">
                <th rowspan="2" class="col-no" style="background-color: #008080; color: white;">NO</th>
                <th rowspan="2" class="col-name" style="background-color: #008080; color: white;">NAMA GTK
                </th>
                @foreach ($validDates as $d)
                                <?php
                    $dateObj = \Carbon\Carbon::createFromDate($year, $month, $d);
                    $dateNum = $dateObj->format('d/m'); // shorten date format
                                                    ?>
                                <th class="col-date" style="background-color: #008080; color: white; font-size: 7pt;">
                                    {{ $dateNum }}
                                </th>
                @endforeach
            </tr>
            <!-- Row 2: Day Names -->
            <tr style="background-color: #008080; color: white;">
                @foreach ($validDates as $d)
                                <?php
                    $dateObj = \Carbon\Carbon::createFromDate($year, $month, $d);
                    // Use shortest possible day name
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
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align: left; padding-left: 5px;">{{ $row['name'] }}</td>

                    @foreach ($validDates as $d)
                        @php
                            // Check if index exists for this date
                            $attendance = $row['dates'][$d] ?? null;
                        @endphp

                        @if ($attendance)
                            @if ($attendance->status == 'Hadir')
                                <td style="font-size: 6pt;">
                                    {{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('H:i') : '' }}
                                    -<br>
                                    {{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('H:i') : '' }}
                                </td>
                            @elseif ($attendance->status == 'Sakit')
                                <td class="status-sakit">Sakit</td>
                            @elseif ($attendance->status == 'Izin')
                                <td class="status-izin">Izin</td>
                            @elseif ($attendance->status == 'Alpha')
                                <td class="status-alpha">Alpha</td>
                            @elseif ($attendance->status == 'telat')
                                <td style="font-size: 6pt;">
                                    {{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('H:i') : '' }}
                                    -<br>
                                    {{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('H:i') : '' }}
                                </td>
                            @else
                                <td>-</td>
                            @endif
                        @else
                            <td>-</td>
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
                        $qrContent =
                            "Verifikasi Dokumen:\n" .
                            "Dokumen: Rekap Absensi GTK\n" .
                            "Periode: $monthName\n" .
                            "Oleh: " .
                            ($profile->nama_kepala_madrasah ?? '-');
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