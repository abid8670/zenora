<?php

namespace App\Filament\Resources\AssetResource\Widgets;

use App\Models\Asset;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AssetsByCategory extends ChartWidget
{
    protected static ?string $heading = 'Assets by Category';

    protected function getData(): array
    {
        $data = Asset::query()
            ->join('asset_categories', 'assets.asset_category_id', '=', 'asset_categories.id')
            ->select('asset_categories.name', \DB::raw('count(*) as count'))
            ->groupBy('asset_categories.name')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Assets',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
