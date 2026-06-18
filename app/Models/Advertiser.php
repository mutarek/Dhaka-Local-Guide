<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Advertiser extends Model
{
    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'name',
        'company_name',
        'email',
        'phone',
        'website_url',
        'address',
        'notes',
        'status',
    ];

    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }
}
