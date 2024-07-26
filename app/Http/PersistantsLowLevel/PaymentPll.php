<?php

namespace App\Http\PersistantsLowLevel;

use App\Models\Payment;
use Illuminate\Support\Facades\Cache;

class PaymentPll extends PersistantLowLevel
{
    public static function get_all_pays()
    {
        $pays = Cache::get('pays.index');
        if (is_null($pays)) {
            $pays = Payment::with('user', 'site')->get();

            Cache::put('pays.index', $pays);
        }

        return $pays;
    }

    public static function get_especific_user_pays(int $user_id)
    {

        $pays = Cache::get('pays.user'.$user_id);
        if (is_null($pays)) {
            $pays = Payment::with('site')->where('user_id', $user_id)->get();

            Cache::put('pays.user'.$user_id, $pays);
        }

        return $pays;
    }

    public static function save_cache(string $name, $data)
    {
        Cache::put($name, $data);
    }

    public static function get_cache(string $name)
    {
        return Cache::get($name);
    }

    public static function forget_cache(string $name_cache)
    {
        Cache::forget($name_cache);
    }
}
