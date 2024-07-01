<?php

namespace App\Http\PersistantsLowLevel;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class CategoryPll extends PersistantLowLevel
{
    public static function get_all_categories()
    {
        return Category::all();
    }

    public static function get_cache(string $name)
    {
        return Cache::get($name);
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
