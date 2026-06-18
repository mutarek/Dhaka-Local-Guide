<?php

namespace App\Policies;

use App\Models\Advertiser;
use App\Models\User;
use App\Support\Permissions;

class AdvertiserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAdminPermission(Permissions::MANAGE_ADVERTISERS);
    }

    public function view(User $user, Advertiser $advertiser): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Advertiser $advertiser): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, Advertiser $advertiser): bool
    {
        return $this->viewAny($user);
    }
}
