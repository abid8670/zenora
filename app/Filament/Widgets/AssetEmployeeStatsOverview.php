<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Employee;
use App\Models\Office;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AssetEmployeeStatsOverview extends BaseWidget implements HasForms
{
    use InteractsWithForms;

    protected static ?string $pollingInterval = null;

    protected static ?string $navigationGroup = 'Assets & Employees';

    public ?array $filters = [];

    protected function getFilters(): array
    {
        return [
            Select::make('office_id')
                ->label('Filter by Office')
                ->options(Office::pluck('name', 'id'))
                ->placeholder('All Offices'),
            Select::make('asset_category_id')
                ->label('Filter by Asset Category')
                ->options(AssetCategory::pluck('name', 'id'))
                ->placeholder('All Categories'),
        ];
    }
    
    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $selectedOfficeId = $this->filters['office_id'] ?? null;
        $selectedAssetCategoryId = $this->filters['asset_category_id'] ?? null;

        $assetQuery = Asset::query();
        $employeeQuery = Employee::query();

        if ($selectedOfficeId) {
            $assetQuery->where('office_id', $selectedOfficeId);
            $employeeQuery->where('office_id', $selectedOfficeId);
        }

        if ($selectedAssetCategoryId) {
            $assetQuery->where('asset_category_id', $selectedAssetCategoryId);
        }

        $inStockQuery = clone $assetQuery;
        $assignedQuery = clone $assetQuery;

        return [
            Stat::make('Total Assets', $assetQuery->count())
                ->description('All assets in inventory')
                ->descriptionIcon('heroicon-o-archive-box')
                ->chart([15, 10, 20, 30, 25, 40, $assetQuery->count()])
                ->color('primary'),

            Stat::make('Total Employees', $employeeQuery->count())
                ->description('Employees in the office')
                ->descriptionIcon('heroicon-o-user-group')
                ->chart([5, 4, 6, 8, 7, 9, $employeeQuery->count()])
                ->color('info'),

            Stat::make('In Stock Assets', $inStockQuery->where('status', 'In Stock')->count())
                ->description('Available assets')
                ->descriptionIcon('heroicon-o-check-circle')
                ->chart([20, 12, 15, 25, 18, 22, $inStockQuery->where('status', 'In Stock')->count()])
                ->color('success'),

            Stat::make('Assigned Assets', $assignedQuery->where('status', 'Assigned')->count())
                ->description('Assets currently in use')
                ->descriptionIcon('heroicon-o-user-plus')
                ->chart([10, 12, 8, 14, 16, 15, $assignedQuery->where('status', 'Assigned')->count()])
                ->color('warning'),
        ];
    }

    protected function getFilterForm(): \Filament\Forms\Form
    {
        return \Filament\Forms\Form::make($this)
            ->schema($this->getFilters())
            ->statePath('filters')
            ->live();
    }
}
