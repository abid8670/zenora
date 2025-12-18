<?php

namespace App\Filament\Widgets;

use App\Models\Domain;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DomainStatsOverview extends BaseWidget
{
    protected static ?string $navigationGroup = 'Domain Management';

    protected function getStats(): array
    {
        $expiringSoonCount = Domain::where('expiry_date', '>=', Carbon::now())
            ->where('expiry_date', '<=', Carbon::now()->addDays(30))
            ->count();

        return [
            Stat::make('Total Domains', Domain::count())
                ->description('All registered domains')
                ->descriptionIcon('heroicon-o-globe-alt')
                ->color('primary'),
            Stat::make('Active Domains', Domain::where('status', 'Active')->count())
                ->description('Actively hosted domains')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Expiring Soon', $expiringSoonCount)
                ->description('Domains expiring in the next 30 days')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Expired Domains', Domain::where('expiry_date', '<', Carbon::now())->count())
                ->description('Domains that have expired')
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->color('danger'),
        ];
    }
}
