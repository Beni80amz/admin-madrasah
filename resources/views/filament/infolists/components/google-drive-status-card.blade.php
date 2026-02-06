@php
    $isConnected = $status === 'connected';
    $primaryHex = $isConnected ? '#10B981' : '#F59E0B';
    $primaryRgb = $isConnected ? '16, 185, 129' : '245, 158, 11';
    $icon = $isConnected ? 'heroicon-o-check-badge' : 'heroicon-o-exclamation-circle';
    $title = $isConnected ? 'Google Drive Terhubung' : 'Google Drive Terputus';
@endphp

<div class="google-drive-status-card">
    <style>
        .google-drive-status-card .status-card-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            border: 1px solid rgba({{ $primaryRgb }}, 0.2);
            background: white;
            padding: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            transition: all 0.5s ease;
        }

        .dark .google-drive-status-card .status-card-wrapper {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(12px);
            border-color: rgba({{ $primaryRgb }}, 0.3);
        }

        .google-drive-status-card .glow-1 {
            position: absolute;
            top: -5rem;
            right: -5rem;
            width: 16rem;
            height: 16rem;
            border-radius: 9999px;
            background: rgba({{ $primaryRgb }}, 0.1);
            filter: blur(60px);
            pointer-events: none;
        }

        .google-drive-status-card .icon-container {
            position: relative;
            display: flex;
            height: 6rem;
            width: 6rem;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            background: rgba({{ $primaryRgb }}, 0.1);
            margin: 0 auto;
        }

        .google-drive-status-card .pulse-bg {
            position: absolute;
            inset: 0;
            border-radius: 9999px;
            background: rgba({{ $primaryRgb }}, 0.2);
            animation: gd-ping 3s cubic-bezier(0, 0, 0.2, 1) infinite;
        }

        @keyframes gd-ping {

            75%,
            100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        .google-drive-status-card .card-title {
            font-size: 1.5rem;
            font-weight: 900;
            margin-top: 1.5rem;
            color: #111827;
            text-align: center;
        }

        .dark .google-drive-status-card .card-title {
            color: white;
        }

        .google-drive-status-card .card-desc {
            margin-top: 1rem;
            font-size: 0.875rem;
            line-height: 1.5;
            color: #6B7280;
            text-align: center;
            max-width: 24rem;
        }
    </style>

    <div class="status-card-wrapper">
        <div class="glow-1"></div>

        <div class="icon-container">
            <div class="pulse-bg"></div>
            <x-dynamic-component :component="$icon"
                style="width: 48px; height: 48px; position: relative; color: {{ $primaryHex }};" />
        </div>

        <div class="card-title">{{ $title }}</div>
        <div class="card-desc">
            @if($isConnected)
                Sistem siap mengamankan berkas administrasi Anda secara otomatis di penyimpanan awan Google Drive.
            @else
                Hubungkan akun Google Drive Anda untuk mulai menggunakan fitur pencadangan berkas otomatis.
            @endif
        </div>

        @if($isConnected)
            <div
                style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-top: 1.5rem; background: rgba(16, 185, 129, 0.1); padding: 0.375rem 1rem; border-radius: 9999px; width: fit-content; margin-left: auto; margin-right: auto;">
                <span
                    style="height: 0.5rem; width: 0.5rem; border-radius: 9999px; background: #10B981; display: inline-block;"></span>
                <span
                    style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.05em; color: #065F46;">Active
                    Sync</span>
            </div>
        @endif
    </div>
</div>