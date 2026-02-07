<x-filament-widgets::widget>
    @php
        /**
         * @var \App\Models\Attendance|null $attendance
         */
    @endphp
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                Kehadiran Hari Ini
            </h2>
            <span class="text-xs text-gray-500 dark:text-gray-400 font-mono">
                {{ now()->format('H:i') }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4">
            {{-- Jam Masuk --}}
            <div
                class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 text-center">
                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                    Jam Masuk
                </div>
                <div
                    class="text-2xl font-bold {{ $attendance?->time_in ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400' }}">
                    {{ $attendance?->time_in ?? '--:--' }}
                </div>
                @if($attendance?->keterlambatan > 0)
                    <div class="text-xs text-red-500 mt-1 font-medium">
                        Telat {{ $attendance->keterlambatan }}m
                    </div>
                @else
                    <div class="text-xs text-green-500 mt-1 font-medium">
                        Tepat Waktu
                    </div>
                @endif
            </div>

            {{-- Jam Pulang --}}
            <div
                class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 text-center">
                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                    Jam Pulang
                </div>
                <div
                    class="text-2xl font-bold {{ $attendance?->time_out ? 'text-green-600 dark:text-green-400' : 'text-gray-400' }}">
                    {{ $attendance?->time_out ?? '--:--' }}
                </div>
                @if($attendance?->lembur > 0)
                    <div class="text-xs text-blue-500 mt-1 font-medium">
                        + Lembur
                        @php
                            $jam = floor($attendance->lembur / 60);
                            $menit = $attendance->lembur % 60;
                            // Format: 1j 30m or 30m
                            $lemburStr = ($jam > 0 ? $jam . 'j ' : '') . ($menit > 0 ? $menit . 'm' : '');
                           @endphp
                        {{ $lemburStr }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Status Badge --}}
        <div class="mt-4 text-center">
            @if($attendance)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            {{ match (strtolower($attendance->status)) {
                    'hadir' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                    'telat' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                    'izin' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                    'sakit' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                    'alpha' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                } }}">
                            Status: {{ ucfirst($attendance->status) }}
                        </span>
            @else
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                    Status: Belum Absen
                </span>
            @endif
        </div>

    </x-filament::section>
</x-filament-widgets::widget>