<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Support\SeoData;
use App\Support\SeoSchema;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $query = trim((string) $request->query('q'));

        $posts = Post::query()
            ->with(['category', 'tags'])
            ->published()
            ->when($query !== '', fn ($builder) => $builder->search($query))
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        $seo = new SeoData(
            title: $query === '' ? 'Search Dhaka City' : 'Search results for '.$query,
            description: 'Search Dhaka City guides for places, prices, hospitals, hotels, restaurants, transport, and travel information in Dhaka.',
            canonical: route('search'),
            schema: SeoSchema::graph([
                SeoSchema::breadcrumbs([
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Search', 'url' => route('search')],
                ]),
            ]),
        );

        return view('search', compact('posts', 'query', 'seo'));
    }
}
