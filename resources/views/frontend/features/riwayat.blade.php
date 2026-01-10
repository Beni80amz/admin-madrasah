<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Riwayat Absensi</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&amp;display=swap" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .font-display {
            font-family: 'Lexend', sans-serif;
        }
    </style>
</head>

<body class="bg-[#f6f8f6] text-[#0d1b0f] min-h-screen flex flex-col font-display">
    <header class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-md mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('dashboard.index') }}" class="p-2 -ml-2 rounded-full hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="font-bold text-lg">Riwayat Absensi</h1>
            <div class="w-10"></div>
        </div>
    </header>

    <main class="flex-1 w-full max-w-md mx-auto pt-24 px-4 pb-8 flex flex-col gap-4">
        {{-- Filter and Export --}}
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('riwayat.index') }}" method="GET" class="flex flex-col gap-3">
                <div class="flex gap-2">
                    <div class="flex-1">
                        <label class="text-xs text-gray-500 mb-1 block">Bulan</label>
                        <select name="month"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->monthName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="text-xs text-gray-500 mb-1 block">Tahun</label>
                        <select name="year"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500">
                            @foreach(range(now()->year, 2024) as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-gray-900 text-white rounded-xl py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors">
                        Tampilkan
                    </button>
                    <a href="{{ route('riwayat.export', ['month' => $month, 'year' => $year]) }}"
                        class="flex-1 flex items-center justify-center gap-2 bg-green-50 text-green-700 border border-green-200 rounded-xl py-2.5 text-sm font-medium hover:bg-green-100 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        PDF
                    </a>
                </div>
            </form>
        </div>

        {{-- Attendance List --}}
        <div class="flex flex-col gap-3">
            @forelse($attendances as $attendance)
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($attendance->date)->locale('id')->isoFormat('dddd, D MMM Y') }}
                            </span>
                            @php
                                $statusColor = match ($attendance->status) {
                                    'hadir' => 'bg-green-100 text-green-700',
                                    'telat' => 'bg-yellow-100 text-yellow-700',
                                    'izin' => 'bg-blue-100 text-blue-700',
                                    'sakit' => 'bg-purple-100 text-purple-700',
                                    'alpha' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-full {{ $statusColor }}">
                                {{ $attendance->status }}
                            </span>
                        </div>
                        <div class="flex items-center gap-4 text-xs text-gray-500">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px] text-green-600">login</span>
                                {{ $attendance->time_in ?? '--:--' }}
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px] text-red-500">logout</span>
                                {{ $attendance->time_out ?? '--:--' }}
                            </div>
                            {{-- Show lateness if present --}}
                            @if($attendance->keterlambatan > 0)
                                <div class="text-orange-600 font-medium">
                                    +{{ $attendance->keterlambatan }}m
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Status Icon/Visual --}}
                    <div class="size-10 rounded-full flex items-center justify-center {{ $statusColor }}">
                        <span class="material-symbols-outlined">
                            @if($attendance->status == 'hadir') check_circle
                            @elseif($attendance->status == 'telat') schedule
                            @elseif($attendance->status == 'sakit') sick
                            @elseif($attendance->status == 'izin') assignment_ind
                            @else help @endif
                        </span>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-12 text-center text-gray-500">
                    <span class="material-symbols-outlined text-4xl mb-2 text-gray-300">event_busy</span>
                    <p class="text-sm">Tidak ada data absensi untuk periode ini.</p>
                </div>
            @endforelse
        </div>
    </main>
</body>

</html>