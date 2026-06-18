<?php

namespace App\Filament\Resources\Advertisements\Tables;

use App\Models\AdPackage;
use App\Models\Advertisement;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AdvertisementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('priority', 'desc')
            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('advertiser.name')
                    ->label('Advertiser')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('adPackage.name')
                    ->label('Package')
                    ->toggleable(),
                TextColumn::make('placement_position')
                    ->formatStateUsing(fn (string $state): string => AdPackage::placementOptions()[$state] ?? $state)
                    ->badge()
                    ->sortable(),
                TextColumn::make('target_type')
                    ->formatStateUsing(fn (string $state): string => Advertisement::targetOptions()[$state] ?? $state)
                    ->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Advertisement::STATUS_ACTIVE => 'success',
                        Advertisement::STATUS_EXPIRED => 'danger',
                        Advertisement::STATUS_PAUSED => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Advertisement::PAYMENT_PAID => 'success',
                        Advertisement::PAYMENT_PARTIAL => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('amount_paid')
                    ->money('BDT')
                    ->sortable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('priority')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('impressions_count')
                    ->label('Impressions')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('clicks_count')
                    ->label('Clicks')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('sponsored_label')
                    ->label('Sponsored')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        Advertisement::STATUS_DRAFT => 'Draft',
                        Advertisement::STATUS_ACTIVE => 'Active',
                        Advertisement::STATUS_EXPIRED => 'Expired',
                        Advertisement::STATUS_PAUSED => 'Paused',
                    ]),
                SelectFilter::make('placement_position')
                    ->options(AdPackage::placementOptions()),
                SelectFilter::make('target_type')
                    ->options(Advertisement::targetOptions()),
                SelectFilter::make('payment_status')
                    ->options([
                        Advertisement::PAYMENT_UNPAID => 'Unpaid',
                        Advertisement::PAYMENT_PARTIAL => 'Partial',
                        Advertisement::PAYMENT_PAID => 'Paid',
                    ]),
                Filter::make('expiring_soon')
                    ->label('Expiring within 3 days')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('status', Advertisement::STATUS_ACTIVE)
                        ->whereBetween('end_date', [now()->toDateString(), now()->addDays(3)->toDateString()])),
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
