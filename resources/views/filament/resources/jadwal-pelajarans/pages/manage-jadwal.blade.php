<x-filament-panels::page>
    <style>
        .jadwal-select {
            width: 100%;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid rgba(156, 163, 175, 0.3);
            background-color: rgba(255, 255, 255, 0.05);
            color: inherit;
            font-size: 14px;
        }

        .jadwal-select option {
            background-color: #1f2937;
            color: #fff;
            padding: 8px;
        }

        .jadwal-select:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }

        .btn-reset {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: none;
            cursor: pointer;
        }

        .btn-reset:hover {
            background-color: rgba(16, 185, 129, 0.2);
        }

        .btn-hapus {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            background-color: #ef4444;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-hapus:hover {
            background-color: #dc2626;
        }

        .day-btn {
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .day-btn-active {
            background-color: #10b981;
            color: white;
        }

        .day-btn-inactive {
            background-color: rgba(156, 163, 175, 0.2);
            color: inherit;
        }

        .day-btn-inactive:hover {
            background-color: rgba(156, 163, 175, 0.3);
        }

        .jam-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: rgba(16, 185, 129, 0.15);
            color: #10b981;
            font-weight: 600;
            font-size: 14px;
        }

        .btn-tambah {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            background-color: #10b981;
            color: white;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-tambah:hover {
            background-color: #059669;
        }

        .jadwal-time-input {
            padding: 6px 8px;
            border-radius: 6px;
            border: 1px solid rgba(156, 163, 175, 0.3);
            background-color: rgba(255, 255, 255, 0.05);
            color: inherit;
            font-size: 13px;
            text-align: center;
        }

        .jadwal-time-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }

        .btn-export {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: white;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-export:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
    </style>

    <div style="display: flex; flex-direction: column; gap: 24px;">
        {{-- Filter Section --}}
        <div
            style="background: var(--fi-body-bg); border-radius: 12px; padding: 20px; border: 1px solid rgba(156, 163, 175, 0.2);">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px;">Tahun
                        Ajaran</label>
                    <select wire:model.live="tahunAjaranId" class="jadwal-select">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($this->getTahunAjaranOptions() as $id => $nama)
                            <option value="{{ $id }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label
                        style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px;">Semester</label>
                    <select wire:model.live="semester" class="jadwal-select">
                        @foreach($this->getSemesterOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label
                        style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px;">Rombel/Kelas</label>
                    <select wire:model.live="rombelId" class="jadwal-select">
                        <option value="">-- Pilih Rombel --</option>
                        @foreach($this->getRombelOptions() as $id => $nama)
                            <option value="{{ $id }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        @if($rombelId)
            {{-- Export Buttons --}}
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" wire:click="exportExcel" class="btn-export" style="background: #059669;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export Excel
                </button>
                <button type="button" wire:click="exportPdf" class="btn-export" style="background: #dc2626;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                        </path>
                    </svg>
                    Export PDF
                </button>
            </div>
        @endif

        @if($rombelId)
            {{-- Guru Mapel Summary Table --}}
            <div
                style="background: var(--fi-body-bg); border-radius: 12px; overflow: hidden; border: 1px solid rgba(156, 163, 175, 0.2);">
                <div style="background: #10b981; color: white; padding: 12px 20px; font-weight: 600; font-size: 14px;">
                    DAFTAR GURU PENGAJAR
                </div>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(156, 163, 175, 0.1);">
                            <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 600;">NO</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 600;">MATA
                                PELAJARAN</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 600;">NAMA GURU
                            </th>
                            <th style="padding: 12px 16px; text-align: center; font-size: 13px; font-weight: 600;">JTM
                                ROMBEL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->getGuruMapelData() as $index => $item)
                            <tr style="border-top: 1px solid rgba(156, 163, 175, 0.2);">
                                <td style="padding: 12px 16px; font-size: 14px;">{{ $index + 1 }}</td>
                                <td style="padding: 12px 16px; font-size: 14px;">{{ $item['mapel'] }}</td>
                                <td style="padding: 12px 16px; font-size: 14px; color: #10b981; font-weight: 500;">
                                    {{ $item['guru'] }}
                                </td>
                                <td style="padding: 12px 16px; font-size: 14px; text-align: center;">{{ $item['jtm'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 24px; text-align: center; color: #9ca3af; font-size: 14px;">
                                    Belum ada jadwal. Silakan tambahkan jadwal di bawah.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Day Tabs --}}
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                @foreach($this->getHariOptions() as $hari)
                    <button type="button" wire:click="selectHari('{{ $hari }}')"
                        class="day-btn {{ $selectedHari === $hari ? 'day-btn-active' : 'day-btn-inactive' }}">
                        {{ strtoupper($hari) }}
                    </button>
                @endforeach
            </div>

            {{-- Jadwal Form per Jam --}}
            <div
                style="background: var(--fi-body-bg); border-radius: 12px; overflow: hidden; border: 1px solid rgba(156, 163, 175, 0.2);">
                <div style="background: #10b981; color: white; padding: 12px 20px; font-weight: 600; font-size: 14px;">
                    JADWAL HARI {{ strtoupper($selectedHari) }}
                </div>

                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(156, 163, 175, 0.1);">
                            <th
                                style="padding: 12px 16px; text-align: center; font-size: 13px; font-weight: 600; width: 80px;">
                                JAM KE</th>
                            <th
                                style="padding: 12px 16px; text-align: center; font-size: 13px; font-weight: 600; width: 140px;">
                                ALOKASI WAKTU</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 600;">MATA
                                PELAJARAN</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 600;">NAMA GURU
                            </th>
                            <th
                                style="padding: 12px 16px; text-align: center; font-size: 13px; font-weight: 600; width: 100px;">
                                AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($jamKe = 1; $jamKe <= $totalJam; $jamKe++)
                            @php
                                // Calculate time for this jam
                                $baseMinutes = 7 * 60; // 07:00
                                $slotDuration = 35;
                                $breakAfter4 = 15;
                                $startMinutes = $baseMinutes + (($jamKe - 1) * $slotDuration);
                                if ($jamKe > 4) {
                                    $startMinutes += $breakAfter4;
                                }
                                $endMinutes = $startMinutes + $slotDuration;
                                $jamMulai = sprintf('%02d:%02d', floor($startMinutes / 60), $startMinutes % 60);
                                $jamSelesai = sprintf('%02d:%02d', floor($endMinutes / 60), $endMinutes % 60);
                            @endphp
                            <tr style="border-top: 1px solid rgba(156, 163, 175, 0.2);">
                                <td style="padding: 12px 16px; text-align: center;">
                                    <span class="jam-badge">{{ $jamKe }}</span>
                                </td>
                                <td style="padding: 12px 10px; text-align: center;">
                                    <div style="display: flex; align-items: center; gap: 4px; justify-content: center;">
                                        <input type="time" wire:model="jadwalData.{{ $jamKe }}.jam_mulai"
                                            wire:change="saveJamKe({{ $jamKe }})" class="jadwal-time-input"
                                            style="width: 85px;">
                                        <span style="color: #9ca3af;">-</span>
                                        <input type="time" wire:model="jadwalData.{{ $jamKe }}.jam_selesai"
                                            wire:change="saveJamKe({{ $jamKe }})" class="jadwal-time-input"
                                            style="width: 85px;">
                                    </div>
                                </td>
                                <td style="padding: 12px 16px;">
                                    <select wire:model="jadwalData.{{ $jamKe }}.mata_pelajaran_id"
                                        wire:change="saveJamKe({{ $jamKe }})" class="jadwal-select">
                                        <option value="">-- Pilih Mapel --</option>
                                        @foreach($this->getMataPelajaranOptions() as $id => $nama)
                                            <option value="{{ $id }}">{{ $nama }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="padding: 12px 16px;">
                                    <select wire:model="jadwalData.{{ $jamKe }}.teacher_id"
                                        wire:change="saveJamKe({{ $jamKe }})" class="jadwal-select">
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach($this->getTeacherOptions() as $id => $nama)
                                            <option value="{{ $id }}">{{ $nama }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="padding: 12px 16px; text-align: center;">
                                    <button type="button" wire:click="resetJamKe({{ $jamKe }})" class="btn-reset">
                                        Reset
                                    </button>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>

                {{-- Add/Remove Jam Buttons --}}
                <div style="padding: 16px 20px; border-top: 1px solid rgba(156, 163, 175, 0.2); display: flex; gap: 12px;">
                    <button type="button" wire:click="tambahJam" class="btn-tambah">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Jam
                    </button>
                    @if($totalJam > 1)
                        <button type="button" wire:click="kurangiJam" wire:confirm="Yakin ingin menghapus jam terakhir?"
                            class="btn-hapus">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                            Kurangi Jam
                        </button>
                    @endif
                </div>
            </div>
        @else
            {{-- Empty State --}}
            <div
                style="background: var(--fi-body-bg); border-radius: 12px; padding: 48px 24px; text-align: center; border: 1px solid rgba(156, 163, 175, 0.2);">
                <svg style="width: 48px; height: 48px; margin: 0 auto 16px; color: #9ca3af;" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">Pilih Rombel</h3>
                <p style="font-size: 14px; color: #9ca3af;">Silakan pilih Tahun Ajaran, Semester, dan Rombel untuk mengelola
                    jadwal.</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>