<?php

namespace App\Http\PersistantsLowLevel;

use App\Http\Requests\StoreSuscriptionRequest;
use App\Models\Suscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SuscriptionPll extends PersistantLowLevel
{
    private const SECONDS = 300;

    public static function get_all_suscription()
    {
        return Cache::remember('suscription.index', self::SECONDS, function () {
            return Suscription::with('site')->get();
        });
    }

    public static function get_suscription_enum_field_values(string $field)
    {
        return DB::select("SHOW COLUMNS FROM suscriptions WHERE Field = '".$field."'")[0]->Type;
    }

    public static function save_suscription(StoreSuscriptionRequest $request)
    {
        $suscription = new Suscription;
        $suscription->name = $request->name;
        $suscription->description = $request->description;
        $suscription->amount = $request->amount;
        $suscription->currency_type = $request->currency;
        $suscription->expiration_time = $request->expiration_time;
        $suscription->frecuency_collection = $request->frecuency_collection;
        $suscription->site()->associate($request->site_id);
        $suscription->number_trys = $request->number_trys;
        $suscription->how_often_days = $request->how_often_days;
        $suscription->save();

        Cache::flush();
    }

    public static function update_suscription(Request $request, Suscription $suscription)
    {
        $suscription->update([
            'name' => $request->name,
            'description' => $request->description,
            'amount' => $request->amount,
            'currency_type' => $request->currency,
            'expiration_time' => $request->expiration_time,
            'frecuency_collection' => $request->frecuency_collection,
            'site_id' => $request->site_id,
            'number_trys' => $request->number_trys,
            'how_often_days' => $request->how_often_days,
        ]);

        Cache::flush();
    }

    public static function delete_suscription(Suscription $suscription)
    {
        $suscription->delete();

        Cache::flush();
    }

    public static function get_site_suscription(int $site_id)
    {
        return Cache::remember('suscription.site', self::SECONDS, function () use ($site_id) {
            return Suscription::where('site_id', $site_id)
                ->get();
        });
    }

    public static function get_especific_suscription(int $id)
    {
        return Suscription::find($id);
    }

    public static function get_cache(string $name)
    {
        return Cache::remember($name, self::SECONDS, function () use ($name) {
            return Cache::get($name);
        });
    }

    public static function forget_cache(string $name_cache)
    {
        Cache::forget($name_cache);
    }

    public static function save_cache(string $name, $data)
    {
        Cache::remember($name, self::SECONDS, function () use ($data) {
            return $data;
        });
    }
}
