<?php

namespace App\Policies;

use App\Constants\Permissions;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::USERS_INDEX);
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::USERS_SHOW);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::USERS_CREATE);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::USERS_STORE);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::USERS_DESTROY);
    }
}
