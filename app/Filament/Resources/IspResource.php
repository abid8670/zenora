<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IspResource\Pages;
use App\Models\Isp;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class IspResource extends Resource
{
    protected static ?string $model = Isp::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Network';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('ISP Details')->tabs([
                    Tabs\Tab::make('Connection Details')
                        ->icon('heroicon-o-signal')
                        ->schema([
                            Section::make()
                                ->columns(2)
                                ->schema([
                                    Select::make('office_id')
                                        ->relationship('office', 'name', fn (Builder $query) => $query->with('site'))
                                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name}" . ($record->site ? " ({$record->site->name})" : ''))
                                        ->prefixIcon('heroicon-o-building-office-2')
                                        ->searchable()
                                        ->required()
                                        ->label('Office Location'),
                                    TextInput::make('name')
                                        ->required()
                                        ->label('Connection Name')
                                        ->placeholder('e.g., Main Office Fiber'),
                                    Select::make('provider')
                                        ->options([
                                            'PTCL' => 'PTCL',
                                            'StormFiber' => 'StormFiber',
                                            'Nayatel' => 'Nayatel',
                                            'Optix' => 'Optix',
                                            'Cybernet' => 'Cybernet',
                                            'Wateen' => 'Wateen',
                                            'Zong 4G' => 'Zong 4G',
                                            'Jazz 4G' => 'Jazz 4G',
                                            'Ufone 4G' => 'Ufone 4G',
                                            'Telenor 4G' => 'Telenor 4G',
                                            'SCO' => 'SCO',
                                            'Transworld Home' => 'Transworld Home',
                                            'Fiberlink' => 'Fiberlink',
                                            'Satcomm' => 'Satcomm',
                                            'Connect Communications' => 'Connect Communications',
                                        ])
                                        ->searchable()
                                        ->required()
                                        ->label('ISP Provider'),
                                    TextInput::make('speed')
                                        ->required()
                                        ->label('Connection Speed')
                                        ->placeholder('e.g., 100 Mbps'),
                                    TextInput::make('circuit_id')
                                        ->label('Circuit ID / Customer ID'),
                                    Select::make('connection_type')
                                        ->options([
                                            'Fiber Optic' => 'Fiber Optic',
                                            'DSL' => 'DSL',
                                            'Radio Link' => 'Radio Link',
                                            'Satellite' => 'Satellite',
                                            'Other' => 'Other',
                                        ]),
                                    TextInput::make('location')
                                        ->placeholder('e.g., Server Room, 3rd Floor')
                                        ->columnSpanFull(),
                                    DatePicker::make('installation_date'),
                                    Select::make('status')
                                        ->options([
                                            'Active' => 'Active',
                                            'Inactive' => 'Inactive',
                                            'Maintenance' => 'Maintenance',
                                        ])
                                        ->required()
                                        ->default('Active'),
                                    TextInput::make('firewall_ip')
                                        ->label('Firewall IP Address')
                                        ->prefixIcon('heroicon-o-shield-check')
                                        ->ip()
                                        ->columnSpanFull(),
                                    KeyValue::make('static_ip')
                                        ->label('Static IP Pool')
                                        ->keyLabel('Description')
                                        ->valueLabel('IP Address')
                                        ->addActionLabel('Add another IP')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    Tabs\Tab::make('Billing & Portal')
                        ->icon('heroicon-o-currency-dollar')
                        ->schema([
                            Section::make('Billing Information')
                                ->columns(3)
                                ->schema([
                                    TextInput::make('account_number')
                                        ->label('Account Number'),
                                    TextInput::make('monthly_cost')
                                        ->label('Monthly Cost')
                                        ->numeric()
                                        ->prefix('PKR'),
                                    DatePicker::make('billing_date')
                                        ->label('Next Billing Date'),
                                ]),
                            Section::make('ISP Portal Credentials')
                                ->description('Login details for the ISP management portal.')
                                ->columns(3)
                                ->schema([
                                    TextInput::make('management_url')
                                        ->label('Portal URL')
                                        ->url()
                                        ->placeholder('https://portal.isp.com'),
                                    TextInput::make('username')
                                        ->label('Username'),
                                    TextInput::make('password')
                                        ->label('Password')
                                        ->password()
                                        ->revealable()
                                        ->autocomplete('new-password'),
                                ]),
                        ]),
                    Tabs\Tab::make('Remarks')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->schema([
                            Section::make('Additional Notes')
                                ->schema([
                                    Textarea::make('remarks')
                                        ->label('Jot down anything important here.')
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
                TextColumn::make('office.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Connection Name'),
                TextColumn::make('provider')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('circuit_id')
                    ->label('Circuit / Customer ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('connection_type')
                    ->label('Type')
                    ->searchable(),
                TextColumn::make('speed'),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'Active',
                        'danger' => 'Inactive',
                        'warning' => 'Maintenance',
                    ]),
                TextColumn::make('firewall_ip')->label('Firewall IP')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('static_ip')->label('Static IPs')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('billing_date')
                    ->date()
                    ->sortable()
                    ->label('Next Billing')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('installation_date')
                    ->date()
                    ->sortable()
                    ->label('Installed On')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('office')->relationship('office', 'name'),
                SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        'Maintenance' => 'Maintenance',
                    ]),
                SelectFilter::make('provider')
                    ->options(fn() => Isp::query()->distinct()->pluck('provider', 'provider')),
                SelectFilter::make('connection_type')
                    ->options([
                        'Fiber Optic' => 'Fiber Optic',
                        'DSL' => 'DSL',
                        'Radio Link' => 'Radio Link',
                        'Satellite' => 'Satellite',
                        'Other' => 'Other',
                    ])
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
            'index' => Pages\ListIsps::route('/'),
            'create' => Pages\CreateIsp::route('/create'),
            'edit' => Pages\EditIsp::route('/{record}/edit'),
        ];
    }
}
