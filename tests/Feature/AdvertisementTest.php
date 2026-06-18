<?php

use App\Models\AdPackage;
use App\Models\Advertisement;
use App\Models\Advertiser;
use App\Models\Category;
use App\Models\Post;
use App\Services\AdvertisementService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createAdPost(array $overrides = []): Post
{
    $category = Category::query()->create([
        'name' => 'Food',
        'slug' => 'food',
        'is_active' => true,
    ]);

    return Post::query()->create(array_merge([
        'category_id' => $category->id,
        'title' => 'Best Restaurants in Dhaka',
        'slug' => 'best-restaurants-in-dhaka',
        'excerpt' => 'Restaurant guide.',
        'content' => '<p>First paragraph.</p><p>Second paragraph.</p><p>Third paragraph.</p>',
        'status' => Post::STATUS_PUBLISHED,
        'published_at' => now()->subDay(),
    ], $overrides));
}

function createAdvertisement(array $overrides = []): Advertisement
{
    $advertiser = Advertiser::query()->create([
        'name' => 'Dhaka Sponsor',
        'status' => Advertiser::STATUS_ACTIVE,
    ]);

    $package = AdPackage::query()->firstOrCreate(
        ['slug' => '30-days-in-article-ad'],
        [
            'name' => '30 Days In-Article Ad',
            'duration_days' => 30,
            'price' => 15000,
            'placement_type' => AdPackage::PLACEMENT_AFTER_FIRST_PARAGRAPH,
            'status' => AdPackage::STATUS_ACTIVE,
        ]
    );

    return Advertisement::query()->create(array_merge([
        'advertiser_id' => $advertiser->id,
        'ad_package_id' => $package->id,
        'title' => 'Sponsored restaurant offer',
        'image' => 'ads/desktop.webp',
        'mobile_image' => 'ads/mobile.webp',
        'destination_url' => 'https://example.com/offer',
        'placement_position' => AdPackage::PLACEMENT_AFTER_FIRST_PARAGRAPH,
        'target_type' => Advertisement::TARGET_ALL_POSTS,
        'start_date' => now()->subDay()->toDateString(),
        'end_date' => now()->addDays(29)->toDateString(),
        'status' => Advertisement::STATUS_ACTIVE,
        'amount_paid' => 15000,
        'payment_status' => Advertisement::PAYMENT_PAID,
        'priority' => 10,
        'open_in_new_tab' => true,
        'nofollow' => true,
        'sponsored_label' => true,
    ], $overrides));
}

test('advertisement service returns active matching ads by priority', function () {
    $post = createAdPost();
    $lowPriorityAd = createAdvertisement(['priority' => 1]);
    $highPriorityAd = createAdvertisement(['priority' => 50]);
    createAdvertisement([
        'placement_position' => AdPackage::PLACEMENT_SIDEBAR,
        'priority' => 100,
    ]);

    $ads = app(AdvertisementService::class)->getActiveAds(
        position: AdPackage::PLACEMENT_AFTER_FIRST_PARAGRAPH,
        post: $post,
        limit: 2,
    );

    expect($ads->pluck('id')->all())->toBe([$highPriorityAd->id, $lowPriorityAd->id]);
});

test('rendered post ads increment impressions and click route redirects', function () {
    $post = createAdPost();
    $ad = createAdvertisement();

    $this->get(route('posts.show', $post))
        ->assertOk()
        ->assertSee('Sponsored restaurant offer')
        ->assertSee('rel="sponsored nofollow noopener"', false);

    expect($ad->fresh()->impressions_count)->toBe(1);

    $this->get(route('ads.click', $ad))
        ->assertRedirect('https://example.com/offer');

    expect($ad->fresh()->clicks_count)->toBe(1);
});
