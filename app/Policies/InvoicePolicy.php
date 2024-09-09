<?php

namespace App\Policies;

use App\Constants\Permissions;
use App\Models\User;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::INVOICES_INDEX);
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::INVOICES_SHOW);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::INVOICES_CREATE);
    }

    public function edit(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::INVOICES_EDIT);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::INVOICES_STORE);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::INVOICES_DESTROY);
    }

    public function pay(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::INVOICES_DESTROY);
    }
}
