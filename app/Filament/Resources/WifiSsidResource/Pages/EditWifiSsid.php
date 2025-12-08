<?php

namespace App\Filament\Resources\WifiSsidResource\Pages;

use App\Filament\Resources\WifiSsidResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWifiSsid extends EditRecord
{
    protected static string $resource = WifiSsidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
