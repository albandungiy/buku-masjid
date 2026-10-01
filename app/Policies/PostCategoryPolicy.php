<?php

namespace App\Policies;

use App\Models\PostCategory;
use App\User;

class PostCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, PostCategory $postCategory): bool
    {
        return true;
    }

    public function create(User $user, PostCategory $postCategory): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }

    public function update(User $user, PostCategory $postCategory): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }

    public function delete(User $user, PostCategory $postCategory): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }
}
