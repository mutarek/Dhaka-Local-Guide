<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Post;

class SeoSchema
{
    public static function webSite(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => route('home').'#website',
            'name' => config('app.name'),
            'url' => route('home'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => route('search').'?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    public static function article(Post $post): array
    {
        $schema = [
            '@type' => 'Article',
            '@id' => $post->url().'#article',
            'headline' => $post->title,
            'description' => $post->excerpt,
            'datePublished' => $post->published_at?->toAtomString(),
            'dateModified' => $post->updated_at?->toAtomString(),
            'mainEntityOfPage' => $post->url(),
            'articleSection' => $post->category->name,
            'keywords' => $post->tags->pluck('name')->implode(', '),
            'author' => self::organization(),
            'publisher' => self::organization(),
        ];

        if ($post->featuredImageUrl()) {
            $schema['image'] = [$post->featuredImageUrl()];
        }

        return $schema;
    }

    public static function categoryPage(Category $category): array
    {
        return [
            '@type' => 'CollectionPage',
            '@id' => route('categories.show', $category).'#collection',
            'name' => $category->name,
            'description' => $category->description,
            'url' => route('categories.show', $category),
        ];
    }

    public static function faq(Post $post): ?array
    {
        $questions = collect($post->faqs)
            ->filter(fn (array $faq): bool => filled($faq['question'] ?? null) && filled($faq['answer'] ?? null))
            ->map(fn (array $faq): array => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ])
            ->values()
            ->all();

        if ($questions === []) {
            return null;
        }

        return [
            '@type' => 'FAQPage',
            '@id' => $post->url().'#faq',
            'mainEntity' => $questions,
        ];
    }

    public static function breadcrumbs(array $items): array
    {
        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)
                ->values()
                ->map(fn (array $item, int $index): array => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['name'],
                    'item' => $item['url'],
                ])
                ->all(),
        ];
    }

    public static function graph(array $schemas): array
    {
        return [
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter($schemas)),
        ];
    }

    private static function organization(): array
    {
        return [
            '@type' => 'Organization',
            '@id' => route('home').'#organization',
            'name' => config('app.name'),
            'url' => route('home'),
        ];
    }
}
