<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubnetResource\Pages;
use App\Models\Subnet;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;

class SubnetResource extends Resource
{
    protected static ?string $model = Subnet::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?string $navigationGroup = 'Network';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Network Configuration')
                ->schema([
                    Grid::make(3)->schema([
                        Forms\Components\TextInput::make('subnet_address')
                            ->label('Subnet Address (CIDR)')
                            ->placeholder('e.g., 192.168.1.0/24')
                            ->prefixIcon('heroicon-o-computer-desktop')
                            ->required()
                            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule, callable $get) {
                                return $rule->where('office_id', $get('office_id'));
                            }),
                        Forms\Components\TextInput::make('gateway')
                            ->label('Gateway')
                            ->placeholder('e.g., 192.168.1.1')
                            ->prefixIcon('heroicon-o-globe-alt')
                            ->nullable(),
                        Forms\Components\TextInput::make('vlan_id')
                            ->label('VLAN ID')
                            ->placeholder('e.g., 10')
                            ->prefixIcon('heroicon-o-tag')
                            ->numeric()
                            ->nullable(),
                    ]),
                ]),
            Section::make('Description & Association')
                ->schema([
                    Grid::make(3)->schema([
                        Forms\Components\Select::make('office_id')
                            ->relationship('office', 'name')
                            ->label('Associated Office')
                            ->prefixIcon('heroicon-o-building-office')
                            ->required(),
                        Forms\Components\TextInput::make('description')
                            ->label('Description')
                            ->placeholder('e.g., Guest WiFi Network')
                            ->prefixIcon('heroicon-o-chat-bubble-bottom-center-text')
                            ->nullable(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->placeholder('Any additional notes or comments...')
                            ->rows(4)
                            ->columnSpan(2)
                            ->nullable(),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subnet_address')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('office.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vlan_id')
                    ->label('VLAN ID')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('office')->relationship('office', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubnets::route('/'),
            'create' => Pages\CreateSubnet::route('/create'),
            'edit' => Pages\EditSubnet::route('/{record}/edit'),
        ];
    }
}
