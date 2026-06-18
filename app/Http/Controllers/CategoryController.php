<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Support\SeoData;
use App\Support\SeoSchema;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Category $category): View
    {
        abort_unless($category->is_active, 404);

        $posts = $category->posts()
            ->with(['category', 'tags'])
            ->published()
            ->latest('published_at')
            ->paginate(12);

        $seo = new SeoData(
            title: $category->meta_title ?: $category->name.' Guides in Dhaka',
            description: $category->meta_description ?: ($category->description ?: 'Read practical Dhaka guides and recommendations for '.$category->name.'.'),
            canonical: route('categories.show', $category),
            schema: SeoSchema::graph([
                SeoSchema::categoryPage($category),
                SeoSchema::breadcrumbs([
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => $category->name, 'url' => route('categories.show', $category)],
                ]),
            ]),
        );

        return view('categories.show', compact('category', 'posts', 'seo'));
    }
}
