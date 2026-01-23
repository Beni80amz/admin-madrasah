<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Profil Madrasah</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 30mm 20mm 40mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 15px 25px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #10b981;
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .logo-container {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }

        .logo {
            width: 70px;
            height: 70px;
        }

        .header-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .header h1 {
            font-size: 18px;
            color: #10b981;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header p {
            font-size: 10px;
            color: #666;
            margin: 3px 0;
        }

        .header-info {
            font-size: 9px;
            color: #888;
            margin-top: 5px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #10b981;
            text-transform: uppercase;
        }

        .section-content {
            text-align: justify;
            padding: 10px;
            background: #f9fafb;
            border-radius: 5px;
            border-left: 4px solid #10b981;
        }

        .identity-table {
            width: 100%;
            border-collapse: collapse;
        }

        .identity-table tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .identity-table td {
            padding: 8px 10px;
            vertical-align: top;
        }

        .identity-table td:first-child {
            width: 35%;
            font-weight: bold;
            color: #374151;
        }

        .identity-table td:nth-child(2) {
            width: 3%;
            text-align: center;
        }

        .identity-table td:last-child {
            width: 62%;
            color: #1f2937;
        }

        .visi-misi-container {
            padding: 10px;
            background: #f0fdf4;
            border-radius: 5px;
            border: 1px solid #10b981;
        }

        .visi-box, .misi-box {
            margin-bottom: 15px;
        }

        .visi-box:last-child, .misi-box:last-child {
            margin-bottom: 0;
        }

        .box-title {
            font-size: 12px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 8px;
            padding: 5px 10px;
            background: #d1fae5;
            border-radius: 3px;
            display: inline-block;
        }

        .box-content {
            padding-left: 10px;
            text-align: justify;
        }

        .tujuan-list {
            padding-left: 20px;
            margin: 0;
        }

        .tujuan-list li {
            margin-bottom: 5px;
            text-align: justify;
        }

        .section-content ul,
        .visi-misi-container ul {
            padding-left: 20px;
            margin: 5px 0;
        }

        .section-content li,
        .visi-misi-container li {
            margin-bottom: 8px;
            text-align: justify;
            list-style-type: disc;
        }

        .section-content li::marker,
        .visi-misi-container li::marker {
            color: #10b981;
        }

        .section-content p,
        .visi-misi-container p {
            margin: 0 0 8px 0;
        }

        .section-content ol {
            padding-left: 20px;
            margin: 5px 0;
        }

        .section-content ol li {
            list-style-type: decimal;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }

        .footer-table {
            width: 100%;
            border: none;
        }

        .footer-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .footer-left {
            text-align: left;
            font-size: 8px;
            color: #666;
        }

        .footer-right {
            text-align: right;
            width: 70px;
        }

        .qr-code {
            width: 60px;
            height: 60px;
        }

        .kepala-section {
            margin-top: 30px;
            text-align: right;
            padding-right: 50px;
        }

        .kepala-info {
            display: inline-block;
            text-align: center;
        }

        .kepala-photo {
            width: 80px;
            height: 100px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .kepala-name {
            font-weight: bold;
            font-size: 11px;
            margin-top: 5px;
        }

        .kepala-title {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-content">
            @if($profile->logo && file_exists(public_path('storage/' . $profile->logo)))
                <div class="logo-container">
                    <img src="{{ public_path('storage/' . $profile->logo) }}" class="logo">
                </div>
            @endif
            <div class="header-text">
                <h1>{{ $profile->nama_madrasah ?? 'Madrasah' }}</h1>
                <p>{{ $profile->alamat ?? '' }}</p>
                <div class="header-info">
                    @if($profile->nsm)NSM: {{ $profile->nsm }}@endif
                    @if($profile->nsm && $profile->npsn) | @endif
                    @if($profile->npsn)NPSN: {{ $profile->npsn }}@endif
                </div>
            </div>
        </div>
    </div>

    {{-- Identitas Madrasah --}}
    <div class="section">
        <div class="section-title">Identitas Madrasah</div>
        <table class="identity-table">
            <tr>
                <td>Nama Madrasah</td>
                <td>:</td>
                <td>{{ $profile->nama_madrasah ?? '-' }}</td>
            </tr>
            <tr>
                <td>NSM</td>
                <td>:</td>
                <td>{{ $profile->nsm ?? '-' }}</td>
            </tr>
            <tr>
                <td>NPSN</td>
                <td>:</td>
                <td>{{ $profile->npsn ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tahun Berdiri</td>
                <td>:</td>
                <td>{{ $profile->tahun_berdiri ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $profile->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td>Kepala Madrasah</td>
                <td>:</td>
                <td>{{ $profile->nama_kepala_madrasah ?? '-' }}</td>
            </tr>
        </table>
    </div>

    {{-- Sejarah Singkat --}}
    @if($profile->sejarah_singkat)
    <div class="section">
        <div class="section-title">Sejarah Singkat</div>
        <div class="section-content">
            {!! $profile->sejarah_singkat !!}
        </div>
    </div>
    @endif

    {{-- Visi --}}
    @if($profile->visi)
    <div class="section">
        <div class="section-title">Visi</div>
        <div class="visi-misi-container">
            {!! $profile->visi !!}
        </div>
    </div>
    @endif

    {{-- Misi --}}
    @if($profile->misi)
    <div class="section">
        <div class="section-title">Misi</div>
        <div class="section-content">
            {!! $profile->misi !!}
        </div>
    </div>
    @endif

    {{-- Tujuan Madrasah --}}
    @if($profile->tujuan_madrasah)
    <div class="section">
        <div class="section-title">Tujuan Madrasah</div>
        <div class="section-content">
            {!! $profile->tujuan_madrasah !!}
        </div>
    </div>
    @endif

    {{-- Signature Section --}}
    <div class="kepala-section">
        <div class="kepala-info">
            <p>Bogor, {{ now()->translatedFormat('d F Y') }}</p>
            <p>Kepala Madrasah</p>
            
            <div style="position: relative; display: inline-block; height: 80px; width: 100%; margin-top: 10px; min-width: 150px;">
                <!-- Stempel (Left of Signature) -->
                @if($profile->stempel_madrasah && file_exists(public_path('storage/' . $profile->stempel_madrasah)))
                    <div style="position: absolute; left: -40px; top: -10px; z-index: 1;">
                        <img src="{{ public_path('storage/' . $profile->stempel_madrasah) }}" 
                             style="width: 80px; height: 80px; opacity: 0.9;">
                    </div>
                @endif
    
                <!-- Tanda Tangan -->
                @if($profile->tanda_tangan_kepala_madrasah && file_exists(public_path('storage/' . $profile->tanda_tangan_kepala_madrasah)))
                    <div style="position: relative; z-index: 2; display: inline-block;">
                        <img src="{{ public_path('storage/' . $profile->tanda_tangan_kepala_madrasah) }}" 
                             style="height: 80px; width: auto; max-width: 150px;">
                    </div>
                @else
                    <div style="height: 80px;"></div>
                @endif
            </div>
    
            <p class="kepala-name">{{ $profile->nama_kepala_madrasah }}</p>
            <p class="kepala-title">NIP. {{ $profile->nip_kepala_madrasah ?? '-' }}</p>
        </div>
    </div>

    {{-- Footer with QR Code --}}
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    <p>Dokumen ini dicetak pada {{ now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</p>
                    <p>{{ $profile->nama_madrasah ?? 'Madrasah' }} - {{ $profile->alamat ?? '' }}</p>
                    <p style="margin-top: 5px; font-size: 7px; color: #999;">Scan QR code untuk verifikasi dokumen</p>
                </td>
                <td class="footer-right">
                    <img src="{{ $qrCodeImage }}" class="qr-code" alt="QR Code Verifikasi">
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
