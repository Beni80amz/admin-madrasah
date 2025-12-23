<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;

class StudentsByGenderChart extends ChartWidget
{
    protected static ?int $sort = 3;

    public function getHeading(): string
    {
        return 'Distribusi Siswa per Gender';
    }

    public function getMaxHeight(): ?string
    {
        return '300px';
    }

    protected function getData(): array
    {
        $lakiLaki = Student::where('is_active', true)->where('gender', 'Laki-laki')->count();
        $perempuan = Student::where('is_active', true)->where('gender', 'Perempuan')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah',
                    'data' => [$lakiLaki, $perempuan],
                    'backgroundColor' => ['#3b82f6', '#ec4899'],
                ],
            ],
            'labels' => ['Laki-laki', 'Perempuan'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
