<?php

namespace App\Policies;

use App\Models\Advertisement;
use App\Models\User;
use App\Support\Permissions;

class AdvertisementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAdminPermission(Permissions::MANAGE_ADVERTISEMENTS);
    }

    public function view(User $user, Advertisement $advertisement): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Advertisement $advertisement): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, Advertisement $advertisement): bool
    {
        return $this->viewAny($user);
    }
}
