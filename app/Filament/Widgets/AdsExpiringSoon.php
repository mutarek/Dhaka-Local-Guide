<?php

namespace App\Filament\Widgets;

use App\Models\Advertisement;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class AdsExpiringSoon extends TableWidget
{
    protected static ?string $heading = 'Ads expiring within 3 days';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Advertisement::query()
                ->with(['advertiser', 'adPackage'])
                ->where('status', Advertisement::STATUS_ACTIVE)
                ->whereBetween('end_date', [now()->toDateString(), now()->addDays(3)->toDateString()])
                ->orderBy('end_date'))
            ->columns([
                TextColumn::make('title')->searchable()->wrap(),
                TextColumn::make('advertiser.name')->label('Advertiser'),
                TextColumn::make('adPackage.name')->label('Package'),
                TextColumn::make('end_date')->date()->sortable(),
                TextColumn::make('payment_status')->badge(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
