<?php

namespace App\Filament\Widgets;

use App\Models\Advertisement;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Advertising overview';

    protected function getStats(): array
    {
        return [
            Stat::make('Active ads', Advertisement::query()->where('status', Advertisement::STATUS_ACTIVE)->count())
                ->color('success'),
            Stat::make('Expired ads', Advertisement::query()->where('status', Advertisement::STATUS_EXPIRED)->count())
                ->color('danger'),
            Stat::make('Total ad revenue', '৳'.number_format((float) Advertisement::query()->sum('amount_paid'), 2))
                ->color('primary'),
            Stat::make('Unpaid active ads', Advertisement::query()
                ->where('status', Advertisement::STATUS_ACTIVE)
                ->where('payment_status', '!=', Advertisement::PAYMENT_PAID)
                ->count())
                ->color('warning'),
        ];
    }
}
