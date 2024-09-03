<?php

namespace App\Http\PersistantsLowLevel;

use App\Models\Usersuscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

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

    public function newUniqueId(): string
    {
        return (string) Uuid::uuid4();
    }

    public static function save_user_suscription(Request $request)
    {
        $suscription = SuscriptionPll::get_especific_suscription($request->suscription_id);
        $user_id = Auth::user()->id;

        $user_suscription = new UserSuscription;
        $user_suscription->reference = Str::uuid();
        $user_suscription->user()->associate($user_id);
        $user_suscription->expiration_time = $suscription->expiration_time;
        $user_suscription->suscription()->associate($suscription->id);

        $user_suscription->save();

        Cache::flush();
    }

    public static function delete_user_suscription(string $reference, int $user_id)
    {
        Usersuscription::where('reference', $reference)->where('user_id', $user_id)->delete();

        Cache::flush();
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
