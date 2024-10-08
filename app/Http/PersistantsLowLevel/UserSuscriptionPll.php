<?php

namespace App\Http\PersistantsLowLevel;

use App\Constants\SuscriptionStatus;
use App\Models\Usersuscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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

    public static function get_specific_suscription(string $reference, int $user_id)
    {
        Cache::flush();

        $user_suscription = Cache::get('usersuscription.especific');
        if (is_null($user_suscription)) {
            $user_suscription = Usersuscription::with('user', 'suscription')
                ->where('user_id', $user_id)
                ->where('reference', $reference)
                ->first();

            Cache::put('usersuscription.especific', $user_suscription);
        }

        return json_decode($user_suscription);
    }

    public static function get_specific_user_suscription_request_id(string $request_id)
    {
        Cache::flush();

        $user_suscription = Cache::get('usersuscription.request_id');
        if (is_null($user_suscription)) {
            $user_suscription = Usersuscription::with('user', 'suscription')
                ->where('request_id', $request_id)
                ->first();

            Cache::put('usersuscription.request_id', $user_suscription);
        }

        return $user_suscription;
    }

    public function newUniqueId(): string
    {
        return (string) Uuid::uuid4();
    }

    public static function save_user_suscription(array $suscription_data)
    {
        $user_id = Auth::user()->id;

        $user_suscription = new UserSuscription;
        $user_suscription->reference = $suscription_data['reference'];
        $user_suscription->user()->associate($user_id);
        $user_suscription->expiration_time = $suscription_data['expiration_time'];
        $user_suscription->days_until_next_payment = $suscription_data['days_until_next_payment'];
        $user_suscription->suscription()->associate($suscription_data['suscription_id']);

        $user_suscription->status = $suscription_data['status'];
        $user_suscription->request_id = $suscription_data['request_id'];
        $user_suscription->additional_data = json_encode($suscription_data['additional_data']);

        $user_suscription->save();

        Cache::flush();
    }

    public static function update_suscription($user_suscription, SuscriptionStatus $status)
    {
        Usersuscription::with('user', 'suscription')
            ->where('user_id', $user_suscription->user_id)
            ->where('reference', $user_suscription->reference)
            ->update([
                'token' => $user_suscription->token,
                'sub_token' => $user_suscription->sub_token,
                'status' => $status,
            ]);

        $user_suscription_db = Usersuscription::where('user_id', $user_suscription->user_id)
            ->where('reference', $user_suscription->reference)
            ->first();

        return $user_suscription_db;
    }

    public static function get_suscriptions_to_collect(){
        return Usersuscription::where('days_until_next_payment', 25)->get();
    }

    public static function restore_days_until_next_payment(string $reference, int $user_id, int $days)
    {
        $user_subscription = Usersuscription::where('reference', $reference)
        ->where('user_id', $user_id)
        ->first();

        $user_subscription->days_until_next_payment = $days;

        $user_subscription->save();
    }

    public static function delete_user_suscription(string $reference, int $user_id)
    {
        Usersuscription::where('reference', $reference)->where('user_id', $user_id)->delete();

        Cache::flush();
    }

    public static function decrement_day()
    {
        Usersuscription::where('status', SuscriptionStatus::APPROVED->value)
        ->decrement('days_until_next_payment');
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
