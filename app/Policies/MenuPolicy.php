<?php

namespace App\Policies;

use App\Models\Menu;
use App\User;

class MenuPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }

    public function view(User $user, Menu $menu): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }

    public function create(User $user, Menu $menu): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }

    public function update(User $user, Menu $menu): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }

    public function delete(User $user, Menu $menu): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }

    // Ability for the drag-and-drop reorder endpoint (Fase 4) — not tied to one Menu row.
    public function reorder(User $user): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }
}
