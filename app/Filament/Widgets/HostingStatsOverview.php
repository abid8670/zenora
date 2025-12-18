<?php

namespace App\Filament\Widgets;

use App\Models\Hosting;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HostingStatsOverview extends BaseWidget
{
    protected static ?string $navigationGroup = 'Hosting Management';

    protected function getStats(): array
    {
        $expiringSoonCount = Hosting::where('expiry_date', '>=', Carbon::now())
            ->where('expiry_date', '<=', Carbon::now()->addDays(30))
            ->count();

        return [
            Stat::make('Total Hostings', Hosting::count())
                ->description('All registered hosting accounts')
                ->descriptionIcon('heroicon-o-server')
                ->color('primary'),
            Stat::make('Active Hostings', Hosting::where('status', 'Active')->count())
                ->description('Actively used hosting accounts')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Expiring Soon', $expiringSoonCount)
                ->description('Hostings expiring in the next 30 days')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Expired Hostings', Hosting::where('expiry_date', '<', Carbon::now())->count())
                ->description('Hostings that have expired')
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->color('danger'),
        ];
    }
}
