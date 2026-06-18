<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdPackage extends Model
{
    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const PLACEMENT_BEFORE_CONTENT = 'before_content';

    public const PLACEMENT_AFTER_FIRST_PARAGRAPH = 'after_first_paragraph';

    public const PLACEMENT_MIDDLE_CONTENT = 'middle_content';

    public const PLACEMENT_AFTER_CONTENT = 'after_content';

    public const PLACEMENT_SIDEBAR = 'sidebar';

    public const PLACEMENT_MOBILE_STICKY_BOTTOM = 'mobile_sticky_bottom';

    public const PLACEMENT_CATEGORY_PAGE = 'category_page';

    public const PLACEMENT_HOMEPAGE_BANNER = 'homepage_banner';

    protected $fillable = [
        'name',
        'slug',
        'duration_days',
        'price',
        'placement_type',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'duration_days' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    public static function placementOptions(): array
    {
        return [
            self::PLACEMENT_BEFORE_CONTENT => 'Before blog content',
            self::PLACEMENT_AFTER_FIRST_PARAGRAPH => 'After first paragraph',
            self::PLACEMENT_MIDDLE_CONTENT => 'Middle of article',
            self::PLACEMENT_AFTER_CONTENT => 'After blog content',
            self::PLACEMENT_SIDEBAR => 'Sidebar',
            self::PLACEMENT_MOBILE_STICKY_BOTTOM => 'Mobile sticky bottom ad',
            self::PLACEMENT_CATEGORY_PAGE => 'Category page ad',
            self::PLACEMENT_HOMEPAGE_BANNER => 'Homepage banner ad',
        ];
    }

    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }
}
