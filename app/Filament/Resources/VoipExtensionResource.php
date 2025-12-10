<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoipExtensionResource\Pages;
use App\Models\Office;
use App\Models\VoipExtension;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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

class VoipExtensionResource extends Resource
{
    protected static ?string $model = VoipExtension::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationGroup = 'Network';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                TextInput::make('extension_number')
                    ->required()
                    ->numeric()
                    ->unique(ignoreRecord: true)
                    ->label('Extension Number')
                    ->prefixIcon('heroicon-o-hashtag'),

                TextInput::make('display_name')
                    ->label('Assigned To Name')
                    ->placeholder('e.g., John Doe, Conference Room')
                    ->required(),

                Select::make('office_id')
                    ->label('Office Location')
                    ->relationship('office', 'name', fn (Builder $query) => $query->with('site'))
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name}" . ($record->site ? " ({$record->site->name})" : ''))
                    ->searchable()
                    ->required(),

                Select::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Reserved' => 'Reserved',
                        'Inactive' => 'Inactive',
                    ])
                    ->required()
                    ->default('Active'),

                Textarea::make('remarks')
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('extension_number')->searchable()->sortable(),
                TextColumn::make('display_name')->searchable()->label('Assigned To'),
                TextColumn::make('office.name')->searchable()->sortable()->label('Office'),
                TextColumn::make('office.site.name')->searchable()->sortable()->label('Site'),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'Active',
                        'warning' => 'Reserved',
                        'danger' => 'Inactive',
                    ])
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Reserved' => 'Reserved',
                        'Inactive' => 'Inactive',
                    ]),
                SelectFilter::make('office')->relationship('office', 'name', fn (Builder $query) => $query->with('site'))->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name}" . ($record->site ? " ({$record->site->name})" : ''))->searchable(),
                SelectFilter::make('site')->relationship('office.site', 'name')->label('Site'),
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVoipExtensions::route('/'),
            'create' => Pages\CreateVoipExtension::route('/create'),
            'edit' => Pages\EditVoipExtension::route('/{record}/edit'),
        ];
    }
}
