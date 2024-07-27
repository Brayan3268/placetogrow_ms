<?php

namespace App\Http\PersistantsLowLevel;

use App\Constants\PaymentGateway;
use App\Constants\PaymentStatus;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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

    public static function get_especific_site_pays(int $site_id)
    {
        $pays = Cache::get('pays.site'.$site_id);
        if (is_null($pays)) {
            $pays = Payment::with('user')->where('site_id', $site_id)->get();

            Cache::put('pays.site'.$site_id, $pays);
        }

        return $pays;
    }

    public static function get_especific_site_user_pays(int $site_id, int $user_id)
    {
        $pays = Cache::get('pays.site_user'.$site_id.'_'.$user_id);
        if (is_null($pays)) {
            $pays = Payment::with('user', 'site')
                ->where('site_id', $site_id)
                ->where('user_id', $user_id)
                ->get();

            Cache::put('pays.site_user'.$site_id.'_'.$user_id, $pays);
        }

        return $pays;
    }

    public static function save_payment(StorePaymentRequest $request): Payment
    {
        $user_id = Auth::user()->id;

        $payment = new Payment();
        $payment->reference = (is_null($request->reference)) ? date('ymdHis').'-'.strtoupper(Str::random(4)) : $request->reference;
        $payment->locale = $request->locale;
        $payment->amount = $request->total;
        $payment->description = $request->description;
        $payment->currency = $request->currency;
        $payment->gateway = PaymentGateway::PLACETOPAY->value;
        $payment->site()->associate($request->site_id);
        $payment->user()->associate($user_id);
        $payment->status = PaymentStatus::PENDING->value;

        //$payment->expiration = 88;

        $payment->save();
        PaymentPll::forget_cache('pays.index');
        PaymentPll::forget_cache('pays.user'.$user_id);
        PaymentPll::forget_cache('pays.site'.$request->site_id);
        PaymentPll::forget_cache('pays.site_user'.$request->site_id.'_'.$user_id);

        return $payment;
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
