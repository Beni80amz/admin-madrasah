<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
    {{-- Madrasah Header --}}
    <table>
        <tr>
            <td colspan="12" style="text-align: center; font-weight: bold; font-size: 14px; color: #10B981;">
                {{ $profile->nama_madrasah ?? 'MADRASAH DINIYAH' }}
            </td>
        </tr>
        <tr>
            <td colspan="12" style="text-align: center; font-size: 10px; color: #4b5563;">
                {{ $profile->alamat ?? '' }}
                @if($profile->kelurahan), {{ $profile->kelurahan }} @endif
                @if($profile->kecamatan), {{ $profile->kecamatan }} @endif
                @if($profile->kota), {{ $profile->kota }} @endif
                @if($profile->provinsi), {{ $profile->provinsi }} @endif
            </td>
        </tr>
        <tr></tr>
        <tr>
            <td colspan="12" style="text-align: center; font-weight: bold; font-size: 16px; color: #10B981;">JADWAL
                PELAJARAN</td>
        </tr>
        <tr></tr> {{-- Empty row --}}
    </table>

    {{-- Info Box --}}
    <table style="border: 2px solid #10B981; background-color: #f0fdf4;">
        <tr>
            <td colspan="2" style="font-weight: bold;">Kelas/Rombel</td>
            <td colspan="4">: Kelas {{ $rombel->kelas?->nama ?? '' }} - {{ $rombel->nama ?? '' }}</td>
            <td colspan="2" style="font-weight: bold;">Tahun Ajaran</td>
            <td colspan="4">: {{ $tahunAjaran->nama ?? '' }}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight: bold;">Wali Kelas</td>
            <td colspan="4">: {{ $rombel->waliKelas?->nama_lengkap ?? '-' }}</td>
            <td colspan="2" style="font-weight: bold;">Semester</td>
            <td colspan="4">: {{ ucfirst($semester) }}</td>
        </tr>
    </table>

    <table>
        <tr></tr>
    </table> {{-- Spacer --}}

    {{-- Schedule Grid --}}
    <table>
        @php
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $chunks = array_chunk($days, 3); // 3 days per row
        @endphp

        @foreach($chunks as $chunk)
            {{-- Day Headers --}}
            <tr>
                @foreach($chunk as $day)
                    <td colspan="4"
                        style="background-color: #10B981; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #059669;">
                        {{ strtoupper($day) }}
                    </td>
                    <td></td> {{-- Spacer column --}}
                @endforeach
            </tr>

            {{-- Column Headers --}}
            <tr>
                @foreach($chunk as $day)
                    <td style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 60px;">KE</td>
                    <td style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 100px;">WAKTU</td>
                    <td style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 140px;">MATA PELAJARAN
                    </td>
                    <td style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 150px;">GURU</td>
                    <td></td> {{-- Spacer column --}}
                @endforeach
            </tr>

            {{-- Data Rows (Max 8 rows fixed or dynamic) --}}
            @for($i = 1; $i <= 8; $i++)
                <tr>
                    @foreach($chunk as $day)
                        @php
                            $jadwal = $jadwals->where('hari', $day)->where('jam_ke', $i)->first();
                        @endphp
                        <td style="text-align: center; border: 1px solid #000000;">{{ $i }}</td>
                        <td style="text-align: center; border: 1px solid #000000;">
                            @if($jadwal)
                                {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                            @endif
                        </td>
                        <td style="border: 1px solid #000000;">{{ $jadwal->mataPelajaran?->nama ?? '' }}</td>
                        <td style="border: 1px solid #000000;">{{ $jadwal->teacher?->nama_lengkap ?? '' }}</td>
                        <td></td> {{-- Spacer column --}}
                    @endforeach
                </tr>
            @endfor

            <tr></tr> {{-- Row Spacer --}}
        @endforeach
    </table>
</body>

</html>