<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pendaftaran PPDB - {{ $registration->no_daftar }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #1f2937;
            background: #fff;
        }

        .container {
            padding: 20px 30px;
        }

        /* Header */
        .header {
            display: table;
            width: 100%;
            border-bottom: 3px double #059669;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .header-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }

        .header-logo img {
            width: 55px;
            height: 55px;
        }

        .header-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding-left: 15px;
        }

        .header-text h1 {
            font-size: 16pt;
            font-weight: bold;
            color: #059669;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .header-text p {
            font-size: 9pt;
            color: #6b7280;
            margin: 0;
        }

        /* Title */
        .title {
            text-align: center;
            margin: 12px 0;
            padding: 10px;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            border-radius: 6px;
        }

        .title h2 {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .title p {
            font-size: 10pt;
            opacity: 0.9;
        }

        /* Registration Code */
        .reg-code {
            text-align: center;
            margin: 10px 0;
            padding: 12px;
            border: 2px dashed #059669;
            border-radius: 8px;
            background: #f0fdf4;
        }

        .reg-code .label {
            font-size: 8pt;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }

        .reg-code .code {
            font-size: 18pt;
            font-weight: bold;
            color: #059669;
            font-family: 'DejaVu Sans Mono', monospace;
        }

        /* Data Section */
        .section {
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 10pt;
            font-weight: bold;
            color: #059669;
            margin-bottom: 5px;
            padding-bottom: 3px;
            border-bottom: 2px solid #d1fae5;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table tr td {
            padding: 4px 8px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 9pt;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .data-table .label {
            width: 35%;
            color: #6b7280;
            font-size: 10pt;
        }

        .data-table .value {
            font-weight: 500;
            color: #1f2937;
        }

        /* QR Code Section */
        .qr-section {
            display: table;
            width: 100%;
            margin-top: 12px;
            padding: 10px;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .qr-code {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
            text-align: center;
        }

        .qr-code img {
            width: 70px;
            height: 70px;
        }

        .qr-info {
            display: table-cell;
            vertical-align: middle;
            padding-left: 20px;
        }

        .qr-info p {
            font-size: 9pt;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .qr-info .note {
            font-size: 8pt;
            color: #9ca3af;
            font-style: italic;
        }

        /* Footer */
        .footer {
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
        }

        .footer-content {
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }

        .footer-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: center;
        }

        .footer-notes {
            font-size: 8pt;
            color: #6b7280;
        }

        .footer-notes ul {
            margin: 5px 0 0 15px;
        }

        .footer-notes li {
            margin-bottom: 3px;
        }

        .signature {
            margin-top: 10px;
        }

        .signature .date {
            font-size: 8pt;
            color: #6b7280;
            margin-bottom: 40px;
        }

        .signature .name {
            font-size: 10pt;
            font-weight: bold;
            color: #1f2937;
            border-top: 1px solid #1f2937;
            padding-top: 5px;
            display: inline-block;
        }

        .signature .position {
            font-size: 9pt;
            color: #6b7280;
        }

        /* Print styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-logo">
                @if($profile && $profile->logo)
                    <img src="{{ public_path('storage/' . $profile->logo) }}" alt="Logo">
                @endif
            </div>
            <div class="header-text">
                <h1>{{ $profile->nama_madrasah ?? 'Madrasah Ibtidaiyah' }}</h1>
                @if($profile && $profile->nsm)
                    <p style="font-size: 10pt; color: #059669; font-weight: bold; margin-bottom: 3px;">NSM:
                        {{ $profile->nsm }}
                    </p>
                @endif
                <p>{{ $profile->alamat ?? '' }}</p>
                <p>Telp: {{ $profile->no_hp ?? '-' }} | Email: {{ $profile->email ?? '-' }}</p>
            </div>
        </div>

        <!-- Title -->
        <div class="title">
            <h2>Bukti Pendaftaran PPDB</h2>
            <p>Tahun Ajaran {{ $ppdbInfo['tahun_ajaran'] }}</p>
        </div>

        <!-- Registration Code -->
        <div class="reg-code">
            <div class="label">Nomor Pendaftaran</div>
            <div class="code">{{ $registration->no_daftar }}</div>
        </div>

        <!-- Data Calon Siswa -->
        <div class="section">
            <div class="section-title">Data Calon Siswa</div>
            <table class="data-table">
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td class="value">{{ $registration->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td class="label">NIK</td>
                    <td class="value">{{ $registration->nik }}</td>
                </tr>
                @if($registration->nisn)
                    <tr>
                        <td class="label">NISN</td>
                        <td class="value">{{ $registration->nisn }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="label">Tempat, Tanggal Lahir</td>
                    <td class="value">{{ $registration->tempat_lahir }},
                        {{ $registration->tanggal_lahir->translatedFormat('d F Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="value">{{ $registration->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td class="label">Agama</td>
                    <td class="value">{{ $registration->agama }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat</td>
                    <td class="value">{{ $registration->alamat }}</td>
                </tr>
                <tr>
                    <td class="label">Asal Sekolah</td>
                    <td class="value">
                        {{ $registration->asal_sekolah }}{{ $registration->nama_sekolah_asal ? ' - ' . $registration->nama_sekolah_asal : '' }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Data Orang Tua -->
        <div class="section">
            <div class="section-title">Data Orang Tua / Wali</div>
            <table class="data-table">
                <tr>
                    <td class="label">Nama Ayah</td>
                    <td class="value">{{ $registration->nama_ayah }}</td>
                </tr>
                <tr>
                    <td class="label">Nama Ibu</td>
                    <td class="value">{{ $registration->nama_ibu }}</td>
                </tr>
                <tr>
                    <td class="label">No. HP Orang Tua</td>
                    <td class="value">{{ $registration->no_hp_ortu }}</td>
                </tr>
                @if($registration->email)
                    <tr>
                        <td class="label">Nama Wali</td>
                        <td class="value">{{ $registration->email }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <!-- QR Code -->
        <div class="qr-section">
            <div class="qr-code">
                <img src="{{ $qrCodeUrl }}" alt="QR Code">
            </div>
            <div class="qr-info">
                <p><strong>Verifikasi Digital</strong></p>
                <p>Scan QR Code untuk verifikasi keaslian bukti pendaftaran ini.</p>
                <p class="note">Tanggal Daftar: {{ $registration->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <div class="footer-left">
                    <div class="footer-notes">
                        <strong>Catatan Penting:</strong>
                        <ul>
                            <li>Simpan bukti pendaftaran ini sebagai arsip</li>
                            <li>Tunjukkan bukti ini saat verifikasi dokumen</li>
                            <li>Pantau status pendaftaran secara berkala</li>
                            <li>Hubungi panitia PPDB jika ada pertanyaan</li>
                        </ul>
                    </div>
                </div>
                <div class="footer-right">
                    <div class="signature">
                        <div class="date">Kota Depok, {{ now()->locale('id')->isoFormat('D MMMM Y') }}</div>
                        <div class="name">Panitia PPDB</div>
                        <div class="position">{{ $profile->nama_madrasah ?? 'Madrasah' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>