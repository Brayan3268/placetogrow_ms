<?php

namespace Database\Seeders;

use App\Constants\Permissions;
use App\Constants\Roles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    protected array $roles = [
        ['name' => Roles::SUPER_ADMIN, 'guard_name' => 'web'],
        ['name' => Roles::ADMIN, 'guard_name' => 'web'],
        ['name' => Roles::GUEST, 'guard_name' => 'web'],
    ];

    public function run(): void
    {
        DB::table('roles')->upsert($this->roles, 'name');

        $this->assign_permissions_to_super_admin();
        $this->assign_permissions_to_admin();
        $this->assign_permissions_to_guest();
    }

    public function assign_permissions_to_super_admin()
    {
        $super_admin_role = Role::findByName(Roles::SUPER_ADMIN);

        $super_admin_role->syncPermissions(Permissions::get_all_permissions());
    }

    public function assign_permissions_to_admin()
    {
        $admin_role = Role::findByName(Roles::ADMIN);

        $admin_role->syncPermissions(Permissions::get_permissions_admin());
    }

    public function assign_permissions_to_guest()
    {
        $guest = Role::findByName(Roles::GUEST);

        $guest->syncPermissions(Permissions::get_permissions_guest());
    }
}
