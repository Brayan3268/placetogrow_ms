<?php

namespace App\Policies;

use App\Constants\Permissions;
use App\Models\User;

class SitePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SITES_INDEX);
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SITES_SHOW);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SITES_CREATE);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SITES_STORE);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SITES_DESTROY);
    }
}
