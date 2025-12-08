<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Filament\Resources\HostingResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class HostingsRelationManager extends RelationManager
{
    protected static string $relationship = 'hostings';

    public function form(Form $form): Form
    {
        // Get the original schema from the resource, passing the livewire component instance.
        $schema = HostingResource::form(Form::make($this))->getComponents();

        // Recursively remove the 'project_id' field.
        $filteredSchema = $this->removeComponentByName($schema, 'project_id');

        return $form->schema($filteredSchema);
    }

    private function removeComponentByName(array $components, string $nameToRemove): array
    {
        $filteredComponents = [];

        foreach ($components as $component) {
            // If the current component is the one to remove, skip it.
            if (method_exists($component, 'getName') && $component->getName() === $nameToRemove) {
                continue;
            }

            // If the component is a container (like Tabs, Section, Grid),
            // we need to recurse into its children.
            if (method_exists($component, 'getChildComponents') && count($component->getChildComponents())) {
                $children = $this->removeComponentByName($component->getChildComponents(), $nameToRemove);
                $component->schema($children); // Re-set the schema with the filtered children.
            }

            $filteredComponents[] = $component;
        }

        return $filteredComponents;
    }


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('provider')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('office.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('server_ip')->label('Server IP')->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
