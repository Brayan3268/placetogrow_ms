<?php

namespace App\Http\PersistantsLowLevel;

use App\Constants\SuscriptionStatus;
use App\Constants\UserSuscriptionTypesNotification;
use App\Models\Usersuscription;
use App\Notifications\UserSuscriptionNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Http;

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
        /*Usersuscription::where('days_until_next_payment', 0)
           ->where('status', SuscriptionStatus::APPROVED->value)
           ->get();*/

        return Usersuscription::where(function ($query) {
            $query->where('days_until_next_payment', 0)
                ->where('status', SuscriptionStatus::APPROVED->value);
        })
            ->orWhere(function ($query) {
                $query->whereDate('date_try', Carbon::now('America/Bogota')->format('Y-m-d'))
                    ->whereIn('status', [
                        SuscriptionStatus::REJECTED->value,
                        SuscriptionStatus::FAILED->value,
                        SuscriptionStatus::PENDING->value,

                    ])
                    ->whereColumn('attempts_realised', '<', 'suscriptions.number_trys');
            })
            ->join('suscriptions', 'usersuscriptions.suscription_id', '=', 'suscriptions.id')
            ->get();
    }

    public static function restore_days_until_next_payment(string $reference, int $user_id, int $days)
    {
        Usersuscription::where('reference', $reference)
            ->where('user_id', $user_id)
            ->where('status', SuscriptionStatus::APPROVED->value)
            ->update(['days_until_next_payment' => $days]);
    }

    public static function delete_user_suscription(string $reference, int $user_id)
    {
        $user_suscription = Usersuscription::where('reference', $reference)
            ->where('user_id', $user_id)
            ->first();

        $user_suscription->status = SuscriptionStatus::EXPIRATED->value;
        $user_suscription->save();

        self::invalidate_token($user_suscription);

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
            $records = Usersuscription::where('expiration_time', 0)
                ->get();

            Usersuscription::whereIn('reference', $records->pluck('reference'))
                ->update(['status' => SuscriptionStatus::EXPIRATED->value]);

            return Usersuscription::whereIn('reference', $records->pluck('reference'))->get();
        });

        foreach ($updated_user_subscriptions as $updated_user_subscription) {
            self::invalidate_token($updated_user_subscription);

            $site = SitePll::get_specific_site(strval($updated_user_subscription->suscription->site_id));

            $notification = new UserSuscriptionNotification($updated_user_subscription, $site, UserSuscriptionTypesNotification::NOTICE_DELETED_EXPIRATION_SUSCRIPTION->value);
            Notification::send([$updated_user_subscription->user], $notification->delay(self::SECONDS_EMAIL));
        }
    }

    public static function change_status(string $reference, string $status)
    {
        $user_suscription = Usersuscription::where('reference', $reference)->first();

        $date = ($status == SuscriptionStatus::REJECTED->value) ? Carbon::now('America/Bogota')->addDays($user_suscription->suscription->how_often_days)->format('Y-m-d') : null;

        $user_suscription->status = $status;
        $user_suscription->date_try = $date;
        $user_suscription->attempts_realised = ($status == SuscriptionStatus::REJECTED->value) ? $user_suscription->attempts_realised + 1 : 0;

        $user_suscription->save();
    }

    public static function delete_not_payed_user_suscription()
    {
        $updated_user_subscriptions = DB::transaction(function () {
            $records = Usersuscription::whereColumn('attempts_realised', '=', 'suscriptions.number_trys')
                ->join('suscriptions', 'usersuscriptions.suscription_id', '=', 'suscriptions.id')
                ->get();

            Usersuscription::whereIn('reference', $records->pluck('reference'))
                ->update(['status' => SuscriptionStatus::EXPIRATED->value]);

            return Usersuscription::whereIn('reference', $records->pluck('reference'))->get();
        });

        dump($updated_user_subscriptions);

        foreach ($updated_user_subscriptions as $updated_user_subscription) {
            self::invalidate_token($updated_user_subscription);

            $site = SitePll::get_specific_site(strval($updated_user_subscription->suscription->site_id));

            $notification = new UserSuscriptionNotification($updated_user_subscription, $site, UserSuscriptionTypesNotification::NOTICE_DELETED_NOT_PAYED_SUSCRIPTION->value);
            Notification::send([$updated_user_subscription->user], $notification->delay(self::SECONDS_EMAIL));
        }
    }

    public static function invalidate_token(Usersuscription $user_suscription)
    {
        $auth = self::get_auth();

        $data_pay = [
            'auth' => [
                'login' => $auth['login'],
                'tranKey' => $auth['tranKey'],
                'nonce' => $auth['nonce'],
                'seed' => $auth['seed'],
            ],
            'instrument' => [
                'token' => [
                    'token' => $user_suscription->token,
                ],
            ],
        ];
        
        $response = Http::post('https://checkout-co.placetopay.dev/gateway/invalidate', $data_pay);
        $result = $response->json();

        dd($result);
    }

    public static function get_auth()
    {
        $login = 'e3bba31e633c32c48011a4a70ff60497';
        $secretKey = 'ak5N6IPH2kjljHG3';
        $seed = date('c');
        $nonce = (string) rand();

        $tranKey = base64_encode(hash('sha256', $nonce.$seed.$secretKey, true));

        $nonce = base64_encode($nonce);

        return [
            'login' => $login,
            'tranKey' => $tranKey,
            'nonce' => $nonce,
            'seed' => $seed,
        ];
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
