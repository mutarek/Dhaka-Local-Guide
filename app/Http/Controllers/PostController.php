<?php

namespace App\Http\Controllers;

use App\Models\AdPackage;
use App\Models\Category;
use App\Models\Post;
use App\Support\SeoData;
use App\Support\SeoSchema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function show(Post $post): View
    {
        abort_unless($post->status === Post::STATUS_PUBLISHED && $post->published_at && $post->published_at->isPast(), 404);

        return $this->renderPost($post, true);
    }

    public function preview(Post $post): View
    {
        return $this->renderPost($post, false);
    }

    private function renderPost(Post $post, bool $countView): View
    {
        $post->load(['category', 'tags']);

        if ($countView) {
            $post->increment('views_count');
        }

        $relatedPosts = Post::query()
            ->with(['category', 'tags'])
            ->published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->latest('published_at')
            ->take(4)
            ->get();

        $popularArticles = Post::query()
            ->with(['category', 'tags'])
            ->published()
            ->where('id', '!=', $post->id)
            ->withCount('tags')
            ->orderByDesc('tags_count')
            ->latest('published_at')
            ->take(5)
            ->get();

        $categories = Category::query()
            ->active()
            ->withCount(['posts' => fn ($query) => $query->published()])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(8)
            ->get();

        [$content, $tableOfContents] = $this->prepareContent($post);

        $seo = new SeoData(
            title: $post->meta_title ?: $post->title,
            description: $post->meta_description ?: $post->excerpt,
            canonical: $post->canonical_url ?: $post->url(),
            image: $post->featuredImageUrl(),
            type: 'article',
            schema: SeoSchema::graph([
                SeoSchema::article($post),
                SeoSchema::faq($post),
                SeoSchema::breadcrumbs([
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => $post->category->name, 'url' => route('categories.show', $post->category)],
                    ['name' => $post->title, 'url' => $post->url()],
                ]),
                $post->schema,
            ]),
        );

        return view('posts.show', compact('post', 'relatedPosts', 'popularArticles', 'categories', 'content', 'tableOfContents', 'seo'));
    }

    private function prepareContent(Post $post): array
    {
        $content = trim($post->content);

        if (! str_contains($content, '<')) {
            $content = collect(preg_split('/\R{2,}/', $content))
                ->filter()
                ->map(fn (string $paragraph): string => '<p>'.e($paragraph).'</p>')
                ->implode('');
        }

        $tableOfContents = [];

        $content = preg_replace_callback('/<h([23])([^>]*)>(.*?)<\/h\1>/i', function (array $matches) use (&$tableOfContents): string {
            $text = trim(strip_tags($matches[3]));
            $id = Str::slug($text);

            if ($id === '') {
                return $matches[0];
            }

            $tableOfContents[] = [
                'id' => $id,
                'title' => $text,
                'level' => (int) $matches[1],
            ];

            return '<h'.$matches[1].' id="'.$id.'"'.$matches[2].'>'.$matches[3].'</h'.$matches[1].'>';
        }, $content);

        if ($tableOfContents === []) {
            $tableOfContents[] = ['id' => 'overview', 'title' => 'Overview', 'level' => 2];
        }

        if ($post->hasFaqs()) {
            $tableOfContents[] = ['id' => 'faqs', 'title' => 'Frequently Asked Questions', 'level' => 2];
        }

        $content = $this->insertAdAfterParagraph($content, AdPackage::PLACEMENT_AFTER_FIRST_PARAGRAPH, $post, 1);
        $content = $this->insertAdAfterParagraph($content, AdPackage::PLACEMENT_MIDDLE_CONTENT, $post);

        return [$content, $tableOfContents];
    }

    private function insertAdAfterParagraph(string $content, string $position, Post $post, ?int $paragraphNumber = null): string
    {
        preg_match_all('/<\/p>/i', $content, $matches, PREG_OFFSET_CAPTURE);

        if ($matches[0] === []) {
            return $content;
        }

        $totalParagraphs = count($matches[0]);

        if ($position === AdPackage::PLACEMENT_MIDDLE_CONTENT && $totalParagraphs < 3) {
            return $content;
        }

        $paragraphNumber ??= (int) ceil($totalParagraphs / 2);
        $paragraphNumber = max(1, min($paragraphNumber, $totalParagraphs));
        $match = $matches[0][$paragraphNumber - 1];
        $insertAt = $match[1] + strlen($match[0]);
        $adHtml = $this->renderInlineAd($position, $post);

        if ($adHtml === '') {
            return $content;
        }

        return substr($content, 0, $insertAt).$adHtml.substr($content, $insertAt);
    }

    private function renderInlineAd(string $position, Post $post): string
    {
        return trim(Blade::render(
            '<x-ad-placement :position="$position" :post="$post" />',
            compact('position', 'post')
        ));
    }
}
