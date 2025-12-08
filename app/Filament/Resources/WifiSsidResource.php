<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WifiSsidResource\Pages;
use App\Models\WifiSsid;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Components\Section;

class WifiSsidResource extends Resource
{
    protected static ?string $model = WifiSsid::class;

    protected static ?string $navigationIcon = 'heroicon-o-rss';

    protected static ?string $navigationGroup = 'Network';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Relations')
                    ->description('Define the office and access point for this SSID.')
                    ->schema([
                        Forms\Components\Select::make('office_id')
                            ->relationship('office', 'name')
                            ->prefixIcon('heroicon-o-building-office-2')
                            ->required(),
                        Forms\Components\Select::make('access_point_id')
                            ->relationship('accessPoint', 'name')
                            ->prefixIcon('heroicon-o-wifi')
                            ->required(),
                    ])->columns(2),
                Section::make('Details')
                    ->description('Provide the SSID name, password, and any relevant notes.')
                    ->schema([
                        Forms\Components\TextInput::make('ssid')
                            ->prefixIcon('heroicon-o-signal')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->prefixIcon('heroicon-o-lock-closed')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ssid')
                    ->searchable(),
                Tables\Columns\TextColumn::make('office.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            ])
            ->groups([
                Group::make('accessPoint.name')
                    ->label('Access Point'),
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
            'index' => Pages\ListWifiSsids::route('/'),
            'create' => Pages\CreateWifiSsid::route('/create'),
            'edit' => Pages\EditWifiSsid::route('/{record}/edit'),
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
