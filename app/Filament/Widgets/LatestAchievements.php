<?php

namespace App\Filament\Widgets;

use App\Models\Achievement;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAchievements extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 'full';

    public function getHeading(): string
    {
        return 'Prestasi Terbaru';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Achievement::query()
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular()
                    ->state(function (Achievement $record) {
                        // If record has photo, use it
                        if ($record->photo) {
                            return $record->photo;
                        }

                        // Try to find photo from related student/teacher
                        if ($record->type === 'siswa') {
                            $student = \App\Models\Student::where('nama_lengkap', $record->nama)->first();
                            return $student?->photo;
                        }

                        if ($record->type === 'guru') {
                            $teacher = \App\Models\Teacher::where('nama_lengkap', $record->nama)->first();
                            return $teacher?->photo;
                        }

                        return null;
                    }),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'siswa' => 'success',
                        'guru' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('prestasi')
                    ->label('Prestasi'),
                Tables\Columns\TextColumn::make('tingkat')
                    ->label('Tingkat')
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('tahun')
                    ->label('Tahun'),
            ])
            ->paginated(false);
    }
}
