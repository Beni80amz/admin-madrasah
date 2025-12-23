<x-filament-panels::page>
    <style>
        .kenaikan-container {
            display: flex;
            flex-direction: row;
            gap: 1.5rem;
        }

        .kenaikan-panel {
            flex: 1;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .kenaikan-select {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            background: #1f2937;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 0.875rem;
        }

        .kenaikan-select option {
            background: #1f2937;
            color: white;
        }

        .kenaikan-input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 0.875rem;
        }

        .kenaikan-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .kenaikan-table th {
            text-align: left;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            color: #9ca3af;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .kenaikan-table td {
            padding: 0.5rem 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: white;
        }

        .kenaikan-table tr:hover {
            background: rgba(16, 185, 129, 0.1);
            cursor: pointer;
        }

        .kenaikan-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .kenaikan-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #9ca3af;
            margin-bottom: 0.25rem;
        }

        .kenaikan-info {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .kenaikan-info h4 {
            color: #f87171;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .kenaikan-info ul {
            color: #fca5a5;
            font-size: 0.875rem;
            list-style: disc;
            padding-left: 1.25rem;
        }

        @media (max-width: 1024px) {
            .kenaikan-container {
                flex-direction: column;
            }
        }
    </style>

    <div class="kenaikan-container">
        <!-- Panel Kiri: Siswa Asal -->
        <div class="kenaikan-panel">
            <h2 style="font-size: 1.125rem; font-weight: 600; color: #10b981; margin-bottom: 1rem;">
                📚 Tahun Ajaran Asal
            </h2>

            <div class="kenaikan-grid">
                <div>
                    <label class="kenaikan-label">Tahun Ajaran</label>
                    <select wire:model.live="tahunAjaranAsal" class="kenaikan-select">
                        <option value="">-- Pilih --</option>
                        @foreach($this->getTahunAjaranOptions() as $id => $nama)
                            <option value="{{ $id }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="kenaikan-label">Tingkat</label>
                    <select wire:model.live="tingkatAsal" class="kenaikan-select">
                        <option value="">-- Pilih --</option>
                        @foreach($this->getTingkatOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="kenaikan-grid">
                <div>
                    <label class="kenaikan-label">Kelas</label>
                    <select wire:model.live="kelasAsal" class="kenaikan-select">
                        <option value="">-- Pilih --</option>
                        @foreach($this->getKelasAsalOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="kenaikan-label">Cari</label>
                    <input type="text" wire:model.live.debounce.300ms="searchAsal" placeholder="Cari NISN/Nama..."
                        class="kenaikan-input">
                </div>
            </div>

            <div style="overflow-x: auto; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                <table class="kenaikan-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>L/P</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->getStudentsAsal() as $index => $student)
                            <tr wire:click="selectStudent({{ $student->id }})">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->nisn }}</td>
                                <td>{{ $student->nama_lengkap }}</td>
                                <td>{{ $student->gender === 'Laki-laki' ? 'L' : 'P' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 2rem; color: #6b7280;">
                                    {{ $kelasAsal ? 'Tidak ada siswa' : 'Pilih kelas untuk menampilkan siswa' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <p style="font-size: 0.75rem; color: #6b7280; margin-top: 0.5rem;">Menampilkan
                {{ $this->getStudentsAsal()->count() }} siswa</p>

            <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                <x-filament::button wire:click="selectAll" color="primary" size="sm">
                    Select All
                </x-filament::button>
                <x-filament::button wire:click="clearSelection" color="danger" size="sm">
                    Clear
                </x-filament::button>
            </div>
        </div>

        <!-- Panel Kanan: Siswa Tujuan -->
        <div class="kenaikan-panel">
            <h2 style="font-size: 1.125rem; font-weight: 600; color: #3b82f6; margin-bottom: 1rem;">
                🎯 Tahun Ajaran Tujuan
            </h2>

            <div class="kenaikan-grid">
                <div>
                    <label class="kenaikan-label">Tahun Ajaran</label>
                    <select wire:model.live="tahunAjaranTujuan" class="kenaikan-select">
                        <option value="">-- Pilih --</option>
                        @foreach($this->getTahunAjaranOptions() as $id => $nama)
                            <option value="{{ $id }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="kenaikan-label">Tingkat</label>
                    <select wire:model.live="tingkatTujuan" class="kenaikan-select">
                        <option value="">-- Pilih --</option>
                        @foreach($this->getTingkatOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label class="kenaikan-label">Kelas Tujuan</label>
                <select wire:model.live="kelasTujuan" class="kenaikan-select">
                    <option value="">-- Pilih --</option>
                    @foreach($this->getKelasTujuanOptions() as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div style="overflow-x: auto; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                <table class="kenaikan-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>L/P</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->getStudentsTujuan() as $index => $student)
                            <tr wire:click="deselectStudent({{ $student->id }})"
                                style="background: rgba(239, 68, 68, 0.1);">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->nisn }}</td>
                                <td>{{ $student->nama_lengkap }}</td>
                                <td>{{ $student->gender === 'Laki-laki' ? 'L' : 'P' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 2rem; color: #6b7280;">
                                    Belum ada siswa dipilih
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <p style="font-size: 0.75rem; color: #6b7280; margin-top: 0.5rem;">{{ count($selectedStudents) }} siswa
                dipilih</p>

            <div class="kenaikan-info">
                <h4>⚠️ Perhatikan:</h4>
                <ul>
                    <li>Klik pada siswa di panel kiri untuk memilih</li>
                    <li>Klik pada siswa di panel kanan untuk membatalkan</li>
                    <li>Pilih kelas tujuan sebelum proses</li>
                </ul>
            </div>

            <div style="margin-top: 1rem;">
                <x-filament::button wire:click="processKenaikanKelas" color="success" style="width: 100%;" size="lg">
                    🚀 Proses Naik Kelas
                </x-filament::button>
            </div>
        </div>
    </div>
</x-filament-panels::page>