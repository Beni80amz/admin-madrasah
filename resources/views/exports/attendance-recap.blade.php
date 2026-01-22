<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Absensi GTK</title>
    <style>
        @page {
            margin: 5mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 8pt;
        }

        /* Generic Table - reset borders for layout tables */
        table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        /* DATA Table - Strict borders */
        table.data-table {
            width: 100%;
            border: 1px solid #000;
            table-layout: fixed;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #000 !important;
            padding: 2px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
            overflow: hidden;
        }

        /* Teal/Green styling for Header */
        th.kop-style {
            background-color: #008080;
            color: white;
            font-weight: bold;
        }

        /* Column Widths - Percentage Based */
        .col-no {
            width: 3%;
        }

        .col-name {
            width: 19%;
            /* Slightly reduced to ensure total < 100% safety */
            text-align: left !important;
            padding-left: 4px;
        }

        .col-date {
            width: 2.4%;
            /* 2.4 * 31 = 74.4%. Total: 3+19+74.4 = 96.4%. Safety buffer for borders */
            font-size: 7pt;
        }

        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
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
            font-size: 7pt;
        }

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

        .footer-section {
            margin-top: 20px;
            width: 100%;
        }

        .footer-table {
            border: none;
        }

        .footer-table td {
            border: none !important;
            /* Force remove borders on footer cells */
            text-align: center;
            vertical-align: top;
            padding: 0;
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
            <td style="width: 100px; background-color: #e0f7fa;">Hari Kerja: {{ $workingDaysCount }}</td>
            <td style="width: 100px; background-color: #ffcccc;">Hari Libur: {{ $holidayDaysCount }}</td>
            <td class="bg-hadir" style="width: 100px;">Hadir {{ $percentages['Hadir'] }}%</td>
            <td class="bg-sakit" style="width: 100px;">Sakit {{ $percentages['Sakit'] }}%</td>
            <td class="bg-izin" style="width: 100px;">Izin {{ $percentages['Izin'] }}%</td>
            <td class="bg-alpha" style="width: 100px;">Alpha {{ $percentages['Alpha'] }}%</td>
        </tr>
    </table>

    <table class="data-table">
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
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align: left; padding-left: 5px;">{{ strtoupper($row['name']) }}</td>

                    @foreach ($validDates as $dIdx => $d)
                        @php
                            $attendance = $row['dates'][$d] ?? null;
                            $status = $attendance ? strtolower($attendance->status) : null;
                            $isLastCol = ($dIdx === count($validDates) - 1);
                            $rightBorderStyle = $isLastCol ? 'border-right: 1px solid black !important;' : '';
                        @endphp

                        @if ($attendance)
                            @if ($status == 'hadir' || $status == 'telat')
                                <td style="font-size: 6pt; {{ $rightBorderStyle }}">
                                    {{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('H:i') : '-' }}<br>
                                    {{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('H:i') : '-' }}
                                </td>
                            @elseif ($status == 'sakit')
                                <td class="status-sakit" style="{{ $rightBorderStyle }}">Sakit</td>
                            @elseif ($status == 'izin')
                                <td class="status-izin" style="{{ $rightBorderStyle }}">Izin</td>
                            @elseif ($status == 'alpha')
                                <td class="status-alpha" style="{{ $rightBorderStyle }}">Alpha</td>
                            @elseif ($status == 'libur')
                                <td class="status-libur" style="background-color: #ffcccc; color: red; font-weight: bold; font-size: 6pt; {{ $rightBorderStyle }}">LIBUR</td>
                            @else
                                <td style="{{ $rightBorderStyle }}">-</td>
                            @endif
                        @else
                            <td style="{{ $rightBorderStyle }}">-</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-section">
        <table class="footer-table" style="border: none;">
            <tr>
                <td style="width: 70%; border: none; text-align: left; vertical-align: top; padding-left: 20px;">
                    @if(isset($holidays) && count($holidays) > 0)
                        @php
                            // Deduplicate holidays by ID
                            $uniqueHolidays = $holidays->unique('id');
                        @endphp
                        <div style="font-size: 8pt; font-weight: bold; margin-bottom: 2px;">Keterangan Libur:</div>
                        <ul style="margin: 0; padding-left: 15px; font-size: 7pt; list-style-type: none;">
                            @foreach($uniqueHolidays as $holiday)
                                <li style="margin-bottom: 2px;">
                                    <span style="font-weight: bold; color: red;">
                                        [{{ $holiday->start_date->format('d/m') }}@if($holiday->end_date && $holiday->end_date->gt($holiday->start_date))
                                        - {{ $holiday->end_date->format('d/m') }}@endif]
                                    </span>
                                    {{ $holiday->title }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </td>
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