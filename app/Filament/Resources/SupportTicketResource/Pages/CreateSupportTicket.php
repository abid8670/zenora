<?php

namespace App\Filament\Resources\SupportTicketResource\Pages;

use App\Filament\Resources\SupportTicketResource;
use App\Models\User;
use App\Notifications\NewSupportTicketNotification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Notification;

class CreateSupportTicket extends CreateRecord
{
    protected static string $resource = SupportTicketResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['local_ip'] = request()->ip();

        return $data;
    }

    protected function afterCreate(): void
    {
        $ticket = $this->record;
        $admins = User::all(); // Assuming all users are admins. Adjust if you have a specific role.

        Notification::send($admins, new NewSupportTicketNotification($ticket));
    }
}
