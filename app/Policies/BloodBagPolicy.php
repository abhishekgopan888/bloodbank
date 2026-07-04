<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BloodBag;

class BloodBagPolicy
{
    public function view(User $user, BloodBag $bag): bool
    {
        return $user->role === 'admin' || $user->role === 'staff';
    }

    public function update(User $user, BloodBag $bag): bool
    {
        return $user->role === 'admin' || $user->role === 'staff';
    }

    public function delete(User $user, BloodBag $bag): bool
    {
        return $user->role === 'admin';
    }
}
