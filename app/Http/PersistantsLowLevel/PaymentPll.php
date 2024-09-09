<?php

namespace App\Http\PersistantsLowLevel;

use App\Constants\OriginPayment;
use App\Constants\PaymentGateway;
use App\Constants\PaymentStatus;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Usersuscription;
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

    public static function get_especific_pay(int $id)
    {
        $payment = Payment::find($id);

        return $payment;
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

        $payment = new Payment;
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

    public static function save_payment_suscription(array $result, Usersuscription $user_suscription_updated)
    {
        $payment = new Payment;
        $payment->locale = $result['request']['locale'];
        $payment->reference = $result['request']['payment']['reference'];
        $payment->description = $user_suscription_updated->suscription->description;
        $payment->amount = $result['request']['payment']['amount']['total'];
        $payment->currency = $result['request']['payment']['amount']['currency'];
        $payment->status = $result['status']['status'];
        $payment->gateway = PaymentGateway::PLACETOPAY->value;
        $payment->process_identifier = $user_suscription_updated['request_id'];
        $payment->site()->associate($user_suscription_updated->suscription->site_id);
        $payment->user()->associate($user_suscription_updated->user_id);
        $payment->url_session = json_decode($user_suscription_updated->additional_data);
        $payment->origin_payment = OriginPayment::SUSCRIPTION->value;

        $payment->save();
        Cache::flush();

        return $payment;
    }

    public static function save_response_url_payment(Payment $payment, string $url_session)
    {
        $payment->url_session = $url_session;

        $payment->save();
    }

    public static function update_reference_pay(int $payment_id, string $new_reference)
    {
        Payment::where('id', $payment_id)
            ->update([
                'reference' => $new_reference,
            ]);

        $payment = Payment::where('id', $payment_id)
            ->first();

        return $payment;
    }

    public static function validate_is_pending_rejected_pays(int $site_id)
    {
        $user_id = Auth::user()->id;

        $pays = Payment::with('user', 'site')
            ->where('site_id', $site_id)
            ->where('user_id', $user_id)
            ->whereIn('status', ['PENDING', 'REJECTED'])
            ->get();

        return (! $pays->isEmpty()) ? true : false;
    }

    public static function get_session_not_approved_payments(int $site_id)
    {
        $user_id = Auth::user()->id;

        $pay = Payment::with('user', 'site')
            ->where('site_id', $site_id)
            ->where('user_id', $user_id)
            ->whereIn('status', ['PENDING', 'REJECTED'])
            ->first();

        return $pay->url_session;
    }

    public static function get_pays_not_approved_payments(int $site_id)
    {
        $user_id = Auth::user()->id;

        $pay = Payment::with('user', 'site')
            ->where('site_id', $site_id)
            ->where('user_id', $user_id)
            ->whereIn('status', ['PENDING', 'REJECTED'])
            ->first();

        return $pay;
    }

    public static function lose_session(int $payment_id)
    {
        $payment = Payment::find($payment_id);

        $payment->status = 'EXPIRED';
        $payment->save();

        return $payment->site_id;
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
