<?php

namespace App\Filament\Resources\VoipExtensionResource\Pages;

use App\Filament\Resources\VoipExtensionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVoipExtension extends EditRecord
{
    protected static string $resource = VoipExtensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
