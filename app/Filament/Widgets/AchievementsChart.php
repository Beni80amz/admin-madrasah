<?php

namespace App\Filament\Widgets;

use App\Models\Achievement;
use Filament\Widgets\ChartWidget;

class AchievementsChart extends ChartWidget
{
    protected static ?int $sort = 4;

    public function getHeading(): string
    {
        return 'Prestasi per Tahun';
    }

    public function getMaxHeight(): ?string
    {
        return '300px';
    }

    protected function getData(): array
    {
        $achievementsByYear = Achievement::selectRaw('tahun, COUNT(*) as total')
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->limit(5)
            ->pluck('total', 'tahun')
            ->reverse()
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Prestasi',
                    'data' => array_values($achievementsByYear),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                    'fill' => true,
                ],
            ],
            'labels' => array_keys($achievementsByYear),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
