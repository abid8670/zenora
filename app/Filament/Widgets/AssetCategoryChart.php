<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use App\Models\AssetCategory;
use Filament\Widgets\ChartWidget;

class AssetCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Assets by Category';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $categories = AssetCategory::withCount('assets')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Assets',
                    'data' => $categories->pluck('assets_count')->all(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgb(54, 162, 235)',
                ],
            ],
            'labels' => $categories->pluck('name')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false, // Hide the legend
                ],
                'datalabels' => [
                    'anchor' => 'end',
                    'align' => 'top',
                    'formatter' => fn ($value) => $value > 0 ? $value : '',
                    'font' => [
                        'weight' => 'bold',
                    ],
                ],
            ],
        ];
    }
}
