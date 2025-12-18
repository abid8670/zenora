<?php

namespace App\Filament\Resources\AssetResource\Widgets;

use App\Models\AssetAssignmentLog;
use Filament\Widgets\ChartWidget;

class AssetsByDepartment extends ChartWidget
{
    protected static ?string $heading = 'Assets by Department';

    protected function getData(): array
    {
        $data = AssetAssignmentLog::with('employee.department')
            ->get()
            ->groupBy('employee.department.name')
            ->map(fn ($row) => $row->count());

        return [
            'datasets' => [
                [
                    'label' => 'Assets',
                    'data' => $data->values(),
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                    ],
                ],
            ],
            'labels' => $data->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
