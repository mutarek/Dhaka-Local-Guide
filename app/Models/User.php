<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthentication;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'is_admin', 'is_active', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery, MustVerifyEmail
{
    public const ROLE_SUPER_ADMIN = 'super_admin';

    public const ROLE_ADMIN = 'admin';

    public const ROLE_EDITOR = 'editor';

    public const ROLE_AUTHOR = 'author';

    public const ROLE_ADS_MANAGER = 'ads_manager';

    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use HasRoles;
    use InteractsWithAppAuthentication;
    use InteractsWithAppAuthenticationRecovery;
    use Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'last_failed_login_at' => 'datetime',
            'failed_login_count' => 'integer',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin
            && $this->is_active
            && $this->hasVerifiedEmail()
            && in_array($this->role, [
                self::ROLE_SUPER_ADMIN,
                self::ROLE_ADMIN,
                self::ROLE_EDITOR,
                self::ROLE_AUTHOR,
                self::ROLE_ADS_MANAGER,
            ], true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function syncPrimaryRole(): void
    {
        if ($this->role && Schema::hasTable('roles') && Role::query()->where('name', $this->role)->exists()) {
            $this->syncRoles([$this->role]);
        }
    }

    public function hasAdminPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (Schema::hasTable('permissions') && $this->can($permission)) {
            return true;
        }

        return in_array($permission, match ($this->role) {
            self::ROLE_ADMIN => [
                \App\Support\Permissions::MANAGE_POSTS,
                \App\Support\Permissions::PUBLISH_POSTS,
                \App\Support\Permissions::MANAGE_CATEGORIES,
                \App\Support\Permissions::MANAGE_TAGS,
                \App\Support\Permissions::MANAGE_ADVERTISERS,
                \App\Support\Permissions::MANAGE_AD_PACKAGES,
                \App\Support\Permissions::MANAGE_ADVERTISEMENTS,
            ],
            self::ROLE_EDITOR => [
                \App\Support\Permissions::MANAGE_POSTS,
                \App\Support\Permissions::PUBLISH_POSTS,
                \App\Support\Permissions::MANAGE_CATEGORIES,
                \App\Support\Permissions::MANAGE_TAGS,
            ],
            self::ROLE_AUTHOR => [
                \App\Support\Permissions::MANAGE_OWN_POSTS,
            ],
            self::ROLE_ADS_MANAGER => [
                \App\Support\Permissions::MANAGE_ADVERTISERS,
                \App\Support\Permissions::MANAGE_AD_PACKAGES,
                \App\Support\Permissions::MANAGE_ADVERTISEMENTS,
            ],
            default => [],
        }, true);
    }
}
