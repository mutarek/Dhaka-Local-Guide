<?php

namespace App\Filament\Resources\AdPackages\Tables;

use App\Models\AdPackage;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AdPackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('duration_days')
                    ->label('Days')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price')
                    ->money('BDT')
                    ->sortable(),
                TextColumn::make('placement_type')
                    ->formatStateUsing(fn (string $state): string => AdPackage::placementOptions()[$state] ?? $state)
                    ->badge()
                    ->sortable(),
                TextColumn::make('advertisements_count')
                    ->counts('advertisements')
                    ->label('Ads')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === AdPackage::STATUS_ACTIVE ? 'success' : 'gray')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('placement_type')
                    ->options(AdPackage::placementOptions()),
                SelectFilter::make('status')
                    ->options([
                        AdPackage::STATUS_ACTIVE => 'Active',
                        AdPackage::STATUS_INACTIVE => 'Inactive',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
