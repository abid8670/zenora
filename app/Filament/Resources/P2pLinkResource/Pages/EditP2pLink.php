<?php

namespace App\Filament\Resources\P2pLinkResource\Pages;

use App\Filament\Resources\P2pLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditP2pLink extends EditRecord
{
    protected static string $resource = P2pLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
