<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createPublishedPost(array $overrides = []): Post
{
    $category = Category::query()->firstOrCreate(
        ['slug' => 'travel'],
        [
            'name' => 'Travel',
            'description' => 'Travel guides for Dhaka readers.',
        ]
    );

    return Post::query()->create(array_merge([
        'category_id' => $category->id,
        'title' => "Dhaka to Cox's Bazar Bus Ticket Price",
        'slug' => 'dhaka-to-coxs-bazar-bus-ticket-price',
        'excerpt' => "A clear guide to Dhaka to Cox's Bazar bus ticket price ranges.",
        'content' => 'Compare coach types, route timing, boarding points, and booking terms.',
        'meta_title' => "Dhaka to Cox's Bazar Bus Ticket Price - Latest Travel Guide",
        'meta_description' => 'Learn bus ticket price ranges, coach options, travel time, and booking tips.',
        'faqs' => [
            [
                'question' => 'How long does the bus journey take?',
                'answer' => 'The journey often takes around 10 to 12 hours.',
            ],
        ],
        'status' => Post::STATUS_PUBLISHED,
        'published_at' => now()->subDay(),
    ], $overrides));
}

test('home page returns seo metadata', function () {
    createPublishedPost();

    $this->get('/')
        ->assertOk()
        ->assertSee('Dhaka City - Best Places, Guides, Prices and Local Information in Dhaka')
        ->assertSee('application/ld+json')
        ->assertSee('Browse by Category')
        ->assertSee('Featured Articles')
        ->assertSee('Trending Today')
        ->assertSee('Popular This Week')
        ->assertSee('Dhaka to Cox&#039;s Bazar Bus Ticket Price', false);
});

test('post page renders dynamic meta tags and article schema', function () {
    $post = createPublishedPost();

    $this->get(route('posts.show', $post))
        ->assertOk()
        ->assertSee('<meta name="description" content="Learn bus ticket price ranges, coach options, travel time, and booking tips.">', false)
        ->assertSee('<meta name="twitter:title" content="Dhaka to Cox&#039;s Bazar Bus Ticket Price - Latest Travel Guide">', false)
        ->assertSee('"@type": "Article"', false)
        ->assertSee('"@type": "FAQPage"', false)
        ->assertSee('"@type": "BreadcrumbList"', false)
        ->assertSee('Frequently Asked Questions')
        ->assertSee($post->title);
});

test('unpublished posts are not public', function () {
    $post = createPublishedPost(['published_at' => now()->addDay()]);

    $this->get(route('posts.show', $post))->assertNotFound();
});

test('category and search pages list published posts', function () {
    $post = createPublishedPost();

    $this->get(route('categories.show', $post->category))
        ->assertOk()
        ->assertSee($post->title);

    $this->get(route('search', ['q' => 'Cox']))
        ->assertOk()
        ->assertSee($post->title);
});

test('search finds posts by title category and tags with pagination', function () {
    $post = createPublishedPost(['title' => 'Budget Hotels in Gulshan']);
    $tag = Tag::query()->create([
        'name' => 'Hotel Guides',
        'slug' => 'hotel-guides',
    ]);

    $post->tags()->attach($tag);

    $this->get(route('search', ['q' => 'Budget']))
        ->assertOk()
        ->assertSee($post->title);

    $this->get(route('search', ['q' => 'Travel']))
        ->assertOk()
        ->assertSee($post->title);

    $this->get(route('search', ['q' => 'Hotel Guides']))
        ->assertOk()
        ->assertSee($post->title);

    foreach (range(1, 13) as $index) {
        createPublishedPost([
            'title' => 'Paged Result '.$index,
            'slug' => 'paged-result-'.$index,
            'published_at' => now()->subMinutes($index),
        ]);
    }

    $this->get(route('search', ['q' => 'Paged']))
        ->assertOk()
        ->assertSee('Paged Result 1')
        ->assertSee('13 results for "Paged"', false)
        ->assertSee('Pagination Navigation');
});

test('posts can be attached to tags', function () {
    $post = createPublishedPost();
    $tag = Tag::query()->create([
        'name' => 'Bus Tickets',
        'slug' => 'bus-tickets',
    ]);

    $post->tags()->attach($tag);

    expect($post->fresh()->tags)->toHaveCount(1)
        ->and($tag->fresh()->posts)->toHaveCount(1);
});

test('sitemap and robots expose crawl directives', function () {
    $post = createPublishedPost();

    $this->get(route('sitemap'))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml')
        ->assertSee($post->url());

    $this->get(route('robots'))
        ->assertOk()
        ->assertSee('User-agent: *')
        ->assertSee(route('sitemap'));
});
