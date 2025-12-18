<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers\AssetsRelationManager;
use App\Models\Employee;
use App\Models\Office;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Human Resources';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Employee Details')->tabs([
                Tabs\Tab::make('Personal Information')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->prefixIcon('heroicon-o-user'),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->prefixIcon('heroicon-o-envelope'),
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->prefixIcon('heroicon-o-key')
                            ->revealable(),
                        TextInput::make('phone')
                            ->tel()
                            ->prefixIcon('heroicon-o-phone'),
                        TextInput::make('cnic')
                            ->label('CNIC')
                            ->unique(ignoreRecord: true)
                            ->prefixIcon('heroicon-o-identification'),
                        Textarea::make('address')
                            ->columnSpanFull(),
                    ])->columns(3),

                Tabs\Tab::make('Official Details')
                    ->icon('heroicon-o-briefcase')
                    ->schema([
                        TextInput::make('employee_id')
                            ->label('Employee ID')
                            ->disabled()
                            ->prefixIcon('heroicon-o-identification'),
                        Select::make('office_id')
                            ->label('Office')
                            ->options(Office::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->prefixIcon('heroicon-o-building-office'),
                        TextInput::make('designation')
                            ->required()
                            ->prefixIcon('heroicon-o-briefcase'),
                        Select::make('department_id')
                            ->label('Department')
                            ->relationship('department', 'name')
                            ->required()
                            ->prefixIcon('heroicon-o-tag'),
                        DatePicker::make('joining_date')
                            ->required()
                            ->prefixIcon('heroicon-o-calendar-days'),
                        DatePicker::make('leaving_date')
                            ->prefixIcon('heroicon-o-calendar-days'),
                        Select::make('status')
                            ->options([
                                'Active' => 'Active',
                                'Probation' => 'Probation',
                                'Resigned' => 'Resigned',
                                'Terminated' => 'Terminated',
                            ])
                            ->required()
                            ->default('Active'),
                    ])->columns(3),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee_id')
                    ->label('Employee ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('office.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('department.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('designation'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Probation' => 'warning',
                        'Resigned' => 'danger',
                        'Terminated' => 'gray',
                        default => 'primary',
                    }),
                TextColumn::make('joining_date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Probation' => 'Probation',
                        'Resigned' => 'Resigned',
                        'Terminated' => 'Terminated',
                    ]),
                SelectFilter::make('office')
                    ->relationship('office', 'name'),
                SelectFilter::make('department')
                    ->relationship('department', 'name')
            ])
            ->actions([
                Action::make('print')
                    ->label('Print Form')
                    ->icon('heroicon-o-printer')
                    ->modalContent(fn (Model $record) => view(
                        'filament.resources.employee-resource.print-form',
                        ['employee' => $record]
                    ))
                    ->modalWidth('3xl') // Make the modal wider
                    ->modalSubmitAction(false) // Hide the default submit button
                    ->modalCancelAction(false), // Hide the default cancel button

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
            AssetsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
