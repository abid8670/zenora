<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportTicketResource\Pages;
use App\Models\SupportTicket;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;

    protected static ?string $navigationIcon = 'heroicon-o-lifebuoy';

    protected static ?string $navigationGroup = 'Helpdesk';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'New')->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ticket Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->disabled(fn (string $operation): bool => $operation === 'edit'),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->disabled(fn (string $operation): bool => $operation === 'edit'),
                        Forms\Components\Select::make('office_id')
                            ->relationship('office', 'name')
                            ->searchable()
                            ->required()
                            ->disabled(fn (string $operation): bool => $operation === 'edit'),
                        Forms\Components\Select::make('support_type_id')
                            ->relationship('supportType', 'name')
                            ->searchable()
                            ->required()
                            ->disabled(fn (string $operation): bool => $operation === 'edit'),
                        Forms\Components\TextInput::make('title')
                            ->label('Subject')
                            ->required()
                            ->columnSpanFull()
                            ->disabled(fn (string $operation): bool => $operation === 'edit'),
                        Forms\Components\Textarea::make('description')
                            ->label('Problem Description')
                            ->required()
                            ->columnSpanFull()
                            ->disabled(fn (string $operation): bool => $operation === 'edit'),
                    ])->columns(2),

                Forms\Components\Section::make('Support Response')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'New' => 'New',
                                'Pending' => 'Pending',
                                'Resolved' => 'Resolved',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('remarks')
                            ->label('Support Team Remarks')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (string $operation): bool => $operation === 'edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('User')
                    ->searchable(),
                Tables\Columns\TextColumn::make('local_ip')
                    ->label('IP Address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('title')
                    ->label('Subject')
                    ->searchable(),
                Tables\Columns\TextColumn::make('office.name')
                    ->label('Office')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'New' => 'info',
                        'Pending' => 'warning',
                        'Resolved' => 'success',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Reported On')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('resolution_time')
                    ->label('Resolution Time')
                    ->state(function (SupportTicket $record): ?string {
                        if ($record->status !== 'Resolved') {
                            return null;
                        }
                        return Carbon::parse($record->created_at)->diffForHumans($record->updated_at, true);
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'New' => 'New',
                        'Pending' => 'Pending',
                        'Resolved' => 'Resolved',
                    ]),
                Tables\Filters\SelectFilter::make('office')
                    ->relationship('office', 'name'),
                Tables\Filters\SelectFilter::make('supportType')
                    ->relationship('supportType', 'name')
                    ->label('Category'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('Mark as Pending')
                    ->action(function (SupportTicket $record, array $data): void {
                        $record->remarks = $data['remarks'];
                        $record->status = 'Pending';
                        $record->save();
                    })
                    ->form([
                        Forms\Components\Textarea::make('remarks')
                            ->label('Pending Reason')
                            ->required(),
                    ])
                    ->requiresConfirmation()
                    ->color('warning')
                    ->icon('heroicon-o-clock')
                    ->visible(fn (SupportTicket $record): bool => !in_array($record->status, ['Pending', 'Resolved'])),
                Action::make('Resolve')
                    ->action(function (SupportTicket $record, array $data): void {
                        $record->remarks = $data['remarks'];
                        $record->status = 'Resolved';
                        $record->save();
                    })
                    ->form([
                        Forms\Components\Textarea::make('remarks')
                            ->label('Support Team Remarks')
                            ->required(),
                    ])
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (SupportTicket $record): bool => $record->status !== 'Resolved'),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportTickets::route('/'),
            'create' => Pages\CreateSupportTicket::route('/create'),
            'edit' => Pages\EditSupportTicket::route('/{record}/edit'),
        ];
    }
}
