<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Penerimaan Sementara</title>
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

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 8px;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
        }

        .status-disetujui {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .status-ditolak {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
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
            width: 150px;
        }

        .data-table td:nth-child(2) {
            width: 15px;
            text-align: center;
        }

        /* Sekolah Asal Table */
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

        /* Note */
        .note {
            margin-top: 25px;
            padding: 8px 10px;
            background-color: #fef3c7;
            border-left: 3px solid #f59e0b;
            font-size: 9px;
            color: #666;
        }

        .note-approved {
            background-color: #d1fae5;
            border-left-color: #10b981;
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
            if (!$date) return '-';
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
            <h1>Surat Penerimaan {{ $siswaMasuk->isPending() ? 'Sementara' : '' }}</h1>
            <div class="nomor">Nomor : {{ $siswaMasuk->nomor_surat_penerimaan ?? '.................................' }}</div>
            
            <!-- Status Badge -->
            <div class="status-badge status-{{ $siswaMasuk->status }}">
                @if($siswaMasuk->isPending())
                    Menunggu Verifikasi
                @elseif($siswaMasuk->isApproved())
                    Disetujui
                @else
                    Ditolak
                @endif
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Yang bertanda tangan di bawah ini, Kepala {{ $siteProfile->nama_madrasah ?? 'Madrasah' }} menerangkan bahwa:</p>

            <table class="data-table">
                <tr>
                    <td>Nama Lengkap</td>
                    <td>:</td>
                    <td><strong>{{ strtoupper($siswaMasuk->nama_lengkap) }}</strong></td>
                </tr>
                @if($siswaMasuk->nisn)
                <tr>
                    <td>NISN</td>
                    <td>:</td>
                    <td>{{ $siswaMasuk->nisn }}</td>
                </tr>
                @endif
                @if($siswaMasuk->nik)
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{ $siswaMasuk->nik }}</td>
                </tr>
                @endif
                @if($siswaMasuk->gender)
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $siswaMasuk->gender }}</td>
                </tr>
                @endif
                @if($siswaMasuk->tempat_lahir && $siswaMasuk->tanggal_lahir)
                <tr>
                    <td>Tempat, Tanggal Lahir</td>
                    <td>:</td>
                    <td>{{ strtoupper($siswaMasuk->tempat_lahir) }}, {{ $formatTanggalIndo($siswaMasuk->tanggal_lahir) }}</td>
                </tr>
                @endif
                @if($siswaMasuk->nama_ayah)
                <tr>
                    <td>Nama Ayah</td>
                    <td>:</td>
                    <td>{{ $siswaMasuk->nama_ayah }}</td>
                </tr>
                @endif
                @if($siswaMasuk->nama_ibu)
                <tr>
                    <td>Nama Ibu</td>
                    <td>:</td>
                    <td>{{ $siswaMasuk->nama_ibu }}</td>
                </tr>
                @endif
            </table>

            <p>telah diterima {{ $siswaMasuk->isPending() ? 'sementara' : '' }} sebagai siswa pindahan/mutasi di {{ $siteProfile->nama_madrasah ?? 'Madrasah' }} dengan keterangan sebagai berikut:</p>

            <table class="sekolah-table">
                <tr>
                    <td>Sekolah Asal</td>
                    <td>:</td>
                    <td>{{ strtoupper($siswaMasuk->sekolah_asal) }}</td>
                </tr>
                <tr>
                    <td>Kelas Asal</td>
                    <td>:</td>
                    <td>{{ $siswaMasuk->kelas_asal ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Kelas Tujuan</td>
                    <td>:</td>
                    <td>Kelas {{ $siswaMasuk->kelas_tujuan }}</td>
                </tr>
                <tr>
                    <td>Tanggal Masuk</td>
                    <td>:</td>
                    <td>{{ $formatTanggalIndo($siswaMasuk->tanggal_masuk) }}</td>
                </tr>
                @if($siswaMasuk->nomor_surat_pindah)
                <tr>
                    <td>No. Surat Pindah Asal</td>
                    <td>:</td>
                    <td>{{ $siswaMasuk->nomor_surat_pindah }}</td>
                </tr>
                @endif
                @if($siswaMasuk->alasan_pindah)
                <tr>
                    <td>Alasan Pindah</td>
                    <td>:</td>
                    <td>{{ $siswaMasuk->alasan_pindah }}</td>
                </tr>
                @endif
            </table>

            <p>Demikian surat penerimaan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
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
                            $verificationUrl = url('/verifikasi-surat-masuk/' . $siswaMasuk->id);
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
        <div class="note {{ $siswaMasuk->isApproved() ? 'note-approved' : '' }}">
            @if($siswaMasuk->isPending())
                <strong>Catatan:</strong> Surat ini bersifat SEMENTARA dan memerlukan verifikasi dari pihak madrasah. 
                Penerimaan siswa baru berlaku setelah diverifikasi dan disetujui oleh Kepala Madrasah.
            @elseif($siswaMasuk->isApproved())
                <strong>Catatan:</strong> Surat ini telah DIVERIFIKASI dan DISETUJUI pada {{ $formatTanggalIndo($siswaMasuk->verified_at) }}.
                Siswa telah resmi diterima sebagai siswa aktif di {{ $siteProfile->nama_madrasah ?? 'Madrasah' }}.
            @else
                <strong>Catatan:</strong> Permohonan penerimaan siswa ini telah DITOLAK pada {{ $formatTanggalIndo($siswaMasuk->verified_at) }}.
                @if($siswaMasuk->catatan_verifikasi)
                    <br>Alasan: {{ $siswaMasuk->catatan_verifikasi }}
                @endif
            @endif
        </div>
    </div>
</body>

</html>
