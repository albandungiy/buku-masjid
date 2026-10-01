<?php

namespace App\Policies;

use App\Models\Post;
use App\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Post $post): bool
    {
        return true;
    }

    public function create(User $user, Post $post): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }

    // Editing content/title of a reserved homepage-section page IS allowed — only its
    // slug is protected, enforced in Posts\UpdateRequest (Fase 3), not here.
    public function update(User $user, Post $post): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }

    // Reserved homepage-section pages (config('cms.reserved_page_slugs')) can never be
    // deleted by anyone — the homepage depends on them always existing (docs/cms.md §5.2).
    public function delete(User $user, Post $post): bool
    {
        if ($post->isReservedPage()) {
            return false;
        }

        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }
}
