<?php

namespace App\Models;

use App\Events\PostPublished;
use App\Services\HtmlSanitizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_SCHEDULED = 'scheduled';

    public const FACEBOOK_NOT_SHARED = 'not_shared';

    public const FACEBOOK_SHARED = 'shared';

    public const FACEBOOK_FAILED = 'failed';

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'gallery_images',
        'image_alt',
        'image_caption',
        'meta_title',
        'meta_description',
        'canonical_url',
        'focus_keyword',
        'status',
        'views_count',
        'reading_time',
        'is_featured',
        'is_trending',
        'faqs',
        'schema',
        'auto_share_to_facebook',
        'facebook_share_status',
        'facebook_post_id',
        'facebook_share_error',
        'facebook_shared_at',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'faqs' => 'array',
            'gallery_images' => 'array',
            'schema' => 'array',
            'is_featured' => 'boolean',
            'is_trending' => 'boolean',
            'auto_share_to_facebook' => 'boolean',
            'published_at' => 'datetime',
            'facebook_shared_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            $post->content = app(HtmlSanitizer::class)->sanitize($post->content);
            $post->reading_time = $post->calculateReadingTime();

            if ($post->status === self::STATUS_PUBLISHED && ! $post->published_at) {
                $post->published_at = Carbon::now();
            }
        });

        static::updated(function (Post $post) {
            if ($post->wasChanged('status') && $post->status === self::STATUS_PUBLISHED) {
                event(new PostPublished($post));
            }
        });

        static::created(function (Post $post) {
            if ($post->status === self::STATUS_PUBLISHED) {
                event(new PostPublished($post));
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function advertisements(): BelongsToMany
    {
        return $this->belongsToMany(Advertisement::class)->withTimestamps();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', Carbon::now());
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        $like = '%'.str_replace(['%', '_'], ['\%', '\_'], $term).'%';

        return $query->where(function (Builder $query) use ($like) {
            $query
                ->where('title', 'like', $like)
                ->orWhere('excerpt', 'like', $like)
                ->orWhere('content', 'like', $like)
                ->orWhereHas('category', function (Builder $query) use ($like) {
                    $query
                        ->where('name', 'like', $like)
                        ->orWhere('slug', 'like', $like);
                })
                ->orWhereHas('tags', function (Builder $query) use ($like) {
                    $query
                        ->where('name', 'like', $like)
                        ->orWhere('slug', 'like', $like);
                });
        });
    }

    public function url(): string
    {
        return route('posts.show', $this);
    }

    public function featuredImageUrl(): ?string
    {
        if (! $this->featured_image) {
            return null;
        }

        if (str_starts_with($this->featured_image, 'http://') || str_starts_with($this->featured_image, 'https://')) {
            return $this->featured_image;
        }

        return Storage::disk('public')->url($this->featured_image);
    }

    public function displayImageUrl(): string
    {
        return $this->featuredImageUrl()
            ?: 'https://images.unsplash.com/photo-1444723121867-7a241cacace9?auto=format&fit=crop&w=1200&q=80';
    }

    public function readingTime(): int
    {
        return $this->reading_time ?: $this->calculateReadingTime();
    }

    public function calculateReadingTime(): int
    {
        $words = str_word_count(strip_tags((string) $this->content));

        return max(1, (int) ceil($words / 220));
    }

    public function seoScore(): int
    {
        $checks = [
            str($this->meta_title)->length() >= 50 && str($this->meta_title)->length() <= 60,
            str($this->meta_description)->length() >= 140 && str($this->meta_description)->length() <= 160,
            filled($this->focus_keyword) && str($this->title)->lower()->contains(str($this->focus_keyword)->lower()),
            filled($this->focus_keyword) && str(strip_tags($this->content))->lower()->contains(str($this->focus_keyword)->lower()),
            preg_match('/<h[23][^>]*>/i', (string) $this->content) === 1,
            filled($this->image_alt),
            preg_match('/href=["\']\//i', (string) $this->content) === 1,
            $this->hasFaqs(),
        ];

        return (int) round((collect($checks)->filter()->count() / count($checks)) * 100);
    }

    public function hasFaqs(): bool
    {
        return collect($this->faqs)
            ->filter(fn (array $faq): bool => filled($faq['question'] ?? null) && filled($faq['answer'] ?? null))
            ->isNotEmpty();
    }
}
