<?php

namespace App\Policies;

use App\Models\Donation;
use App\User;

class DonationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user, Donation $donation): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE]);
    }

    public function view(User $user, Donation $donation): bool
    {
        return true;
    }

    public function update(User $user, Donation $donation): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE])
            && $donation->status_id == Donation::STATUS_PENDING;
    }

    // Book-scoping (only the book's manager, or an admin, may confirm) is enforced
    // separately via BookPolicy::manageDonations — see Donations\ConfirmRequest::authorize().
    public function confirm(User $user, Donation $donation): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE])
            && $donation->status_id == Donation::STATUS_PENDING;
    }

    public function delete(User $user, Donation $donation): bool
    {
        return in_array($user->role_id, [User::ROLE_ADMIN, User::ROLE_FINANCE])
            && $donation->status_id == Donation::STATUS_PENDING;
    }
}
