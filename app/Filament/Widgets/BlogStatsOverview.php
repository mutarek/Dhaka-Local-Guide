<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BlogStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Dashboard overview';

    protected function getStats(): array
    {
        return [
            Stat::make('Total posts', Post::query()->count())->color('primary'),
            Stat::make('Published posts', Post::query()->where('status', Post::STATUS_PUBLISHED)->count())->color('success'),
            Stat::make('Draft posts', Post::query()->where('status', Post::STATUS_DRAFT)->count())->color('gray'),
            Stat::make('Categories', Category::query()->count())->color('info'),
            Stat::make('Tags', Tag::query()->count())->color('warning'),
            Stat::make('Total views', number_format(Post::query()->sum('views_count')))->color('danger'),
        ];
    }
}
