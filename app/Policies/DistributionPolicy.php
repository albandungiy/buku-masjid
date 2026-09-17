<?php

namespace App\Policies;

use App\Models\Distribution;
use App\User;

class DistributionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user, Distribution $distribution): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE]);
    }

    public function view(User $user, Distribution $distribution): bool
    {
        return true;
    }

    public function update(User $user, Distribution $distribution): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE])
            && $distribution->status_id == Distribution::STATUS_PENDING;
    }

    // Book-scoping (only the book's manager, or an admin, may approve) is enforced
    // separately via BookPolicy::manageDistributions — see Distributions\ApproveRequest::authorize().
    public function approve(User $user, Distribution $distribution): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE])
            && $distribution->status_id == Distribution::STATUS_PENDING;
    }

    // Book-scoping is enforced separately via BookPolicy::manageDistributions — see
    // Distributions\RejectRequest::authorize().
    public function reject(User $user, Distribution $distribution): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE])
            && $distribution->status_id == Distribution::STATUS_PENDING;
    }

    public function delete(User $user, Distribution $distribution): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE])
            && $distribution->status_id == Distribution::STATUS_PENDING;
    }
}
