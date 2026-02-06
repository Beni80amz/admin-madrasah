@php
    $accentColor = $isMain ? '#10B981' : '#3B82F6';
    $icon = $isMain ? 'heroicon-o-cloud' : 'heroicon-o-folder';
@endphp

<div class="google-drive-folder-card">
    <style>
        .google-drive-folder-card .card-link {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            border-radius: 1rem;
            background: white;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .dark .google-drive-folder-card .card-link {
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .google-drive-folder-card .card-link:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .google-drive-folder-card .icon-box {
            display: flex;
            height: 3.5rem;
            width: 3.5rem;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            background: rgba({{ $isMain ? '16, 185, 129' : '59, 130, 246' }}, 0.1);
            transition: all 0.5s ease;
        }

        .google-drive-folder-card .card-link:hover .icon-box {
            transform: rotate(6deg) scale(1.1);
        }

        .google-drive-folder-card .text-title {
            font-size: 0.875rem;
            font-weight: 800;
            color: #111827;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .google-drive-folder-card .text-title {
            color: white;
        }

        .google-drive-folder-card .text-subtitle {
            font-size: 0.625rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6B7280;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .google-drive-folder-card .dot {
            height: 0.375rem;
            width: 0.375rem;
            border-radius: 9999px;
            background:
                {{ $isConnected ? '#10B981' : '#D1D5DB' }}
            ;
        }
    </style>

    <a href="{{ $link }}" target="_blank" class="card-link">
        <div class="icon-box">
            <x-dynamic-component :component="$icon" style="width: 28px; height: 28px; color: {{ $accentColor }};" />
        </div>

        <div style="flex: 1; min-width: 0;">
            <div class="text-title">{{ $name }}</div>
            <div class="text-subtitle">
                <span class="dot"></span>
                {{ $isMain ? 'Root Directory' : 'Sub Directory' }}
            </div>
        </div>

        <div
            style="height: 2.25rem; width: 2.25rem; display: flex; align-items: center; justify-content: center; border-radius: 0.5rem; background: #F9FAFB; color: #9CA3AF;">
            <x-heroicon-m-arrow-top-right-on-square style="width: 16px; height: 16px;" />
        </div>
    </a>
</div>