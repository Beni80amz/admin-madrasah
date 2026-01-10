<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->headerActions([
                \Filament\Actions\Action::make('export')
                    ->label('Export Laporan')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->form([
                        \Filament\Forms\Components\Select::make('month')
                            ->label('Bulan')
                            ->options([
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ])
                            ->default(now()->month)
                            ->required(),
                        \Filament\Forms\Components\Select::make('year')
                            ->label('Tahun')
                            ->options(function () {
                                $years = range(now()->year, 2024);
                                return array_combine($years, $years);
                            })
                            ->default(now()->year)
                            ->required(),
                        \Filament\Forms\Components\Select::make('user_id')
                            ->label('Pegawai (Opsional)')
                            ->options(\App\Models\Teacher::pluck('nama_lengkap', 'user_id'))
                            ->searchable()
                            ->placeholder('Semua Pegawai'),
                        \Filament\Forms\Components\Radio::make('format')
                            ->options([
                                'pdf' => 'PDF (Laporan Resmi + QR)',
                                'excel' => 'Excel (Data Mentah)',
                            ])
                            ->default('pdf')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $month = $data['month'];
                        $year = $data['year'];
                        $userId = $data['user_id'] ?? null;

                        $query = \App\Models\Attendance::query()
                            ->whereMonth('date', $month)
                            ->whereYear('date', $year);

                        if ($userId) {
                            $query->where('user_id', $userId);
                        }

                        $attendances = $query->with('user')->orderBy('date')->get();

                        if ($data['format'] === 'excel') {
                            return \Maatwebsite\Excel\Facades\Excel::download(
                                new \App\Exports\AttendanceExport($attendances),
                                'Laporan-Absensi-' . $month . '-' . $year . '.xlsx'
                            );
                        } else {
                            // PDF
                            $user = $userId ? \App\Models\User::find($userId) : null;
                            $profile = \App\Models\ProfileMadrasah::first();

                            $teacherName = $user ? ($user->name) : 'Semua Pegawai';
                            if ($userId) {
                                $teacher = \App\Models\Teacher::where('user_id', $userId)->first();
                                if ($teacher) {
                                    $teacherName = $teacher->nama_lengkap;
                                }
                            }

                            $summary = [
                                'hadir' => $attendances->where('status', 'hadir')->count(),
                                'telat' => $attendances->where('status', 'telat')->count(),
                                'izin' => $attendances->where('status', 'izin')->count(),
                                'sakit' => $attendances->where('status', 'sakit')->count(),
                                'alpha' => $attendances->where('status', 'alpha')->count(),
                            ];

                            $qrData = route('attendance.verify', [
                                'period' => "$month-$year",
                                'user' => $user ? $user->id : 'all',
                                'timestamp' => now()->timestamp
                            ]);

                            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.attendance', [
                                'attendances' => $attendances,
                                'user' => $user,
                                'teacherName' => $teacherName,
                                'profile' => $profile,
                                'period' => \Carbon\Carbon::create($year, $month, 1)->locale('id')->isoFormat('MMMM Y'),
                                'summary' => $summary,
                                'qrData' => $qrData,
                            ]);

                            return response()->streamDownload(
                                fn() => print ($pdf->output()),
                                'Laporan-Absensi-' . $month . '-' . $year . '.pdf'
                            );
                        }
                    }),
            ])
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->locale('id')->isoFormat('dddd, D MMMM Y'))
                    ->sortable(),
                TextColumn::make('time_in')
                    ->label('Masuk')
                    ->time(),
                TextColumn::make('time_out')
                    ->label('Pulang')
                    ->time(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'hadir' => 'success',
                        'telat' => 'warning',
                        'izin' => 'info',
                        'sakit' => 'info',
                        'alpha' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('keterlambatan')
                    ->label('Telat')
                    ->numeric()
                    ->suffix(' menit')
                    ->sortable(),
                TextColumn::make('lembur')
                    ->numeric()
                    ->suffix(' menit')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'telat' => 'Telat',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpha' => 'Alpha',
                    ]),
                Filter::make('date')
                    ->form([
                        DatePicker::make('from')->label('Dari Tanggal'),
                        DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }
}
