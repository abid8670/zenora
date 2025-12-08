<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HostingResource\Pages;
use App\Models\Hosting;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class HostingResource extends Resource
{
    protected static ?string $model = Hosting::class;

    protected static ?string $navigationIcon = 'heroicon-o-server';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Hosting Details')->tabs([
                    Tabs\Tab::make('General Information')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Section::make('Core Details')
                                ->description('Provide the main details of the hosting plan.')
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Nickname')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpan(1),
                                    Select::make('status')
                                        ->options(['Active' => 'Active', 'Inactive' => 'Inactive'])
                                        ->required()
                                        ->default('Active')
                                        ->columnSpan(1),
                                    TextInput::make('provider')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpan(1),
                                    TextInput::make('plan_name')
                                        ->label('Plan Name')
                                        ->maxLength(255)
                                        ->columnSpan(1),
                                ])->columns(2),
                            Section::make('Association')
                                ->schema([
                                    Select::make('project_id')
                                        ->relationship('project', 'name')
                                        ->searchable()
                                        ->required(),
                                    Select::make('office_id')
                                        ->relationship('office', 'name')
                                        ->searchable()
                                        ->required(),
                                ])->columns(2),
                            Section::make('Important Dates')
                                ->schema([
                                    DatePicker::make('registration_date')
                                        ->label('Registration Date'),
                                    DatePicker::make('expiry_date')
                                        ->label('Expiry Date'),
                                ])->columns(2),
                        ]),

                    Tabs\Tab::make('Credentials & Access')
                        ->icon('heroicon-o-key')
                        ->schema([
                            Section::make('Server & Login Details')
                                ->schema([
                                    TextInput::make('server_ip')
                                        ->label('Server IP')
                                        ->ip()
                                        ->maxLength(255),
                                    TextInput::make('login_url')
                                        ->label('Login URL')
                                        ->maxLength(255),
                                    TextInput::make('username')
                                        ->maxLength(255),
                                    TextInput::make('password')
                                        ->password()
                                        ->revealable()
                                        ->dehydrated(fn ($state) => filled($state)),
                                ])->columns(2),
                        ]),

                    Tabs\Tab::make('DNS & Backup')
                        ->icon('heroicon-o-globe-alt')
                        ->schema([
                            Section::make('DNS Configuration')
                                ->schema([
                                    KeyValue::make('nameservers')
                                        ->label('Nameservers')
                                        ->keyLabel('NS Record')
                                        ->valueLabel('Value')
                                        ->reorderable(),
                                    TextInput::make('dns_management_url')
                                        ->label('DNS Management URL')
                                        ->url()
                                        ->columnSpanFull(),
                                ]),
                            Section::make('Backup Information')
                                ->schema([
                                    Textarea::make('backup_info')
                                        ->label('Backup Details')
                                        ->rows(5)
                                        ->columnSpanFull(),
                                ]),
                        ]),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('project.name')->searchable()->sortable(),
                TextColumn::make('provider')->searchable()->sortable(),
                TextColumn::make('office.name')->searchable()->sortable(),
                TextColumn::make('server_ip')->label('Server IP')->searchable(),
                ToggleColumn::make('status')
                    ->onColor('success')
                    ->offColor('danger'),
                TextColumn::make('expiry_date')
                    ->date('d-M-Y')
                    ->sortable()
                    ->label('Expires On'),
                TextColumn::make('creator.name')->label('Created By')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Last Updated')->since()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListHostings::route('/'),
            'create' => Pages\CreateHosting::route('/create'),
            'edit' => Pages\EditHosting::route('/{record}/edit'),
        ];
    }
}
