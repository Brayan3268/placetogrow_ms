<?php

namespace App\Http\PersistantsLowLevel;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class CategoryPll extends PersistantLowLevel
{
    private const SECONDS = 300;

    public static function get_all_categories()
    {
        return Category::all();
    }

    public static function get_cache(string $name)
    {
        return Cache::remember($name, self::SECONDS, function () use ($name) {
            return Cache::get($name);
        });
    }

    public static function get_specific_role(string $role_name)
    {
        return Cache::remember('role.'.$role_name, self::SECONDS, function ($role_name) {
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
