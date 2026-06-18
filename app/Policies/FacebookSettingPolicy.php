<?php

namespace App\Policies;

use App\Models\FacebookSetting;
use App\Models\User;
use App\Support\Permissions;

class FacebookSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAdminPermission(Permissions::MANAGE_SETTINGS);
    }

    public function view(User $user, FacebookSetting $facebookSetting): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user) && FacebookSetting::query()->count() === 0;
    }

    public function update(User $user, FacebookSetting $facebookSetting): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, FacebookSetting $facebookSetting): bool
    {
        return false;
    }
}
