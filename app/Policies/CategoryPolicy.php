<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use App\Support\Permissions;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAdminPermission(Permissions::MANAGE_CATEGORIES);
    }

    public function view(User $user, Category $category): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Category $category): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, Category $category): bool
    {
        return $this->viewAny($user);
    }
}
