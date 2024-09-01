<?php

namespace App\Http\PersistantsLowLevel;

use App\Http\Requests\StoreSuscriptionRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\Suscription;
use App\Models\UserSuscription;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SuscriptionPll extends PersistantLowLevel
{
    public static function get_all_suscription()
    {
        $suscription = Cache::get('suscription.index');
        if (is_null($suscription)) {
            $suscription = Suscription::with('site')->get();
            Cache::put('suscription.index', $suscription);
        }

        return $suscription;
    }

    public static function get_suscription_enum_field_values(string $field)
    {
        return DB::select("SHOW COLUMNS FROM suscriptions WHERE Field = '".$field."'")[0]->Type;
    }

    public static function save_suscription(StoreSuscriptionRequest $request)
    {
        $suscription = new Suscription();
        $suscription->name = $request->name;
        $suscription->description = $request->description;
        $suscription->amount = $request->amount;
        $suscription->currency_type = $request->currency;
        $suscription->expiration_time = $request->expiration_time;
        $suscription->frecuency_collection = $request->frecuency_collection;
        $suscription->site()->associate($request->site_id);
        $suscription->save();

        Cache::flush();
    }

    public static function get_cache(string $name)
    {
        return Cache::get($name);
    }

    public static function forget_cache(string $name_cache)
    {
        Cache::forget($name_cache);
    }

    public static function save_cache(string $name, $data)
    {
        Cache::put($name, $data);
    }
}
