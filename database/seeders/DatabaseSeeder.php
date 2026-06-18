<?php

namespace Database\Seeders;

use App\Models\AdPackage;
use App\Models\Category;
use App\Models\FacebookSetting;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $editor = User::factory()->create([
            'name' => 'Dhaka City Editor',
            'email' => 'editor@dhakacity.test',
            'is_admin' => true,
            'is_active' => true,
            'role' => User::ROLE_SUPER_ADMIN,
            'email_verified_at' => now(),
        ]);

        $this->call(RolePermissionSeeder::class);
        $editor->syncPrimaryRole();

        FacebookSetting::query()->firstOrCreate([], [
            'auto_share_enabled' => false,
        ]);

        collect([
            [
                'name' => '7 Days Sidebar Ad',
                'slug' => '7-days-sidebar-ad',
                'duration_days' => 7,
                'price' => 3500,
                'placement_type' => AdPackage::PLACEMENT_SIDEBAR,
                'description' => 'Sidebar ad placement for one week.',
                'status' => AdPackage::STATUS_ACTIVE,
            ],
            [
                'name' => '30 Days In-Article Ad',
                'slug' => '30-days-in-article-ad',
                'duration_days' => 30,
                'price' => 15000,
                'placement_type' => AdPackage::PLACEMENT_AFTER_FIRST_PARAGRAPH,
                'description' => 'In-article ad placement after the first paragraph for 30 days.',
                'status' => AdPackage::STATUS_ACTIVE,
            ],
            [
                'name' => '1 Year Homepage Banner',
                'slug' => '1-year-homepage-banner',
                'duration_days' => 365,
                'price' => 120000,
                'placement_type' => AdPackage::PLACEMENT_HOMEPAGE_BANNER,
                'description' => 'Homepage banner placement for one year.',
                'status' => AdPackage::STATUS_ACTIVE,
            ],
        ])->each(fn (array $package) => AdPackage::query()->updateOrCreate(
            ['slug' => $package['slug']],
            $package
        ));

        $categories = collect([
            [
                'name' => 'Food & Nightlife',
                'slug' => 'food-nightlife',
                'description' => 'Restaurants, cafes, rooftop spots, and nightlife guides across Dhaka.',
                'sort_order' => 10,
            ],
            [
                'name' => 'Healthcare',
                'slug' => 'healthcare',
                'description' => 'Hospital and clinic guides for common healthcare decisions in Dhaka.',
                'sort_order' => 20,
            ],
            [
                'name' => 'Travel',
                'slug' => 'travel',
                'description' => 'Dhaka travel routes, ticket prices, hotels, and weekend trip planning.',
                'sort_order' => 30,
            ],
        ])->mapWithKeys(fn (array $category) => [
            $category['slug'] => Category::query()->updateOrCreate(
                ['slug' => $category['slug']],
                $category
            ),
        ]);

        $tags = collect([
            ['name' => 'Restaurants', 'slug' => 'restaurants'],
            ['name' => 'Bars', 'slug' => 'bars'],
            ['name' => 'Hospitals', 'slug' => 'hospitals'],
            ['name' => 'Eye Care', 'slug' => 'eye-care'],
            ['name' => 'Bus Tickets', 'slug' => 'bus-tickets'],
            ['name' => "Cox's Bazar", 'slug' => 'coxs-bazar'],
        ])->mapWithKeys(fn (array $tag) => [
            $tag['slug'] => Tag::query()->updateOrCreate(
                ['slug' => $tag['slug']],
                $tag
            ),
        ]);

        $posts = [
            [
                'category' => 'food-nightlife',
                'tags' => ['restaurants'],
                'title' => 'Best Restaurants in Dhaka',
                'slug' => 'best-restaurants-in-dhaka',
                'excerpt' => 'A practical shortlist of Dhaka restaurants for family dinners, business meals, dates, and casual meetups.',
                'content' => "Dhaka has restaurants for almost every budget and occasion. For a useful shortlist, compare location, parking, cuisine, service consistency, and how easy it is to reserve a table.\n\nPopular dining areas include Gulshan, Banani, Dhanmondi, Uttara, and Bashundhara. Gulshan and Banani usually work well for business meals and upscale dinners, while Dhanmondi and Uttara have strong casual dining options.\n\nBefore choosing a restaurant, check recent reviews, confirm opening hours, and call ahead during weekends or public holidays.",
                'meta_title' => 'Best Restaurants in Dhaka - Dhaka City Guide',
                'meta_description' => 'Find the best restaurants in Dhaka by area, occasion, budget, and practical dining needs.',
                'faqs' => [
                    [
                        'question' => 'Which Dhaka areas are best for restaurants?',
                        'answer' => 'Gulshan, Banani, Dhanmondi, Uttara, and Bashundhara are useful areas to compare for restaurants.',
                    ],
                ],
            ],
            [
                'category' => 'food-nightlife',
                'tags' => ['bars'],
                'title' => 'Best Bars in Dhaka',
                'slug' => 'best-bars-in-dhaka',
                'excerpt' => 'Where to look for Dhaka bars, lounges, and hotel venues with practical notes on location and planning.',
                'content' => "Dhaka nightlife is concentrated around hotel bars, private clubs, lounges, and selected restaurant venues. Availability and entry rules can change, so always verify before visiting.\n\nGulshan and Banani are the most common areas for upscale lounges and hotel venues. Travel time, dress code, reservation policy, and closing hours are worth checking in advance.\n\nFor a smooth night out, plan transport early and confirm whether the venue allows walk-ins.",
                'meta_title' => 'Best Bars in Dhaka - Lounges and Nightlife Guide',
                'meta_description' => 'A practical guide to finding bars, lounges, and nightlife venues in Dhaka.',
            ],
            [
                'category' => 'healthcare',
                'tags' => ['hospitals', 'eye-care'],
                'title' => 'Best Eye Hospitals in Dhaka',
                'slug' => 'best-eye-hospitals-in-dhaka',
                'excerpt' => 'How to compare Dhaka eye hospitals for cataract care, retina treatment, LASIK consultation, and emergency eye issues.',
                'content' => "Choosing an eye hospital in Dhaka depends on the service you need. Cataract surgery, retina care, glaucoma management, pediatric eye care, and LASIK consultations may require different specialists.\n\nLook for consultant availability, diagnostic equipment, surgery facilities, patient flow, and emergency support. It is also useful to ask whether follow-up appointments are handled by the same consultant.\n\nFor urgent pain, sudden vision loss, or eye injury, seek emergency care immediately rather than waiting for a routine appointment.",
                'meta_title' => 'Best Eye Hospitals in Dhaka - Patient Guide',
                'meta_description' => 'Compare eye hospitals in Dhaka for cataract, retina, glaucoma, LASIK, pediatric care, and urgent eye treatment.',
            ],
            [
                'category' => 'travel',
                'tags' => ['bus-tickets', 'coxs-bazar'],
                'title' => "Dhaka to Cox's Bazar Bus Ticket Price",
                'slug' => 'dhaka-to-coxs-bazar-bus-ticket-price',
                'excerpt' => "A clear guide to Dhaka to Cox's Bazar bus ticket price ranges, coach types, route timing, and booking tips.",
                'content' => "Dhaka to Cox's Bazar bus ticket prices usually vary by operator, coach type, seat class, season, and booking date. Non-AC, AC, sleeper, and business class coaches can have very different prices.\n\nThe route often takes around 10 to 12 hours, but holiday traffic can make the journey longer. Night coaches are popular because they save daytime travel hours.\n\nBefore booking, compare operator counters, online ticketing platforms, cancellation terms, boarding points, and arrival location in Cox's Bazar.",
                'meta_title' => "Dhaka to Cox's Bazar Bus Ticket Price - Latest Travel Guide",
                'meta_description' => "Learn Dhaka to Cox's Bazar bus ticket price ranges, coach options, travel time, boarding points, and booking tips.",
                'faqs' => [
                    [
                        'question' => "How long does Dhaka to Cox's Bazar take by bus?",
                        'answer' => 'The journey often takes around 10 to 12 hours, but holiday traffic can make it longer.',
                    ],
                    [
                        'question' => 'Which bus type should I compare before booking?',
                        'answer' => 'Compare non-AC, AC, sleeper, and business class coaches before booking.',
                    ],
                ],
            ],
        ];

        foreach ($posts as $index => $post) {
            $savedPost = Post::query()->updateOrCreate(
                ['slug' => $post['slug']],
                [
                    'category_id' => $categories[$post['category']]->id,
                    'author_id' => $editor->id,
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'content' => $post['content'],
                    'meta_title' => $post['meta_title'],
                    'meta_description' => $post['meta_description'],
                    'faqs' => $post['faqs'] ?? null,
                    'status' => Post::STATUS_PUBLISHED,
                    'views_count' => 1000 + ($index * 275),
                    'is_featured' => $index < 2,
                    'is_trending' => $index !== 2,
                    'focus_keyword' => $post['tags'][0] ?? null,
                    'image_alt' => $post['title'],
                    'facebook_share_status' => Post::FACEBOOK_NOT_SHARED,
                    'published_at' => Carbon::now()->subDays(count($posts) - $index),
                ]
            );

            $savedPost->tags()->sync(
                collect($post['tags'])
                    ->map(fn (string $slug) => $tags[$slug]->id)
                    ->all()
            );
        }
    }
}
