<?php

namespace App\Policies;

use App\Constants\Permissions;
use App\Models\User;

class UserSuscriptionPolicy
{
    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::USER_SUSCRIPTION_STORE);
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::USER_SUSCRIPTION_SHOW);
    }
}
