<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookSetting extends Model
{
    protected $fillable = [
        'page_id',
        'access_token',
        'auto_share_enabled',
        'last_verified_at',
    ];

    protected function casts(): array
    {
        return [
            'access_token' => 'encrypted',
            'auto_share_enabled' => 'boolean',
            'last_verified_at' => 'datetime',
        ];
    }

    public static function current(): self
    {
        return self::query()->firstOrCreate([]);
    }
}
