<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class Advertisement extends Model
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_EXPIRED = 'expired';

    public const STATUS_PAUSED = 'paused';

    public const PAYMENT_UNPAID = 'unpaid';

    public const PAYMENT_PARTIAL = 'partial';

    public const PAYMENT_PAID = 'paid';

    public const TARGET_ALL_POSTS = 'all_posts';

    public const TARGET_CATEGORY = 'category';

    public const TARGET_SPECIFIC_POSTS = 'specific_posts';

    public const TARGET_HOMEPAGE = 'homepage';

    protected $fillable = [
        'advertiser_id',
        'ad_package_id',
        'title',
        'image',
        'mobile_image',
        'destination_url',
        'placement_position',
        'target_type',
        'category_id',
        'start_date',
        'end_date',
        'status',
        'amount_paid',
        'payment_status',
        'priority',
        'impressions_count',
        'clicks_count',
        'open_in_new_tab',
        'nofollow',
        'sponsored_label',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'amount_paid' => 'decimal:2',
            'priority' => 'integer',
            'impressions_count' => 'integer',
            'clicks_count' => 'integer',
            'open_in_new_tab' => 'boolean',
            'nofollow' => 'boolean',
            'sponsored_label' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Advertisement $advertisement) {
            $advertisement->created_by ??= auth()->id();
        });

        static::saving(function (Advertisement $advertisement) {
            if (! self::hasSafeDestinationUrl($advertisement->destination_url)) {
                throw ValidationException::withMessages([
                    'destination_url' => 'Advertisement destination URL must be a valid http or https URL.',
                ]);
            }
        });
    }

    public static function targetOptions(): array
    {
        return [
            self::TARGET_ALL_POSTS => 'All posts',
            self::TARGET_CATEGORY => 'Category',
            self::TARGET_SPECIFIC_POSTS => 'Specific posts',
            self::TARGET_HOMEPAGE => 'Homepage',
        ];
    }

    public function advertiser(): BelongsTo
    {
        return $this->belongsTo(Advertiser::class);
    }

    public function adPackage(): BelongsTo
    {
        return $this->belongsTo(AdPackage::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_ACTIVE)
            ->whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today());
    }

    public function imageUrl(): string
    {
        return $this->assetUrl($this->image);
    }

    public function mobileImageUrl(): string
    {
        return $this->assetUrl($this->mobile_image ?: $this->image);
    }

    public function relAttribute(): string
    {
        return collect([
            'sponsored',
            $this->nofollow ? 'nofollow' : null,
            $this->open_in_new_tab ? 'noopener' : null,
        ])->filter()->unique()->implode(' ');
    }

    public static function hasSafeDestinationUrl(?string $url): bool
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        return in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true);
    }

    private function assetUrl(?string $path): string
    {
        if (! $path) {
            return '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
