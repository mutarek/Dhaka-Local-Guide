<?php

namespace App\Filament\Widgets;

use App\Models\Advertisement;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class AdNotifications extends TableWidget
{
    protected static ?string $heading = 'Ad notifications';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Advertisement::query()
                ->with('advertiser')
                ->where(function (Builder $query): void {
                    $query
                        ->where(function (Builder $query): void {
                            $query
                                ->where('status', Advertisement::STATUS_ACTIVE)
                                ->whereBetween('end_date', [now()->toDateString(), now()->addDays(3)->toDateString()]);
                        })
                        ->orWhere(function (Builder $query): void {
                            $query
                                ->where('status', Advertisement::STATUS_ACTIVE)
                                ->where('payment_status', '!=', Advertisement::PAYMENT_PAID);
                        })
                        ->orWhereNull('image')
                        ->orWhereNull('destination_url')
                        ->orWhere('destination_url', '');
                })
                ->latest('updated_at'))
            ->columns([
                TextColumn::make('title')->searchable()->wrap(),
                TextColumn::make('advertiser.name')->label('Advertiser'),
                TextColumn::make('status')->badge(),
                TextColumn::make('payment_status')->badge(),
                TextColumn::make('end_date')->date()->sortable(),
                TextColumn::make('alert')
                    ->label('Notification')
                    ->state(function (Advertisement $record): string {
                        if ($record->status === Advertisement::STATUS_ACTIVE && $record->end_date->betweenIncluded(now()->startOfDay(), now()->addDays(3)->endOfDay())) {
                            return 'Expiring within 3 days';
                        }

                        if ($record->status === Advertisement::STATUS_ACTIVE && $record->payment_status !== Advertisement::PAYMENT_PAID) {
                            return 'Active ad is unpaid';
                        }

                        return 'Missing image or destination URL';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Expiring within 3 days' => 'warning',
                        'Active ad is unpaid' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
