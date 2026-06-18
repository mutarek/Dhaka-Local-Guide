<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $categories = Category::query()->active()->latest('updated_at')->get();
        $posts = Post::query()->published()->latest('updated_at')->get();

        return response()
            ->view('sitemap', compact('categories', 'posts'))
            ->header('Content-Type', 'application/xml');
    }
}
