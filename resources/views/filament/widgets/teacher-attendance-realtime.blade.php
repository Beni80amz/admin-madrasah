<x-filament-widgets::widget>
    @php
        /**
         * @var \App\Models\Attendance|null $attendance
         */
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Jam Masuk --}}
        <div
            class="relative overflow-hidden rounded-2xl bg-gray-900 border border-gray-800 shadow-xl p-6 group transition-all hover:border-blue-500/30 hover:shadow-blue-500/10 hover:-translate-y-1">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500/10 border border-blue-500/20 group-hover:bg-blue-500/20 transition-colors">
                        <x-heroicon-m-arrow-right-end-on-rectangle class="h-6 w-6 text-blue-400"
                            style="width: 24px; height: 24px;" />
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-0.5">Jam Masuk</p>
                        <h3
                            class="text-2xl font-bold text-white tracking-tight {{ $attendance?->time_in ? 'text-blue-50' : 'text-gray-500' }}">
                            {{ $attendance?->time_in ?? '--:--' }}
                        </h3>
                    </div>
                </div>

                @if($attendance?->keterlambatan > 0)
                    <div class="flex flex-col items-end">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-red-400/10 px-2.5 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/20">
                            <x-heroicon-m-exclamation-circle class="h-3.5 w-3.5" style="width: 14px; height: 14px;" />
                            Telat {{ $attendance->keterlambatan }}m
                        </span>
                    </div>
                @elseif($attendance?->time_in)
                    <div class="flex flex-col items-end">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-emerald-400/10 px-2.5 py-1 text-xs font-medium text-emerald-400 ring-1 ring-inset ring-emerald-400/20">
                            <x-heroicon-m-check-circle class="h-3.5 w-3.5" style="width: 14px; height: 14px;" />
                            Tepat Waktu
                        </span>
                    </div>
                @endif
            </div>

            {{-- Progress Bar Aesthetic --}}
            <div class="mt-4 h-1 w-full bg-gray-800 rounded-full overflow-hidden">
                <div class="h-full {{ $attendance?->time_in ? ($attendance?->keterlambatan > 0 ? 'bg-red-500' : 'bg-blue-500') : 'bg-transparent' }} rounded-full"
                    style="width: {{ $attendance?->time_in ? '100%' : '0%' }}"></div>
            </div>
        </div>

        {{-- Jam Pulang --}}
        <div
            class="relative overflow-hidden rounded-2xl bg-gray-900 border border-gray-800 shadow-xl p-6 group transition-all hover:border-emerald-500/30 hover:shadow-emerald-500/10 hover:-translate-y-1">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500/10 border border-emerald-500/20 group-hover:bg-emerald-500/20 transition-colors">
                        <x-heroicon-m-arrow-left-start-on-rectangle class="h-6 w-6 text-emerald-400"
                            style="width: 24px; height: 24px;" />
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-0.5">Jam Pulang</p>
                        <h3
                            class="text-2xl font-bold text-white tracking-tight {{ $attendance?->time_out ? 'text-emerald-50' : 'text-gray-500' }}">
                            {{ $attendance?->time_out ?? '--:--' }}
                        </h3>
                    </div>
                </div>

                @if($attendance?->lembur > 0)
                    <div class="flex flex-col items-end">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-purple-400/10 px-2.5 py-1 text-xs font-medium text-purple-400 ring-1 ring-inset ring-purple-400/20">
                            <x-heroicon-m-clock class="h-3.5 w-3.5" style="width: 14px; height: 14px;" />
                            +Lembur
                            @php
                                $jam = floor($attendance->lembur / 60);
                                $menit = $attendance->lembur % 60;
                                echo ($jam > 0 ? $jam . 'j ' : '') . ($menit > 0 ? $menit . 'm' : '');
                             @endphp
                        </span>
                    </div>
                @elseif($attendance?->time_out)
                    <div class="flex flex-col items-end">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-emerald-400/10 px-2.5 py-1 text-xs font-medium text-emerald-400 ring-1 ring-inset ring-emerald-400/20">
                            <x-heroicon-m-check class="h-3.5 w-3.5" style="width: 14px; height: 14px;" />
                            Selesai
                        </span>
                    </div>
                @endif
            </div>

            {{-- Progress Bar Aesthetic --}}
            <div class="mt-4 h-1 w-full bg-gray-800 rounded-full overflow-hidden">
                <div class="h-full {{ $attendance?->time_out ? ($attendance?->lembur > 0 ? 'bg-purple-500' : 'bg-emerald-500') : 'bg-transparent' }} rounded-full"
                    style="width: {{ $attendance?->time_out ? '100%' : '0%' }}"></div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>