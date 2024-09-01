<?php

namespace App\Policies;

use App\Constants\Permissions;
use App\Models\User;

class SuscriptionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SUSCRIPTION_INDEX);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SUSCRIPTION_CREATE);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SUSCRIPTION_STORE);
    }
}
