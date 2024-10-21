<?php

namespace App\Http\PersistantsLowLevel;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolePll extends PersistantLowLevel
{
    private const SECONDS = 300;

    public static function get_all_users_roles()
    {
        return Cache::remember('users.roles', self::SECONDS, function () {
            return Role::with(['users' => function ($query) {
                $query->select('users.id', 'users.name', 'users.email', 'users.document', 'role_id');
            }])->orderBy('id', 'asc')->get();
        });
    }

    public static function get_specific_role(string $role_name)
    {
        return Cache::remember('role.'.$role_name, self::SECONDS, function () use ($role_name) {
            return Role::findByName($role_name);
        });
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
