<?php

namespace App\Filament\Widgets;

use App\Models\Advertisement;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopClickedAds extends TableWidget
{
    protected static ?string $heading = 'Top clicked ads';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Advertisement::query()->with('advertiser')->orderByDesc('clicks_count')->limit(10))
            ->columns([
                TextColumn::make('title')->searchable()->wrap(),
                TextColumn::make('advertiser.name')->label('Advertiser'),
                TextColumn::make('clicks_count')->label('Clicks')->numeric()->sortable(),
                TextColumn::make('impressions_count')->label('Impressions')->numeric()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
