<?php

namespace App\Policies;

use App\Models\RunningText;
use App\User;

class RunningTextPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }

    public function view(User $user, RunningText $runningText): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }

    public function create(User $user, RunningText $runningText): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }

    public function update(User $user, RunningText $runningText): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }

    public function delete(User $user, RunningText $runningText): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }

    public function reorder(User $user): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_SECRETARY]);
    }
}
