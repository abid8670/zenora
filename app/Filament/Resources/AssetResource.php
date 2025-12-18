<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\RelationManagers\AssetAssignmentLogsRelationManager;
use App\Models\Asset;
use App\Models\AssetAssignmentLog;
use App\Models\Employee;
use App\Models\Office;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Navigation\NavigationItem;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Inventory';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Asset Details')
                ->description('Provide the main details of the asset.')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-archive-box'),
                    Select::make('asset_category_id')
                        ->relationship('assetCategory', 'name')
                        ->required()
                        ->prefixIcon('heroicon-o-tag'),
                    TextInput::make('serial_number')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-qr-code'),
                    Select::make('status')
                        ->options([
                            'In Stock' => 'In Stock',
                            'Assigned' => 'Assigned',
                            'Damaged' => 'Damaged',
                            'Lost' => 'Lost',
                            'Retired' => 'Retired',
                        ])
                        ->required()
                        ->default('In Stock')
                        ->prefixIcon('heroicon-o-check-circle'),
                ])->columns(2),

            Section::make('Purchase and Location')
                ->description("Information about the asset\'s purchase and location.")
                ->schema([
                    DatePicker::make('purchase_date')
                        ->prefixIcon('heroicon-o-calendar-days'),
                    TextInput::make('purchase_cost')
                        ->numeric()
                        ->prefix('PKR'),
                    Select::make('office_id')
                        ->searchable()
                        ->getSearchResultsUsing(function (string $search): array {
                            $offices = Office::query()
                                ->where(function (Builder $query) use ($search) {
                                    $query->where('name', 'like', "%{$search}%")
                                        ->orWhereHas('site', function (Builder $query) use ($search) {
                                            $query->where('name', 'like', "%{$search}%");
                                        });
                                })
                                ->with('site')
                                ->limit(50)
                                ->get();

                            return $offices->mapWithKeys(function (Office $office) {
                                return [$office->getKey() => "{$office->site?->name} - {$office->name}"];
                            })->all();
                        })
                        ->getOptionLabelUsing(function ($value): ?string {
                            $office = Office::with('site')->find($value);
                            return $office ? "{$office->site?->name} - {$office->name}" : null;
                        })
                        ->required()
                        ->prefixIcon('heroicon-o-building-office-2'),
                ])->columns(3),

            Section::make('Notes')
                ->description('Additional notes about the asset.')
                ->schema([
                    Textarea::make('notes')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('assetCategory.name')->label('Category')->sortable(),
                TextColumn::make('site.name')->sortable()->searchable(),
                TextColumn::make('office.name')->sortable(),
                TextColumn::make('serial_number')->searchable(),
                TextColumn::make('status')->searchable()->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'In Stock' => 'success',
                        'Assigned' => 'primary',
                        'Damaged' => 'warning',
                        'Lost', 'Retired' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('site')->relationship('site', 'name'),
                SelectFilter::make('status')
                    ->options([
                        'In Stock' => 'In Stock',
                        'Assigned' => 'Assigned',
                        'Damaged' => 'Damaged',
                        'Lost' => 'Lost',
                        'Retired' => 'Retired',
                    ])
                    ->label('Status'),
                SelectFilter::make('assetCategory')
                    ->relationship('assetCategory', 'name')
                    ->label('Category'),
                SelectFilter::make('office')
                    ->relationship('office', 'name')
                    ->label('Office'),
            ])
            ->actions([
                Action::make('assign')
                    ->label('Assign')
                    ->icon('heroicon-o-user-plus')
                    ->form([
                        Select::make('employee_id')
                            ->label('Employee')
                            ->options(Employee::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        DatePicker::make('assigned_date')
                            ->label('Assigned Date')
                            ->default(now())
                            ->required(),
                        Textarea::make('notes')
                            ->label('Notes'),
                    ])
                    ->action(function (Asset $record, array $data) {
                        AssetAssignmentLog::create([
                            'asset_id' => $record->id,
                            'employee_id' => $data['employee_id'],
                            'assigned_date' => $data['assigned_date'],
                            'notes' => $data['notes'],
                        ]);
                        $record->status = 'Assigned';
                        $record->save();
                        Notification::make()
                            ->title('Asset assigned successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Asset $record): bool => $record->status === 'In Stock'),

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
        return [
            AssetAssignmentLogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
            'dashboard' => Pages\AssetDashboard::route('/dashboard'),
        ];
    }

    public static function getNavigationItems(): array
    {
        $items = parent::getNavigationItems();

        $items[] = NavigationItem::make('Asset Dashboard')
            ->url(self::getUrl('dashboard'))
            ->icon('heroicon-o-chart-pie')
            ->group(static::getNavigationGroup())
            ->isActiveWhen(fn () => request()->routeIs(self::getRouteBaseName() . '.dashboard'));

        return $items;
    }
}
