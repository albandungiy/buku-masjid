<?php

namespace App\Policies;

use App\Models\Event;
use App\User;

class EventPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Event $event): bool
    {
        return true;
    }

    public function create(User $user, Event $event): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_CHAIRMAN, User::ROLE_SECRETARY]);
    }

    public function update(User $user, Event $event): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_CHAIRMAN, User::ROLE_SECRETARY]);
    }

    public function delete(User $user, Event $event): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_CHAIRMAN, User::ROLE_SECRETARY]);
    }
}
