<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $super_admin_role = Role::where('name', 'super_admin')->first();
        $admin_role = Role::where('name', 'admin')->first();
        $guest_role = Role::where('name', 'guest')->first();

        Permission::create(['name' => 'users_menu.show'])->syncRoles([$super_admin_role, $admin_role]);
        Permission::create(['name' => 'super_user.options'])->syncRoles([$super_admin_role]);

        Permission::create(['name' => 'user.create'])->syncRoles([$super_admin_role, $admin_role]);
        Permission::create(['name' => 'user.edit'])->syncRoles([$super_admin_role, $admin_role]);
        Permission::create(['name' => 'user.show'])->syncRoles([$super_admin_role, $admin_role, $guest_role]);
        Permission::create(['name' => 'user.destroy'])->syncRoles([$super_admin_role, $admin_role]);

        Permission::create(['name' => 'super_user.create'])->syncRoles([$super_admin_role]);
        Permission::create(['name' => 'super_user.edit'])->syncRoles([$super_admin_role]);
        Permission::create(['name' => 'super_user.show'])->syncRoles([$super_admin_role, $admin_role]);
        Permission::create(['name' => 'super_user.destroy'])->syncRoles([$super_admin_role]);

        Permission::create(['name' => 'site.manage'])->syncRoles([$super_admin_role, $admin_role]);

        Permission::create(['name' => 'site.pay'])->syncRoles([$guest_role]);

        Permission::create(['name' => 'payments.see_admins_users'])->syncRoles([$super_admin_role, $admin_role]);

        Permission::create(['name' => 'invoices.see_admins_users'])->syncRoles([$super_admin_role, $admin_role]);
        Permission::create(['name' => 'pay_invoices.see_user'])->syncRoles([$super_admin_role, $admin_role]);
    }
}
