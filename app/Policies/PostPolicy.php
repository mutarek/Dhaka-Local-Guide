<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Support\Permissions;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAdminPermission(Permissions::MANAGE_POSTS)
            || $user->hasAdminPermission(Permissions::MANAGE_OWN_POSTS);
    }

    public function view(User $user, Post $post): bool
    {
        return $this->update($user, $post);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Post $post): bool
    {
        if ($user->hasAdminPermission(Permissions::MANAGE_POSTS)) {
            return true;
        }

        return $user->hasAdminPermission(Permissions::MANAGE_OWN_POSTS)
            && (int) $post->author_id === (int) $user->id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->hasAdminPermission(Permissions::MANAGE_POSTS);
    }
}
