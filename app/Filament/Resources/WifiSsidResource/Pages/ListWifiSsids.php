<?php

namespace App\Filament\Resources\WifiSsidResource\Pages;

use App\Filament\Resources\WifiSsidResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWifiSsids extends ListRecords
{
    protected static string $resource = WifiSsidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
