<?php

namespace App\Http\PersistantsLowLevel;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolePll extends PersistantLowLevel
{
    public static function get_all_users_roles()
    {
        $roles = Cache::get('users.roles');
        if (is_null($roles)) {
            $roles = Role::with(['users' => function ($query) {
                $query->select('users.id', 'users.name', 'users.email', 'role_id');
            }])->orderBy('id', 'asc')->get();

            Cache::put('users.roles', $roles);
        }

        return $roles;
    }

    public static function get_specific_role(string $role_name)
    {
        $role = Cache::get('role.'.$role_name);
        if (is_null($role)) {
            $role = Role::findByName($role_name);

            Cache::put('role.'.$role_name, $role);
        }

        return $role;
    }

    public static function count_super_admin_users()
    {
        return DB::table('model_has_roles')
            ->where('role_id', 1)
            ->count();
    }

    public static function forget_cache(string $name_cache)
    {
        Cache::forget($name_cache);
    }
}
