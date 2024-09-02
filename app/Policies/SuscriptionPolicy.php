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

    public function view(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SUSCRIPTION_SHOW);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SUSCRIPTION_CREATE);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SUSCRIPTION_STORE);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SUSCRIPTION_DESTROY);
    }

    public function edit(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SUSCRIPTION_EDIT);
    }
}
