<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Struktur Organisasi</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8px;
            color: #333;
            margin: 0;
            padding: 5px 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 2px solid #10b981;
        }

        .header h1 {
            font-size: 14px;
            color: #10b981;
            margin: 0;
        }

        .header p {
            font-size: 8px;
            color: #666;
            margin: 3px 0 0 0;
        }

        .title {
            text-align: center;
            margin-bottom: 8px;
        }

        .title h2 {
            font-size: 12px;
            margin: 0;
        }

        .title span {
            font-size: 9px;
            color: #10b981;
        }

        .org-chart {
            text-align: center;
        }

        .level {
            margin-bottom: 4px;
        }

        .connector {
            width: 2px;
            height: 6px;
            background: #10b981;
            margin: 3px auto;
        }

        .h-line {
            height: 1px;
            background: #ccc;
            margin: 3px 120px;
        }

        .card {
            display: inline-block;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 3px 5px;
            margin: 2px;
            text-align: center;
            vertical-align: top;
            background: #fff;
        }

        .card.gold {
            border-color: #f59e0b;
            background: #fffbeb;
        }

        .card.green {
            border-color: #10b981;
            background: #f0fdf4;
        }

        .photo {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            margin: 0 auto 2px;
            display: block;
        }

        .init {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #10b981;
            color: #fff;
            font-size: 10px;
            font-weight: bold;
            line-height: 28px;
            text-align: center;
            margin: 0 auto 2px;
        }

        .init.gold {
            background: #f59e0b;
        }

        .init.blue {
            background: #3b82f6;
        }

        .init.purple {
            background: #8b5cf6;
        }

        .init.rose {
            background: #f43f5e;
        }

        .init.gray {
            background: #6b7280;
        }

        .name {
            font-size: 7px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .badge {
            font-size: 5px;
            color: #fff;
            background: #10b981;
            padding: 2px 3px;
            border-radius: 3px;
            display: inline-block;
        }

        .badge.gold {
            background: #f59e0b;
        }

        .badge.blue {
            background: #3b82f6;
        }

        .badge.purple {
            background: #8b5cf6;
        }

        .badge.rose {
            background: #f43f5e;
        }

        .badge.gray {
            background: #6b7280;
        }

        .footer {
            margin-top: 8px;
            padding-top: 3px;
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
            font-size: 6px;
            color: #666;
        }

        .footer-right {
            text-align: right;
            width: 50px;
        }

        .qr-code {
            width: 40px;
            height: 40px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $profile->nama_madrasah ?? 'Madrasah' }}</h1>
        <p>{{ $profile->alamat ?? '' }}</p>
    </div>

    <div class="title">
        <h2>STRUKTUR ORGANISASI</h2>
        <span>Tahun Ajaran {{ $tahunAjaran->nama ?? '-' }}</span>
    </div>

    <div class="org-chart">

        @if($strukturLevel0->count() > 0)
            <div class="level">
                @foreach($strukturLevel0 as $item)
                    <div class="card gold">
                        @if($item->photo_display && file_exists(public_path('storage/' . $item->photo_display)))
                            <img src="{{ public_path('storage/' . $item->photo_display) }}" class="photo">
                        @else
                            <div class="init gold">{{ $item->initials }}</div>
                        @endif
                        <div class="name">{{ $item->nama_display }}</div>
                        <div class="badge gold">{{ $item->jabatan_display }}</div>
                    </div>
                @endforeach
            </div>
            <div class="connector"></div>
        @endif

        @if($strukturLevel1->count() > 0)
            <div class="level">
                @foreach($strukturLevel1 as $item)
                    <div class="card green">
                        @if($item->photo_display && file_exists(public_path('storage/' . $item->photo_display)))
                            <img src="{{ public_path('storage/' . $item->photo_display) }}" class="photo">
                        @else
                            <div class="init">{{ $item->initials }}</div>
                        @endif
                        <div class="name">{{ $item->nama_display }}</div>
                        <div class="badge">{{ $item->jabatan_display }}</div>
                    </div>
                @endforeach
            </div>
            <div class="connector"></div>
        @endif

        @if($strukturLevel2->count() > 0)
            <div class="h-line"></div>
            <div class="level">
                @foreach($strukturLevel2 as $item)
                    <div class="card">
                        @if($item->photo_display && file_exists(public_path('storage/' . $item->photo_display)))
                            <img src="{{ public_path('storage/' . $item->photo_display) }}" class="photo">
                        @else
                            <div class="init blue">{{ $item->initials }}</div>
                        @endif
                        <div class="name">{{ $item->nama_display }}</div>
                        <div class="badge blue">{{ $item->jabatan_display }}</div>
                    </div>
                @endforeach
            </div>
            <div class="connector"></div>
        @endif

        @if($strukturLevel3->count() > 0)
            <div class="h-line"></div>
            <div class="level">
                @foreach($strukturLevel3 as $item)
                    <div class="card">
                        @if($item->photo_display && file_exists(public_path('storage/' . $item->photo_display)))
                            <img src="{{ public_path('storage/' . $item->photo_display) }}" class="photo">
                        @else
                            <div class="init purple">{{ $item->initials }}</div>
                        @endif
                        <div class="name">{{ $item->nama_display }}</div>
                        <div class="badge purple">{{ $item->jabatan_display }}</div>
                    </div>
                @endforeach
            </div>
            <div class="connector"></div>
        @endif

        @if($strukturLevel4->count() > 0)
            <div class="h-line"></div>
            <div class="level">
                @foreach($strukturLevel4 as $item)
                    <div class="card">
                        @if($item->photo_display && file_exists(public_path('storage/' . $item->photo_display)))
                            <img src="{{ public_path('storage/' . $item->photo_display) }}" class="photo">
                        @else
                            <div class="init rose">{{ $item->initials }}</div>
                        @endif
                        <div class="name">{{ $item->nama_display }}</div>
                        <div class="badge rose">{{ $item->jabatan_display }}</div>
                    </div>
                @endforeach
            </div>
            <div class="connector"></div>
        @endif

        @if($strukturLevel5->count() > 0)
            <div class="h-line"></div>
            <div class="level">
                @foreach($strukturLevel5 as $item)
                    <div class="card">
                        @if($item->photo_display && file_exists(public_path('storage/' . $item->photo_display)))
                            <img src="{{ public_path('storage/' . $item->photo_display) }}" class="photo">
                        @else
                            <div class="init rose">{{ $item->initials }}</div>
                        @endif
                        <div class="name">{{ $item->nama_display }}</div>
                        <div class="badge rose">{{ $item->jabatan_display }}</div>
                    </div>
                @endforeach
            </div>
            <div class="connector"></div>
        @endif

        @if($strukturLevel6->count() > 0)
            <div class="h-line"></div>
            <div class="level">
                @foreach($strukturLevel6 as $item)
                    <div class="card">
                        @if($item->photo_display && file_exists(public_path('storage/' . $item->photo_display)))
                            <img src="{{ public_path('storage/' . $item->photo_display) }}" class="photo">
                        @else
                            <div class="init gray">{{ $item->initials }}</div>
                        @endif
                        <div class="name">{{ $item->nama_display }}</div>
                        <div class="badge gray">{{ $item->jabatan_display }}</div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    <p>Dokumen ini dicetak pada {{ now()->setTimezone('Asia/Jakarta')->format('d F Y H:i') }} WIB</p>
                    <p>{{ $profile->nama_madrasah ?? 'Madrasah' }} - {{ $profile->alamat ?? '' }}</p>
                    <p style="margin-top: 2px; font-size: 5px; color: #999;">Scan QR code untuk verifikasi</p>
                </td>
                <td class="footer-right">
                    @php
                        $verificationUrl = url('/profil/verifikasi');
                        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=40x40&data=' . urlencode($verificationUrl);
                    @endphp
                    <img src="{{ $qrUrl }}" class="qr-code" alt="QR Code Verifikasi">
                </td>
            </tr>
        </table>
    </div>
</body>

</html>