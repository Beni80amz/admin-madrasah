<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;

class MyAttendance extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Absensi Saya';

    protected static ?string $navigationGroup = 'Administrasi Guru';

    protected static ?string $title = 'Riwayat Absensi';

    protected static string $view = 'filament.pages.my-attendance';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Attendance::query()
                    ->where('user_id', Auth::id())
                    ->orderBy('date', 'desc')
            )
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable(),

                TextColumn::make('date_day')
                    ->label('Hari')
                    ->state(fn(Attendance $record) => Carbon::parse($record->date)->translatedFormat('l')),

                TextColumn::make('time_in')
                    ->label('Masuk')
                    ->time('H:i:s')
                    ->placeholder('-'),

                TextColumn::make('time_out')
                    ->label('Pulang')
                    ->time('H:i:s')
                    ->placeholder('-'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Telat' => 'warning',
                        'Izin' => 'info',
                        'Sakit' => 'danger',
                        'Alpha' => 'gray',
                        default => 'secondary',
                    }),

                TextColumn::make('keterlambatan')
                    ->label('Telat')
                    ->state(fn($record) => $record->keterlambatan > 0 ? $record->keterlambatan . 'm' : '0m'),

                TextColumn::make('lembur')
                    ->label('Lembur')
                    ->state(fn($record) => $record->lembur > 0 ? $record->lembur . 'm' : '0m'),
            ])
            ->filters([
                Filter::make('date')
                    ->form([
                        Select::make('month')
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
                            ->default(now()->month),
                        Select::make('year')
                            ->label('Tahun')
                            ->options(function () {
                                $years = range(now()->year, 2020);
                                return array_combine($years, $years);
                            })
                            ->default(now()->year),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['month'],
                                fn(Builder $query, $month) => $query->whereMonth('date', $month)
                            )
                            ->when(
                                $data['year'],
                                fn(Builder $query, $year) => $query->whereYear('date', $year)
                            );
                    }),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadPdf')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    Select::make('month')
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
                    Select::make('year')
                        ->label('Tahun')
                        ->options(function () {
                            $years = range(now()->year, 2020);
                            return array_combine($years, $years);
                        })
                        ->default(now()->year)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $month = $data['month'];
                    $year = $data['year'];
                    $user = Auth::user();

                    $records = Attendance::query()
                        ->where('user_id', $user->id)
                        ->whereMonth('date', $month)
                        ->whereYear('date', $year)
                        ->orderBy('date', 'asc')
                        ->get();

                    $summary = [
                        'Hadir' => $records->where('status', 'Hadir')->count(),
                        'Telat' => $records->where('status', 'Telat')->count(),
                        'Izin' => $records->where('status', 'Izin')->count(),
                        'Sakit' => $records->where('status', 'Sakit')->count(),
                        'Alpha' => $records->where('status', 'Alpha')->count(),
                    ];

                    $profileMadrasah = \App\Models\ProfileMadrasah::first();
                    $teacher = $user->teacher; // Assuming relationship exists
        
                    $pdf = Pdf::loadView('pdf.attendance-report', [
                        'records' => $records,
                        'summary' => $summary,
                        'month' => $month,
                        'year' => $year,
                        'user' => $user,
                        'profile' => $profileMadrasah,
                        'teacher' => $teacher,
                    ])->setPaper('a4', 'portrait');

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        "Laporan_Absensi_{$user->name}_{$month}_{$year}.pdf"
                    );
                }),
        ];
    }
}
