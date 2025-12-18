<?php

namespace App\Filament\Resources\AssetResource\Widgets;

use App\Models\AssetAssignmentLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;

class AssetAssignments extends BaseWidget
{
    protected static ?string $heading = 'Recent Asset Assignments';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AssetAssignmentLog::query()->latest()->limit(5)
            )
            ->columns([
                TextColumn::make('asset.name')->label('Asset'),
                TextColumn::make('employee.name')->label('Assigned To'),
                TextColumn::make('assigned_date')->label('Assigned Date')->date(),
            ]);
    }
}
