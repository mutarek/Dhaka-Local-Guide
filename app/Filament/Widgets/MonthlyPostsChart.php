<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\ChartWidget;

class MonthlyPostsChart extends ChartWidget
{
    protected ?string $heading = 'Monthly post count';

    protected string $color = 'primary';

    protected function getData(): array
    {
        $months = collect(range(11, 0))
            ->map(fn (int $monthsAgo) => now()->subMonths($monthsAgo)->startOfMonth());

        return [
            'datasets' => [
                [
                    'label' => 'Posts',
                    'data' => $months
                        ->map(fn ($month) => Post::query()
                            ->whereBetween('published_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
                            ->count())
                        ->all(),
                ],
            ],
            'labels' => $months->map(fn ($month) => $month->format('M Y'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
