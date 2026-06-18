<?php

namespace App\Support;

class Permissions
{
    public const MANAGE_USERS = 'manage users';

    public const MANAGE_SETTINGS = 'manage settings';

    public const MANAGE_POSTS = 'manage posts';

    public const MANAGE_OWN_POSTS = 'manage own posts';

    public const PUBLISH_POSTS = 'publish posts';

    public const MANAGE_CATEGORIES = 'manage categories';

    public const MANAGE_TAGS = 'manage tags';

    public const MANAGE_ADVERTISERS = 'manage advertisers';

    public const MANAGE_AD_PACKAGES = 'manage ad packages';

    public const MANAGE_ADVERTISEMENTS = 'manage advertisements';

    public const VIEW_AUDIT_LOGS = 'view audit logs';

    public static function all(): array
    {
        return [
            self::MANAGE_USERS,
            self::MANAGE_SETTINGS,
            self::MANAGE_POSTS,
            self::MANAGE_OWN_POSTS,
            self::PUBLISH_POSTS,
            self::MANAGE_CATEGORIES,
            self::MANAGE_TAGS,
            self::MANAGE_ADVERTISERS,
            self::MANAGE_AD_PACKAGES,
            self::MANAGE_ADVERTISEMENTS,
            self::VIEW_AUDIT_LOGS,
        ];
    }
}
