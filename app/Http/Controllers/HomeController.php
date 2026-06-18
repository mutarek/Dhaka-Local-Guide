<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Support\SeoData;
use App\Support\SeoSchema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $latestPosts = Post::query()
            ->with(['category', 'tags'])
            ->published()
            ->latest('published_at')
            ->take(6)
            ->get();

        $popularPosts = Post::query()
            ->with(['category', 'tags'])
            ->published()
            ->withCount('tags')
            ->orderByDesc('tags_count')
            ->latest('updated_at')
            ->take(4)
            ->get();

        $categories = Category::query()
            ->active()
            ->withCount(['posts' => fn ($query) => $query->published()])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $seo = new SeoData(
            title: 'Dhaka City - Best Places, Guides, Prices and Local Information in Dhaka',
            description: 'Dhaka City publishes practical guides to Dhaka restaurants, bars, hospitals, hotels, transport prices, travel routes, and local services.',
            canonical: route('home'),
            schema: SeoSchema::graph([
                SeoSchema::webSite(),
            ]),
        );

        return view('home', compact('latestPosts', 'popularPosts', 'categories', 'seo'));
    }
}
