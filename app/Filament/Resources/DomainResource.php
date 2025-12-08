<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DomainResource\Pages;
use App\Models\Domain;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DomainResource extends Resource
{
    protected static ?string $model = Domain::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Domain Details')->tabs([
                    Tabs\Tab::make('General Information')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Section::make('Core Details')
                                ->schema([
                                    TextInput::make('name')->label('Domain Name')->required()->maxLength(255),
                                    Select::make('status')->options(['Active' => 'Active', 'Inactive' => 'Inactive'])->required()->default('Active'),
                                ])->columns(2),
                            Section::make('Association')
                                ->schema([
                                    Select::make('project_id')->relationship('project', 'name')->searchable()->required(),
                                    Select::make('office_id')->relationship('office', 'name')->searchable()->required(),
                                ])->columns(2),
                            Section::make('Ownership')
                                ->schema([
                                    TextInput::make('owner_name')->maxLength(255),
                                    TextInput::make('owner_email')->email()->maxLength(255),
                                ])->columns(2),
                            Section::make('Important Dates')
                                ->schema([
                                    DatePicker::make('registration_date'),
                                    DatePicker::make('expiry_date'),
                                ])->columns(2),
                        ]),

                    Tabs\Tab::make('Registrar & DNS')
                        ->icon('heroicon-o-key')
                        ->schema([
                            Section::make('Registrar Details')
                                ->schema([
                                    TextInput::make('registrar')->maxLength(255),
                                    TextInput::make('panel_url')->label('Panel URL')->url()->maxLength(255),
                                ])->columns(2),
                            Section::make('Panel Credentials')
                                ->schema([
                                    TextInput::make('panel_username')->label('Panel Username')->maxLength(255),
                                    TextInput::make('panel_password')->label('Panel Password')->password()->dehydrated(fn ($state) => filled($state))->revealable(),
                                ])->columns(2),
                             Section::make('Nameservers')
                                ->schema([
                                    KeyValue::make('nameservers')
                                        ->label('Nameservers')
                                        ->keyLabel('NS Record')
                                        ->valueLabel('Value')
                                        ->reorderable(),
                                ]),
                        ]),

                    Tabs\Tab::make('Hosting')
                        ->icon('heroicon-o-server')
                        ->schema([
                            Section::make('Hosting Details')
                                ->schema([
                                    Select::make('primary_hosting_id')
                                        ->relationship('primaryHosting', 'name', fn (Builder $query) => $query->where('status', 'Active'))
                                        ->label('Primary Hosting')->searchable(),
                                    Select::make('backup_hosting_id')
                                        ->relationship('backupHosting', 'name', fn (Builder $query) => $query->where('status', 'Active'))
                                        ->label('Backup Hosting')->searchable(),
                                ])->columns(2),
                        ]),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                ToggleColumn::make('status')->onColor('success')->offColor('danger'),
                TextColumn::make('project.name')->searchable()->sortable(),
                TextColumn::make('registrar')->searchable()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('expiry_date')->date('d-M-Y')->sortable(),
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
            'index' => Pages\ListDomains::route('/'),
            'create' => Pages\CreateDomain::route('/create'),
            'edit' => Pages\EditDomain::route('/{record}/edit'),
        ];
    }
}
