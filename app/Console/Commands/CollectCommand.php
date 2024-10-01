<?php

namespace App\Console\Commands;

use App\Http\PersistantsLowLevel\PaymentPll;
use App\Http\PersistantsLowLevel\UserSuscriptionPll;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CollectCommand extends Command
{
    protected $signature = 'app:collect-command';

    protected $description = 'Made the collect for all user when is nedeed';

    public function handle()
    {
        $requests = [];
        $days = [
            'WEEK' => 7,
            'FORTNIGHTLY' => 15,
            'MONTH' => 30,
            'QUARTERLY' => 90,
            'BIANNUAL' => 180,
            'ANNUAL' => 360,
        ];

        $user_suscriptions_to_collect = UserSuscriptionPll::get_suscriptions_to_collect();

        foreach ($user_suscriptions_to_collect as $suscriptions) {
            $auth = $this->get_auth();
            $data_pay = [];
            
            $data_pay = [
                'auth' => [
                    'login' => $auth['login'],
                    'tranKey' => $auth['tranKey'],
                    'nonce' => $auth['nonce'],
                    'seed' => $auth['seed'],
                ],
                'payer' => [
                    'name' => $suscriptions->user->name,
                    'surname' => $suscriptions->user->last_name,
                    'email' => $suscriptions->user->email,
                    'documentType' => $suscriptions->user->document_type,
                    'document' => $suscriptions->user->document,
                    'mobile' => $suscriptions->user->phone,
                ],
                'payment' => [
                    'reference' => substr(str(Str::uuid()), 0, 32),
                    'description' => $suscriptions->suscription->description,
                    'amount' => [
                        'currency' => $suscriptions->suscription->currency_type,
                        'total' => $suscriptions->suscription->amount,
                    ],
                ],
                'instrument' => [
                    'token' => [
                        'token' => $suscriptions->token,
                    ],
                ],
                'ipAddress' => request()->ip(),
                'userAgent' => request()->userAgent(),
                'returnUrl' => route('payment.suscription_show', [
                    'payment' => 100,
                ]),
            ];

            $requests[] = $data_pay;
        }

        $length = min(count($user_suscriptions_to_collect), count($requests));
        for ($i = 0; $i < $length; $i++) {
            $response = Http::post('https://checkout-co.placetopay.dev/api/collect', $requests[$i]);

            $result = $response->json();

            PaymentPll::save_payment_suscription($result, $user_suscriptions_to_collect[$i]);
        }
        
        foreach ($user_suscriptions_to_collect as $user_suscription) {
            UserSuscriptionPll::restore_days_until_next_payment($user_suscription->reference, $user_suscription->user->id, $days[$user_suscription->suscription->frecuency_collection]);
        }

        $this->info('Comando ejecutado con éxito!');
    }

    public function get_auth()
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
}
