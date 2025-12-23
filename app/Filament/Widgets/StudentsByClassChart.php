<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;

class StudentsByClassChart extends ChartWidget
{
    protected static ?int $sort = 2;

    public function getHeading(): string
    {
        return 'Distribusi Siswa per Kelas';
    }

    public function getMaxHeight(): ?string
    {
        return '300px';
    }

    protected function getData(): array
    {
        $studentsByClass = Student::where('is_active', true)
            ->selectRaw('kelas, COUNT(*) as total')
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->pluck('total', 'kelas')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Siswa',
                    'data' => array_values($studentsByClass),
                    'backgroundColor' => [
                        '#10b981',
                        '#3b82f6',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                        '#ec4899',
                        '#06b6d4',
                        '#84cc16',
                        '#f97316',
                        '#6366f1',
                        '#14b8a6',
                        '#a855f7',
                    ],
                ],
            ],
            'labels' => array_keys($studentsByClass),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
