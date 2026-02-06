@php
    $accentColor = match ($color) {
        'primary' => '#10B981',
        'success' => '#3B82F6',
        'info' => '#6366F1',
        default => '#6B7280'
    };
    $accentRgb = match ($color) {
        'primary' => '16, 185, 129',
        'success' => '59, 130, 246',
        'info' => '99, 102, 241',
        default => '107, 114, 128'
    };
@endphp

<div class="google-drive-benefit-card">
    <style>
        .google-drive-benefit-card .benefit-wrapper {
            display: flex;
            padding: 1.5rem;
            border-radius: 1rem;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .dark .google-drive-benefit-card .benefit-wrapper {
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .google-drive-benefit-card .icon-box {
            display: flex;
            height: 3rem;
            width: 3rem;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            background: rgba({{ $accentRgb }}, 0.1);
            margin-right: 1.25rem;
        }

        .google-drive-benefit-card .benefit-title {
            font-size: 0.875rem;
            font-weight: 900;
            color: #111827;
        }

        .dark .google-drive-benefit-card .benefit-title {
            color: white;
        }

        .google-drive-benefit-card .benefit-desc {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            line-height: 1.5;
            color: #6B7280;
        }
    </style>

    <div class="benefit-wrapper">
        <div class="icon-box">
            <x-dynamic-component :component="$icon" style="width: 24px; height: 24px; color: {{ $accentColor }};" />
        </div>
        <div>
            <div class="benefit-title">{{ $title }}</div>
            <div class="benefit-desc">{{ $description }}</div>
        </div>
    </div>
</div>