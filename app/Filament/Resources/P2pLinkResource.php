<?php

namespace App\Filament\Resources;

use App\Filament\Resources\P2pLinkResource\Pages;
use App\Models\P2pLink;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class P2pLinkResource extends Resource
{
    protected static ?string $model = P2pLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationGroup = 'Network';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('P2P/VPN Link Details')->tabs([
                    Tabs\Tab::make('Link Details')
                        ->icon('heroicon-o-link')
                        ->schema([
                            Section::make()
                                ->columns(3)
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->label('Link / VPN Name')
                                        ->placeholder('e.g., HO to KHI Branch VPN')
                                        ->columnSpanFull(),

                                    Select::make('link_type')
                                        ->options([
                                            'P2P Radio Link' => 'P2P Radio Link',
                                            'Fiber Optic Link' => 'Fiber Optic Link',
                                            'Site-to-Site VPN' => 'Site-to-Site VPN',
                                            'MPLS' => 'MPLS',
                                            'Other' => 'Other',
                                        ])
                                        ->required(),

                                    Select::make('status')
                                        ->options([
                                            'Active' => 'Active',
                                            'Inactive' => 'Inactive',
                                            'Maintenance' => 'Maintenance',
                                        ])
                                        ->required()
                                        ->default('Active'),
                                    
                                    TextInput::make('link_speed')
                                        ->label('Link Speed')
                                        ->suffix('Mbps')
                                        ->numeric(),

                                    Select::make('ownership')
                                        ->options([
                                            'Owned' => 'Owned',
                                            'Rented' => 'Rented',
                                        ]),

                                    Select::make('office_a_id')
                                        ->label('Office A (From)')
                                        ->relationship('officeA', 'name', fn (Builder $query) => $query->with('site'))
                                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name}" . ($record->site ? " ({$record->site->name})" : ''))
                                        ->searchable()
                                        ->required()
                                        ->columnSpan(2),

                                    Select::make('office_b_id')
                                        ->label('Office B (To)')
                                        ->relationship('officeB', 'name', fn (Builder $query) => $query->with('site'))
                                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name}" . ($record->site ? " ({$record->site->name})" : ''))
                                        ->searchable()
                                        ->required()
                                        ->columnSpan(2),
                                ]),
                        ]),

                    Tabs\Tab::make('Device A Details')
                        ->icon('heroicon-o-server')
                        ->schema([
                            Section::make('Device/Point A')
                                ->columns(3)
                                ->schema([
                                    TextInput::make('device_a_type')->label('Device Type')->placeholder('e.g., PowerBeam M5'),
                                    Select::make('device_a_mode')->options(['Access Point' => 'Access Point', 'Station' => 'Station', 'Bridge' => 'Bridge', 'Router' => 'Router'])->label('Device Mode'),
                                    TextInput::make('device_a_wan_ip')->label('WAN IP')->ip(),
                                    TextInput::make('device_a_url')->label('Management URL'),
                                    TextInput::make('device_a_username')->label('Username'),
                                    TextInput::make('device_a_password')->label('Password')->password()->revealable()->autocomplete('new-password'),
                                ]),
                        ]),
                    
                    Tabs\Tab::make('Device B Details')
                        ->icon('heroicon-o-server')
                        ->schema([
                            Section::make('Device/Point B')
                                ->columns(3)
                                ->schema([
                                    TextInput::make('device_b_type')->label('Device Type')->placeholder('e.g., MikroTik SXT'),
                                    Select::make('device_b_mode')->options(['Access Point' => 'Access Point', 'Station' => 'Station', 'Bridge' => 'Bridge', 'Router' => 'Router'])->label('Device Mode'),
                                    TextInput::make('device_b_wan_ip')->label('WAN IP')->ip(),
                                    TextInput::make('device_b_url')->label('Management URL'),
                                    TextInput::make('device_b_username')->label('Username'),
                                    TextInput::make('device_b_password')->label('Password')->password()->revealable()->autocomplete('new-password'),
                                ]),
                        ]),

                    Tabs\Tab::make('Remarks')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->schema([
                            Section::make()
                                ->schema([
                                    Textarea::make('remarks')
                                        ->label('Additional Notes')
                                        ->columnSpan('full'),
                                ]),
                        ])
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('link_type')->label('Type')->searchable(),
                BadgeColumn::make('status')->colors(['success' => 'Active', 'danger' => 'Inactive', 'warning' => 'Maintenance']),
                TextColumn::make('link_speed')->label('Speed (Mbps)')->sortable(),
                TextColumn::make('ownership')->searchable(),
                TextColumn::make('officeA.name')->label('From Office')->searchable(),
                TextColumn::make('officeB.name')->label('To Office')->searchable(),
            ])
            ->filters([
                SelectFilter::make('link_type')->options([
                    'P2P Radio Link' => 'P2P Radio Link',
                    'Fiber Optic Link' => 'Fiber Optic Link',
                    'Site-to-Site VPN' => 'Site-to-Site VPN',
                    'MPLS' => 'MPLS',
                    'Other' => 'Other',
                ]),
                SelectFilter::make('status')->options(['Active' => 'Active', 'Inactive' => 'Inactive', 'Maintenance' => 'Maintenance']),
                SelectFilter::make('ownership')->options(['Owned' => 'Owned', 'Rented' => 'Rented']),
                SelectFilter::make('officeA')->relationship('officeA', 'name')->label('From Office'),
                SelectFilter::make('officeB')->relationship('officeB', 'name')->label('To Office'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListP2pLinks::route('/'),
            'create' => Pages\CreateP2pLink::route('/create'),
            'edit' => Pages\EditP2pLink::route('/{record}/edit'),
        ];
    }
}
