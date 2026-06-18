<?php

namespace App\Policies;

use App\Models\AdPackage;
use App\Models\User;
use App\Support\Permissions;

class AdPackagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAdminPermission(Permissions::MANAGE_AD_PACKAGES);
    }

    public function view(User $user, AdPackage $adPackage): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, AdPackage $adPackage): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, AdPackage $adPackage): bool
    {
        return $this->viewAny($user);
    }
}
