<?php

namespace App\Filament\Resources\SupportTypeResource\Pages;

use App\Filament\Resources\SupportTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupportType extends EditRecord
{
    protected static string $resource = SupportTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
