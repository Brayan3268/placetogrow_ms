<?php

namespace App\Http\PersistantsLowLevel;

use App\Models\Usersuscription;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserSuscriptionPll extends PersistantLowLevel
{
    public static function get_all_user_suscriptions()
    {
        $user_suscription = Cache::get('usersuscription.index');
        if (is_null($user_suscription)) {
            $user_suscription = Usersuscription::with('user', 'suscription')
                ->get();

            Cache::put('usersuscription.index', $user_suscription);
        }

        return $user_suscription;
    }

    public static function get_specific_user_suscriptions(int $user_id)
    {
        $user_suscription = Cache::get('usersuscription.index');
        if (is_null($user_suscription)) {
            $user_suscription = Usersuscription::with('user', 'suscription')
                ->where('user_id', $user_id)
                ->get();

            Cache::put('usersuscription.index', $user_suscription);
        }

        return $user_suscription;
    }

    public static function forget_cache(string $name_cache)
    {
        Cache::forget($name_cache);
    }

    public static function get_users_enum_field_values(string $field)
    {
        return DB::select("SHOW COLUMNS FROM users WHERE Field = '".$field."'")[0]->Type;
    }

    public static function save_cache(string $name, $data)
    {
        Cache::put($name, $data);
    }
}
