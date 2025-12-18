<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use App\Filament\Resources\AssetResource\Widgets\AssetAssignments;
use App\Filament\Resources\AssetResource\Widgets\AssetsByCategory;
use App\Filament\Resources\AssetResource\Widgets\AssetsByDepartment;
use Filament\Resources\Pages\Page;

class AssetDashboard extends Page
{
    protected static string $resource = AssetResource::class;

    protected static string $view = 'filament.resources.asset-resource.pages.asset-dashboard';

    protected static ?string $title = 'Asset Dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            AssetsByCategory::class,
            AssetsByDepartment::class,
            AssetAssignments::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return [
            'md' => 2,
            'lg' => 2,
            'xl' => 2,
            '2xl' => 2,
        ];
    }
}
