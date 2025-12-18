<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use App\Models\Asset;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AssetsRelationManager extends RelationManager
{
    protected static string $relationship = 'assetAssignmentLogs';

    public function form(Form $form): Form
    {
        return $form->schema([]); // Not used
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('asset.name')
            ->columns([
                TextColumn::make('asset.name'),
                TextColumn::make('asset.serial_number'),
                TextColumn::make('assigned_date')->date()->sortable(),
                TextColumn::make('returned_date')->date()->sortable(),
                TextColumn::make('return_status')->badge(),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([
                Action::make('return')
                    ->label('Return Asset')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->form([
                        DatePicker::make('returned_date')
                            ->label('Return Date')
                            ->default(now())
                            ->required(),
                        Select::make('return_status')
                            ->options([
                                'OK' => 'OK',
                                'Damaged' => 'Damaged',
                                'Lost' => 'Lost',
                            ])
                            ->required(),
                        Textarea::make('notes')
                            ->label('Notes'),
                    ])
                    ->action(function (Model $record, array $data) {
                        $record->update([
                            'returned_date' => $data['returned_date'],
                            'return_status' => $data['return_status'],
                            'notes' => $data['notes'],
                        ]);

                        $asset = $record->asset;
                        $newStatus = match ($data['return_status']) {
                            'OK' => 'In Stock',
                            'Damaged' => 'Damaged',
                            'Lost' => 'Lost',
                            default => $asset->status,
                        };
                        $asset->update(['status' => $newStatus]);

                        Notification::make()
                            ->title('Asset returned successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Model $record): bool => is_null($record->returned_date)),
            ])
            ->bulkActions([]);
    }
}
