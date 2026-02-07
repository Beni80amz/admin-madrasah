<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class TeacherWeeklyAttendance extends BaseWidget
{
    public static function getSort(): int
    {
        return 3;
    }

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Riwayat Kehadiran (7 Hari Terakhir)';

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Guru');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Attendance::query()
                    ->where('user_id', Auth::id())
                    ->where('date', '>=', now()->subDays(7))
                    ->orderBy('date', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('day_name')
                    ->label('Hari')
                    ->state(fn(Attendance $record) => Carbon::parse($record->date)->locale('id')->translatedFormat('l')),
                Tables\Columns\TextColumn::make('time_in')
                    ->label('Masuk')
                    ->time('H:i')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('time_out')
                    ->label('Pulang')
                    ->time('H:i')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'hadir' => 'success',
                        'telat' => 'warning',
                        'izin' => 'info',
                        'sakit' => 'purple',
                        'alpha' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('keterlambatan')
                    ->label('Telat')
                    ->state(fn($record) => $record->keterlambatan > 0 ? $record->keterlambatan . 'm' : '-'),
                Tables\Columns\TextColumn::make('lembur')
                    ->label('Lembur')
                    ->state(function ($record) {
                        if ($record->lembur <= 0)
                            return '-';
                        $jam = floor($record->lembur / 60);
                        $menit = $record->lembur % 60;
                        return ($jam > 0 ? $jam . 'j ' : '') . ($menit > 0 ? $menit . 'm' : '');
                    }),
            ])
            ->paginated(false);
    }
}
