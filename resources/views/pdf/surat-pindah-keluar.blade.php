<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Keterangan Pindah/Keluar</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 30mm 20mm 40mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.3;
            padding: 10px 20px;
        }

        .container {
            padding: 0 20px;
        }

        /* Header / Kop Surat */
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 3px double #10b981;
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .header-logo {
            display: table-cell;
            width: 70px;
            vertical-align: middle;
        }

        .header-logo img {
            width: 65px;
            height: auto;
        }

        .header-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            line-height: 1.2;
        }

        .madrasah-type {
            font-size: 14px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 0;
        }

        .madrasah-name {
            font-size: 22px;
            font-weight: bold;
            color: #10b981;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .akreditasi {
            font-size: 9px;
            color: #666;
            margin-top: 1px;
        }

        .address {
            font-size: 9px;
            color: #666;
            margin-top: 1px;
        }

        .contact {
            font-size: 8px;
            color: #666;
            margin-top: 1px;
        }

        /* Title */
        .title {
            text-align: center;
            margin: 15px 0;
        }

        .title h1 {
            font-size: 13px;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .title .nomor {
            font-size: 11px;
            margin-top: 5px;
        }

        /* Content */
        .content {
            margin: 10px 0;
            text-align: justify;
        }

        .content p {
            margin-bottom: 8px;
        }

        .data-table {
            width: 100%;
            margin: 8px 0;
        }

        .data-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .data-table td:first-child {
            width: 140px;
        }

        .data-table td:nth-child(2) {
            width: 15px;
            text-align: center;
        }

        /* Sekolah Tujuan Table */
        .sekolah-table {
            width: 100%;
            margin: 8px 0 8px 30px;
        }

        .sekolah-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .sekolah-table td:first-child {
            width: 170px;
        }

        .sekolah-table td:nth-child(2) {
            width: 15px;
            text-align: center;
        }

        /* Footer / TTD */
        .footer {
            margin-top: 20px;
        }

        .ttd-container {
            width: 100%;
        }

        .ttd-left {
            float: left;
            width: 45%;
        }

        .ttd-right {
            float: right;
            width: 50%;
            text-align: center;
        }

        .ttd-right .date {
            margin-bottom: 5px;
            font-size: 11px;
        }

        .ttd-right .jabatan {
            margin-bottom: 10px;
            font-size: 11px;
        }

        .qr-section {
            margin-bottom: 10px;
        }

        .qr-code {
            width: 80px;
            height: 80px;
        }

        .ttd-right .name {
            font-weight: bold;
            text-decoration: underline;
            font-size: 11px;
        }

        .ttd-right .nip {
            font-size: 10px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        /* Catatan */
        .note {
            margin-top: 25px;
            padding: 8px 10px;
            background-color: #f0fdf4;
            border-left: 3px solid #10b981;
            font-size: 9px;
            color: #666;
        }

        .qr-note {
            font-size: 7px;
            color: #999;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    @php
        // Indonesian month names
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        // Format date to Indonesian
        $formatTanggalIndo = function($date) use ($bulanIndo) {
            return $date->format('d') . ' ' . $bulanIndo[(int)$date->format('m')] . ' ' . $date->format('Y');
        };
    @endphp

    <div class="container">
        <!-- Header / Kop Surat -->
        <div class="header">
            <div class="header-content">
                @if($siteProfile->logo)
                <div class="header-logo">
                    <img src="{{ public_path('storage/' . $siteProfile->logo) }}" alt="Logo">
                </div>
                @endif
                <div class="header-text">
                    <div class="madrasah-type">MADRASAH IBTIDAIYAH</div>
                    <div class="madrasah-name">{{ $siteProfile->nama_madrasah ?? 'MADRASAH' }}</div>
                    <div class="akreditasi">
                        @if($siteProfile->nsm)NSM : {{ $siteProfile->nsm }}@endif
                        @if($siteProfile->npsn) NPSN : {{ $siteProfile->npsn }}@endif
                    </div>
                    <div class="address">{{ $siteProfile->alamat ?? '' }}</div>
                    <div class="contact">
                        @if($siteProfile->email)Email : {{ $siteProfile->email }}@endif
                        @if($siteProfile->no_hp) &nbsp;&nbsp; Telp : {{ $siteProfile->no_hp }}@endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Title -->
        <div class="title">
            <h1>Surat Keterangan Pindah/Keluar</h1>
            <div class="nomor">Nomor : {{ $siswaKeluar->nomor_surat ?? '.................................' }}</div>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Surat ini menerangkan bahwa siswa,</p>

            <table class="data-table">
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><strong>{{ strtoupper($siswaKeluar->nama_lengkap) }}</strong></td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $siswaKeluar->gender }}</td>
                </tr>
                <tr>
                    <td>Tempat, tanggal lahir</td>
                    <td>:</td>
                    <td>{{ strtoupper($siswaKeluar->tempat_lahir) }}, {{ $formatTanggalIndo($siswaKeluar->tanggal_lahir) }}</td>
                </tr>
                <tr>
                    <td>NISN</td>
                    <td>:</td>
                    <td>{{ $siswaKeluar->nisn }}</td>
                </tr>
                <tr>
                    <td>Tingkat/Kelas</td>
                    <td>:</td>
                    <td>Kelas {{ $siswaKeluar->kelas_terakhir }}</td>
                </tr>
            </table>

            <p>pada tanggal {{ $formatTanggalIndo($siswaKeluar->tanggal_keluar) }} telah tidak menjalankan Kegiatan Belajar Mengajar pada,</p>

            <table class="sekolah-table">
                <tr>
                    <td>Nama Satuan Pendidikan</td>
                    <td>:</td>
                    <td>{{ strtoupper($siteProfile->nama_madrasah ?? 'MADRASAH') }}</td>
                </tr>
                <tr>
                    <td>NPSN</td>
                    <td>:</td>
                    <td>{{ $siteProfile->npsn ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $siteProfile->alamat ?? '-' }}</td>
                </tr>
            </table>

            <p>Dikarenakan pindah satuan pendidikan/mutasi{{ $siswaKeluar->alasan_keluar ? ', ' . $siswaKeluar->alasan_keluar : '' }}. Hal-hal yang berkaitan dengan data pendidikan siswa pada sistem informasi pendataan pendidikan Kementerian Agama (EMIS) telah dijalankan sebagaimana mestinya.</p>

            @if($siswaKeluar->nomor_dokumen_emis)
            <p>Nomor Dokumen EMIS: <strong>{{ $siswaKeluar->nomor_dokumen_emis }}</strong></p>
            @endif

            <p>Demikian surat keterangan ini dibuat, untuk diketahui dan dipergunakan sebagaimana mestinya.</p>
        </div>

        <!-- Footer / TTD with QR Code -->
        <div class="footer clearfix">
            <div class="ttd-container">
                <div class="ttd-right">
                    <div class="date">{{ $siteProfile->kota ?? 'Depok' }}, {{ $formatTanggalIndo(now()) }}</div>
                    <div class="jabatan">Kepala Madrasah,</div>
                    
                    <div class="qr-section">
                        @php
                            // QR Code links to verification page
                            $verificationUrl = url('/verifikasi-surat/' . $siswaKeluar->id);
                            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' . urlencode($verificationUrl);
                        @endphp
                        <img src="{{ $qrUrl }}" class="qr-code" alt="QR Code Verifikasi">
                        <div class="qr-note">Scan untuk verifikasi</div>
                    </div>
                    
                    <div class="name">{{ $siteProfile->nama_kepala_madrasah ?? '...........................' }}</div>
                    @if($siteProfile->nip_kepala_madrasah)
                    <div class="nip">NIP. {{ $siteProfile->nip_kepala_madrasah }}</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Note -->
        @if($siswaKeluar->sekolah_tujuan)
        <div class="note">
            <strong>Catatan:</strong> Siswa pindah ke {{ $siswaKeluar->sekolah_tujuan }}
        </div>
        @endif
    </div>
</body>

</html>
