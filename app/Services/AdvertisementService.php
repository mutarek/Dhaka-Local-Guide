<?php

namespace App\Services;

use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AdvertisementService
{
    public function getActiveAds(
        string $position,
        ?Post $post = null,
        ?Category $category = null,
        int $limit = 1,
    ): Collection {
        return Advertisement::query()
            ->with(['advertiser', 'adPackage'])
            ->active()
            ->where('placement_position', $position)
            ->where(function (Builder $query) use ($post, $category): void {
                if ($post) {
                    $query
                        ->where('target_type', Advertisement::TARGET_ALL_POSTS)
                        ->orWhere(function (Builder $query) use ($post): void {
                            $query
                                ->where('target_type', Advertisement::TARGET_CATEGORY)
                                ->where('category_id', $post->category_id);
                        })
                        ->orWhere(function (Builder $query) use ($post): void {
                            $query
                                ->where('target_type', Advertisement::TARGET_SPECIFIC_POSTS)
                                ->whereHas('posts', fn (Builder $query): Builder => $query->whereKey($post->id));
                        });

                    return;
                }

                if ($category) {
                    $query
                        ->where(function (Builder $query) use ($category): void {
                            $query
                                ->where('target_type', Advertisement::TARGET_CATEGORY)
                                ->where('category_id', $category->id);
                        });

                    return;
                }

                $query->where('target_type', Advertisement::TARGET_HOMEPAGE);
            })
            ->orderByDesc('priority')
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    public function recordImpressions(Collection $advertisements): void
    {
        $advertisements->each(fn (Advertisement $advertisement) => $advertisement->increment('impressions_count'));
    }
}
