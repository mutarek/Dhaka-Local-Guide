<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use App\Support\Permissions;

class TagPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAdminPermission(Permissions::MANAGE_TAGS);
    }

    public function view(User $user, Tag $tag): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Tag $tag): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, Tag $tag): bool
    {
        return $this->viewAny($user);
    }
}
