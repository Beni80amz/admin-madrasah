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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Jam Masuk Card --}}
            <div
                class="relative overflow-hidden rounded-xl bg-white dark:bg-gray-800 p-5 shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md group">
                <div
                    class="absolute right-0 top-0 -mt-2 -mr-2 h-16 w-16 rounded-full bg-blue-50 dark:bg-blue-900/20 blur-xl transition-all group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30">
                </div>

                <div class="relative flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jam Masuk</p>
                        <h3
                            class="mt-1 text-2xl font-bold tracking-tight {{ $attendance?->time_in ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-600' }}">
                            {{ $attendance?->time_in ?? '--:--' }}
                        </h3>
                    </div>
                    <div class="rounded-lg bg-blue-50 dark:bg-blue-900/30 p-2 text-blue-600 dark:text-blue-400">
                        <x-heroicon-m-arrow-right-end-on-rectangle class="h-6 w-6" />
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2">
                    @if($attendance?->keterlambatan > 0)
                        <span
                            class="inline-flex items-center gap-1 rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-400 ring-1 ring-inset ring-red-600/10 dark:ring-red-400/20">
                            <x-heroicon-m-exclamation-circle class="h-3 w-3" />
                            Telat {{ $attendance->keterlambatan }}m
                        </span>
                    @elseif($attendance?->time_in)
                        <span
                            class="inline-flex items-center gap-1 rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400 ring-1 ring-inset ring-green-600/20 dark:ring-green-400/20">
                            <x-heroicon-m-check-circle class="h-3 w-3" />
                            Tepat Waktu
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1 rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400 ring-1 ring-inset ring-gray-500/10">
                            Belum Absen
                        </span>
                    @endif
                </div>
            </div>

            {{-- Jam Pulang Card --}}
            <div
                class="relative overflow-hidden rounded-xl bg-white dark:bg-gray-800 p-5 shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md group">
                <div
                    class="absolute right-0 top-0 -mt-2 -mr-2 h-16 w-16 rounded-full bg-green-50 dark:bg-green-900/20 blur-xl transition-all group-hover:bg-green-100 dark:group-hover:bg-green-900/30">
                </div>

                <div class="relative flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jam Pulang</p>
                        <h3
                            class="mt-1 text-2xl font-bold tracking-tight {{ $attendance?->time_out ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-600' }}">
                            {{ $attendance?->time_out ?? '--:--' }}
                        </h3>
                    </div>
                    <div class="rounded-lg bg-green-50 dark:bg-green-900/30 p-2 text-green-600 dark:text-green-400">
                        <x-heroicon-m-arrow-left-start-on-rectangle class="h-6 w-6" />
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2">
                    @if($attendance?->lembur > 0)
                        <span
                            class="inline-flex items-center gap-1 rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 ring-1 ring-inset ring-purple-600/10 dark:ring-purple-400/20">
                            <x-heroicon-m-clock class="h-3 w-3" />
                            + Lembur @php
                                $jam = floor($attendance->lembur / 60);
                                $menit = $attendance->lembur % 60;
                                echo ($jam > 0 ? $jam . 'j ' : '') . ($menit > 0 ? $menit . 'm' : '');
                            @endphp
                        </span>
                    @elseif($attendance?->time_out)
                        <span
                            class="inline-flex items-center gap-1 rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400 ring-1 ring-inset ring-green-600/20 dark:ring-green-400/20">
                            <x-heroicon-m-check class="h-3 w-3" />
                            Selesai
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1 rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400 ring-1 ring-inset ring-gray-500/10">
                            Menunggu
                        </span>
                    @endif
                </div>
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