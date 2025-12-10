<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccessPointResource\Pages;
use App\Models\AccessPoint;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;

class AccessPointResource extends Resource
{
    protected static ?string $model = AccessPoint::class;

    protected static ?string $navigationIcon = 'heroicon-o-wifi';

    protected static ?string $navigationGroup = 'Network';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Access Point Form')->tabs([
                    Tabs\Tab::make('Details')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\Select::make('office_id')
                                ->relationship('office', 'name', fn (Builder $query) => $query->with('site'))
                                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name}" . ($record->site ? " ({$record->site->name})" : ''))
                                ->prefixIcon('heroicon-o-building-office-2')
                                ->required(),
                            Forms\Components\TextInput::make('name')
                                ->prefixIcon('heroicon-o-signal')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('ip_address')
                                ->label('Management IP')
                                ->prefixIcon('heroicon-o-computer-desktop')
                                ->ip()
                                ->maxLength(255),
                            Forms\Components\Textarea::make('notes')
                                ->columnSpanFull(),
                        ])->columns(2),
                    Tabs\Tab::make('Network')
                        ->icon('heroicon-o-server-stack')
                        ->schema([
                            Forms\Components\TextInput::make('wan_ip')
                                ->label('WAN IP')
                                ->prefixIcon('heroicon-o-globe-alt')
                                ->ip()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('lan_ip')
                                ->label('LAN IP')
                                ->prefixIcon('heroicon-o-squares-plus')
                                ->ip()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('mode')
                                ->label('Device Mode')
                                ->prefixIcon('heroicon-o-arrows-right-left')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('type')
                                ->label('Device Type')
                                ->prefixIcon('heroicon-o-tag')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('vlan_id')
                                ->label('VLAN ID')
                                ->prefixIcon('heroicon-o-identification')
                                ->maxLength(255),
                        ])->columns(2),
                    Tabs\Tab::make('Management')
                        ->icon('heroicon-o-key')
                        ->schema([
                            Forms\Components\TextInput::make('management_url')
                                ->label('Management URL')
                                ->prefixIcon('heroicon-o-globe-alt')
                                ->url()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('username')
                                ->prefixIcon('heroicon-o-user')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('password')
                                ->password()
                                ->prefixIcon('heroicon-o-lock-closed')
                                ->required(fn (string $context): bool => $context === 'create')
                                ->dehydrated(fn ($state) => filled($state))
                                ->maxLength(255),
                        ])->columns(2),
                    Tabs\Tab::make('Associated SSIDs')
                        ->icon('heroicon-o-rss')
                        ->schema([
                            Forms\Components\Repeater::make('wifiSsids')
                                ->relationship()
                                ->schema([
                                    Forms\Components\TextInput::make('ssid')
                                        ->prefixIcon('heroicon-o-wifi')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('password')
                                        ->password()
                                        ->prefixIcon('heroicon-o-lock-closed')
                                        ->required(fn (string $context): bool => $context === 'create')
                                        ->dehydrated(fn ($state) => filled($state))
                                        ->maxLength(255),
                                ])
                                ->columns(2)
                                ->columnSpanFull(),
                        ]),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('office.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('office.site.name')
                    ->label('Site')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('Management IP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wan_ip')->label('WAN IP')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('lan_ip')->label('LAN IP')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('mode')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('type')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('vlan_id')->label('VLAN ID')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('wifiSsids.ssid')
                    ->label('SSIDs')
                    ->listWithLineBreaks()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
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
            'index' => Pages\ListAccessPoints::route('/'),
            'create' => Pages\CreateAccessPoint::route('/create'),
            'edit' => Pages\EditAccessPoint::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
