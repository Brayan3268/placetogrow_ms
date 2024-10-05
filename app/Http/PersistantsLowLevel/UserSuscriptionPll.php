<?php

namespace App\Http\PersistantsLowLevel;

use App\Constants\SuscriptionStatus;
use App\Constants\UserSuscriptionTypesNotification;
use App\Models\Usersuscription;
use App\Notifications\UserSuscriptionNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Ramsey\Uuid\Uuid;

class UserSuscriptionPll extends PersistantLowLevel
{
    private const SECONDS = 300;

    private const SECONDS_EMAIL = 10;

    public static function get_all_user_suscriptions()
    {
        return Cache::remember('usersuscription.index', self::SECONDS, function () {
            return Usersuscription::with('user', 'suscription')->get();
        });
    }

    public static function get_specific_user_suscriptions(int $user_id)
    {
        return Cache::remember('usersuscription.user.'.$user_id, self::SECONDS, function () use ($user_id) {
            return Usersuscription::with('user', 'suscription')
                ->where('user_id', $user_id)
                ->get();
        });
    }

    public static function get_specific_suscription(string $reference, int $user_id)
    {
        Cache::flush();

        $user_suscription = Cache::remember('usersuscription.especific', self::SECONDS, function () use ($user_id, $reference) {
            return Usersuscription::with('user', 'suscription')
                ->where('user_id', $user_id)
                ->where('reference', $reference)
                ->first();
        });

        return json_decode($user_suscription);
    }

    public static function get_specific_suscription_with_out_decode(string $reference, int $user_id)
    {
        Cache::flush();

        $user_suscription = Cache::remember('usersuscription.especific', self::SECONDS, function () use ($user_id, $reference) {
            return Usersuscription::with('user', 'suscription')
                ->where('user_id', $user_id)
                ->where('reference', $reference)
                ->first();
        });

        return $user_suscription;
    }

    public static function get_specific_user_suscription_request_id(string $request_id)
    {
        Cache::flush();

        return Cache::remember('usersuscription.request_id', self::SECONDS, function () use ($request_id) {
            return Usersuscription::with('user', 'suscription')
                ->where('request_id', $request_id)
                ->first();
        });
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

    public static function get_suscriptions_to_collect()
    {
        return Usersuscription::where('days_until_next_payment', 0)->get();
    }

    public static function restore_days_until_next_payment(string $reference, int $user_id, int $days)
    {
        Usersuscription::where('reference', $reference)
            ->where('user_id', $user_id)
            ->update(['days_until_next_payment' => $days]);
    }

    public static function delete_user_suscription(string $reference, int $user_id)
    {
        Usersuscription::where('reference', $reference)->where('user_id', $user_id)->delete();

        Cache::flush();
    }

    public static function decrement_day_until_next_payment()
    {
        $user_suscriptions = Usersuscription::where('status', SuscriptionStatus::APPROVED->value)
            ->tap(function ($query) {
                $query->decrement('days_until_next_payment');
            })->get();

        foreach ($user_suscriptions as $user_suscription) {
            if ($user_suscription->days_until_next_payment <= 3) {
                $site = SitePll::get_specific_site(strval($user_suscription->suscription->site_id));

                $notification = new UserSuscriptionNotification($user_suscription, $site, UserSuscriptionTypesNotification::NOTICE_NEXT_PAYMENT->value);
                Notification::send([$user_suscription->user], $notification->delay(self::SECONDS_EMAIL));
            }
        }
    }

    public static function decrement_expiration_time()
    {
        $user_suscriptions = Usersuscription::where('status', SuscriptionStatus::APPROVED->value)
            ->tap(function ($query) {
                $query->decrement('expiration_time');
            })->get();

        foreach ($user_suscriptions as $user_suscription) {
            if ($user_suscription->expiration_time <= 3) {
                $site = SitePll::get_specific_site(strval($user_suscription->suscription->site_id));

                $notification = new UserSuscriptionNotification($user_suscription, $site, UserSuscriptionTypesNotification::NOTICE_EXPIRATION_SUSCRIPTION->value);
                Notification::send([$user_suscription->user], $notification->delay(self::SECONDS_EMAIL));
            }
        }
    }

    public static function delete_user_suscription_expiration_time()
    {
        $updated_user_subscriptions = DB::transaction(function () {
            $records = Usersuscription::where('status', SuscriptionStatus::APPROVED->value)
                ->where('expiration_time', 0)
                ->get();

            Usersuscription::whereIn('reference', $records->pluck('reference'))
                ->update(['status' => SuscriptionStatus::EXPIRATED->value]);

            return Usersuscription::whereIn('reference', $records->pluck('reference'))->get();
        });

        dump($updated_user_subscriptions);
        foreach ($updated_user_subscriptions as $updated_user_subscription) {
            $site = SitePll::get_specific_site(strval($updated_user_subscription->suscription->site_id));

            $notification = new UserSuscriptionNotification($updated_user_subscription, $site, UserSuscriptionTypesNotification::NOTICE_DELETED_SUSCRIPTION->value);
            Notification::send([$updated_user_subscription->user], $notification->delay(self::SECONDS_EMAIL));
        }
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
        Cache::remember($name, self::SECONDS, function () use ($data) {
            return $data;
        });
    }
}
