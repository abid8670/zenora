<?php

namespace App\Filament\Resources\AssetResource\RelationManagers;

use App\Models\Asset;
use App\Models\AssetAssignmentLog;
use Filament\Forms;
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

class AssetAssignmentLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'assetAssignmentLogs';

    public function form(Form $form): Form
    {
        return $form->schema([
            // Not used for creation
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('assigned_date')
            ->columns([
                TextColumn::make('employee.name')->label('Assigned To')->searchable(),
                TextColumn::make('assigned_date')->date()->sortable(),
                TextColumn::make('returned_date')->date()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('return_status')->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // No header actions
            ])
            ->actions([
                Action::make('return')
                    ->label('Return Asset')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->form([
                        DatePicker::make('returned_date')
                            ->label('Returned Date')
                            ->default(now())
                            ->required(),
                        Select::make('return_status')
                            ->options([
                                'OK' => 'OK',
                                'Damaged' => 'Damaged',
                                'Lost' => 'Lost',
                                'Stolen' => 'Stolen',
                            ])
                            ->required(),
                        Textarea::make('notes') // Corrected from return_notes
                            ->label('Return Notes'),
                    ])
                    ->action(function (AssetAssignmentLog $record, array $data) {
                        $record->update([
                            'returned_date' => $data['returned_date'],
                            'return_status' => $data['return_status'],
                            'notes' => $data['notes'], // Corrected from return_notes
                        ]);

                        $asset = $record->asset;
                        if (in_array($data['return_status'], ['OK', 'Damaged'])) {
                            $asset->status = $data['return_status'] === 'OK' ? 'In Stock' : 'Damaged';
                        } else {
                            $asset->status = $data['return_status'];
                        }
                        $asset->save();

                        Notification::make()
                            ->title('Asset returned successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (AssetAssignmentLog $record): bool => is_null($record->returned_date)),
            ])
            ->bulkActions([
                // No bulk actions
            ]);
    }
}
