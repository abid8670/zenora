<?php

namespace App\Filament\Widgets;

use App\Models\Domain;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;
use Filament\Tables\Actions\Action;

class ExpiringDomains extends BaseWidget
{
    protected static ?string $heading = 'Domains Expiring Soon';

    protected static ?string $navigationGroup = 'Domain Management';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Domain::query()
                    ->where('expiry_date', '<=', now()->addDays(7))
                    ->where('expiry_date', '>=', now())
                    ->orderBy('expiry_date', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Domain Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('Expiry Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('days_to_expire')
                    ->label('Days to Expire')
                    ->getStateUsing(function ($record) {
                        return Carbon::parse($record->expiry_date)->diffInDays(now());
                    }),
            ])
            ->actions([
                Action::make('renew')
                    ->label('Renew')
                    ->form([
                        Forms\Components\DatePicker::make('expiry_date')
                            ->label('New Expiry Date')
                            ->default(fn (Domain $record) => Carbon::parse($record->expiry_date)->addYear())
                            ->required(),
                    ])
                    ->action(function (Domain $record, array $data) {
                        $record->update([
                            'expiry_date' => $data['expiry_date'],
                        ]);
                    })
                    ->modalHeading('Renew Domain'),
            ])
            ->paginated([5]);
    }
}
