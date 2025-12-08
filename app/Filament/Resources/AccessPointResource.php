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
use Filament\Forms\Components\Section;

class AccessPointResource extends Resource
{
    protected static ?string $model = AccessPoint::class;

    protected static ?string $navigationIcon = 'heroicon-o-wifi';

    protected static ?string $navigationGroup = 'Network';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Access Point Details')
                    ->description('Provide the main details for the access point.')
                    ->schema([
                        Forms\Components\Select::make('office_id')
                            ->relationship('office', 'name')
                            ->prefixIcon('heroicon-o-building-office-2')
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->prefixIcon('heroicon-o-signal')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('ip_address')
                            ->label('IP Address')
                            ->prefixIcon('heroicon-o-computer-desktop')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Credentials & Management')
                    ->description('Enter the credentials and URL for managing the access point.')
                    ->schema([
                        Forms\Components\TextInput::make('management_url')
                            ->label('Management URL')
                            ->prefixIcon('heroicon-o-globe-alt')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('username')
                            ->prefixIcon('heroicon-o-user')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->prefixIcon('heroicon-o-lock-closed')
                            ->maxLength(255),
                    ])->columns(3),

                Section::make('Associated SSIDs')
                    ->description('Manage the Wi-Fi SSIDs broadcast by this access point.')
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
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('office.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable(),
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
