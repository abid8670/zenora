<?php

namespace App\Filament\Resources;

use App\Filament\Resources\P2pLinkResource\Pages;
use App\Models\P2pLink;
use App\Models\Office;
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
                                ->columns(2)
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->label('Link / VPN Name')
                                        ->placeholder('e.g., HO to KHI Branch VPN')
                                        ->columnSpanFull(),
                                    Select::make('link_type')
                                        ->options([
                                            'P2P Radio Link' => 'P2P Radio Link',
                                            'Site-to-Site VPN' => 'Site-to-Site VPN',
                                            'MPLS' => 'MPLS',
                                            'Other' => 'Other',
                                        ])
                                        ->required()
                                        ->reactive(),
                                    Select::make('status')
                                        ->options([
                                            'Active' => 'Active',
                                            'Inactive' => 'Inactive',
                                            'Maintenance' => 'Maintenance',
                                        ])
                                        ->required()
                                        ->default('Active'),
                                    Select::make('office_a_id')
                                        ->label('Office A')
                                        ->options(Office::all()->pluck('name', 'id'))
                                        ->searchable()
                                        ->required(),
                                    Select::make('office_b_id')
                                        ->label('Office B')
                                        ->options(Office::all()->pluck('name', 'id'))
                                        ->searchable()
                                        ->required(),
                                ]),
                        ]),

                    Tabs\Tab::make('Device / Server Access')
                        ->icon('heroicon-o-server')
                        ->schema([
                            Section::make('Management Access')
                                ->description('Credentials to access the radio link device or main VPN server.')
                                ->columns(3)
                                ->schema([
                                    TextInput::make('device_url')
                                        ->label('Management URL / Server Address')
                                        ->placeholder('e.g., 192.168.1.1 or vpn.example.com'),
                                    TextInput::make('username')
                                        ->label('Username'),
                                    TextInput::make('password')
                                        ->label('Password')
                                        ->password()
                                        ->revealable()
                                        ->autocomplete('new-password'),
                                ]),
                        ]),

                    Tabs\Tab::make('VPN Client Credentials')
                        ->icon('heroicon-o-user')
                        ->visible(fn ($get) => $get('link_type') === 'Site-to-Site VPN')
                        ->schema([
                            Section::make('VPN Client Details')
                                ->description('Credentials for the VPN client user, if applicable.')
                                ->columns(3)
                                ->schema([
                                    TextInput::make('vpn_server_ip')
                                        ->label('VPN Server IP')
                                        ->ip(),
                                    TextInput::make('vpn_user')
                                        ->label('VPN Username'),
                                    TextInput::make('vpn_password')
                                        ->label('VPN Password')
                                        ->password()
                                        ->revealable()
                                        ->autocomplete('new-password'),
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
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('link_type')
                    ->label('Type')
                    ->searchable(),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'Active',
                        'danger' => 'Inactive',
                        'warning' => 'Maintenance',
                    ]),
                TextColumn::make('officeA.name')
                    ->label('From Office')
                    ->searchable(),
                TextColumn::make('officeB.name')
                    ->label('To Office')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('link_type')
                    ->options([
                        'P2P Radio Link' => 'P2P Radio Link',
                        'Site-to-Site VPN' => 'Site-to-Site VPN',
                        'MPLS' => 'MPLS',
                        'Other' => 'Other',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        'Maintenance' => 'Maintenance',
                    ]),
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
