<?php

namespace App\Http\Controllers;

use App\Constants\SuscriptionStatus;
use App\Http\PersistantsLowLevel\PaymentPll;
use App\Http\PersistantsLowLevel\SuscriptionPll;
use App\Http\PersistantsLowLevel\UserSuscriptionPll;
use App\Http\Requests\UpdateUsersuscriptionRequest;
use App\Models\Usersuscription;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UsersuscriptionController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->authorize('update', Usersuscription::class);

        $suscription_db = SuscriptionPll::get_especific_suscription($request->suscription_id);

        $suscription = [
            'reference' => Str::uuid(),
            'user_id' => Auth::user()->id,
            'expiration_time' => 30,
            'suscription_id' => $suscription_db->id,
            'status' => SuscriptionStatus::PENDING,
        ];

        $auth = $this->get_auth();

        $data = [
            'auth' => [
                'login' => $auth['login'],
                'tranKey' => $auth['tranKey'],
                'nonce' => $auth['nonce'],
                'seed' => $auth['seed'],
            ],
            'buyer' => [
                'email' => Auth::user()->email,
            ],
            'reference' => $suscription['reference'],
            'description' => $suscription_db->description,
            'expiration' => now()->addHour()->format('c'),
            'ipAddress' => request()->ip(),
            'userAgent' => request()->userAgent(),
            'returnUrl' => route('user_suscriptions.return', [
                'suscription_reference' => $suscription['reference'],
            ]),
            'subscription' => [
                'reference' => substr(str($suscription['reference']), 0, 32),
                'description' => $suscription_db->description,
            ],
        ];

        $response = Http::post('https://checkout-co.placetopay.dev/api/session', $data);

        $result = $response->json();
        if (! $response->ok()) {
            return redirect()->route('suscriptions.index')
                ->with('status', 'Users suscription not created successfully!')
                ->with('class', 'bg-red-500');
        }

        $suscription['request_id'] = $result['requestId'];
        $suscription['additional_data'] = $result['processUrl'];

        UserSuscriptionPll::save_user_suscription($suscription);

        return redirect()->away($result['processUrl']);
    }

    public function show(string $usersuscription_reference)
    {
        $this->authorize('view', Usersuscription::class);

        return view('user_suscriptions.show', compact('usersuscription_reference'));
    }

    public function edit(Usersuscription $usersuscription)
    {
        //
    }

    public function update(UpdateUsersuscriptionRequest $request, Usersuscription $usersuscription)
    {
        //
    }

    public function destroy(string $reference, int $user_id) {}

    public function destroyy(string $reference, int $user_id)
    {
        UserSuscriptionPll::delete_user_suscription($reference, $user_id);

        return redirect()->route('suscriptions.index')
            ->with('status', 'Suscription deleted successfully')
            ->with('class', 'bg-green-500');
    }

    public function return(string $user_suscription)
    {
        $user_suscription = UserSuscriptionPll::get_specific_suscription($user_suscription, intval(Auth::user()->id));

        $auth = $this->get_auth();
        $data = [
            'auth' => [
                'login' => $auth['login'],
                'tranKey' => $auth['tranKey'],
                'nonce' => $auth['nonce'],
                'seed' => $auth['seed'],
            ],
        ];
        $session_information = Http::post('https://checkout-co.placetopay.dev/api/session/'.$user_suscription->request_id, $data);

        $user_suscription->token = $session_information['subscription']['instrument'][0]['value'];
        $user_suscription->sub_token = $session_information['subscription']['instrument'][1]['value'];

        $user_suscription_updated = UserSuscriptionPll::update_suscription($user_suscription, SuscriptionStatus::APPROVED);

        $auth = $this->get_auth();
        $data_pay = [
            'auth' => [
                'login' => $auth['login'],
                'tranKey' => $auth['tranKey'],
                'nonce' => $auth['nonce'],
                'seed' => $auth['seed'],
            ],
            'payer' => [
                'name' => Auth::user()->name,
                'surname' => Auth::user()->last_name,
                'email' => Auth::user()->email,
                'documentType' => Auth::user()->document_type,
                'document' => '1001132544',
                'mobile' => Auth::user()->phone,
            ],
            'payment' => [
                'reference' => substr(str($user_suscription_updated->reference), 0, 32),
                'description' => $user_suscription_updated->description,
                'amount' => [
                    'currency' => $user_suscription_updated->suscription->currency_type,
                    'total' => $user_suscription_updated->suscription->amount,
                ],
            ],
            'instrument' => [
                'token' => [
                    'token' => $user_suscription_updated->token,
                ],
            ],
            'ipAddress' => request()->ip(),
            'userAgent' => request()->userAgent(),
            'returnUrl' => route('payment.suscription_show', [
                'payment' => 100,
            ]),
        ];

        $response = Http::post('https://checkout-co.placetopay.dev/api/collect', $data_pay);

        $result = $response->json();

        if (! $response->ok()) {
            return redirect()->route('suscriptions.index')
                ->with('status', 'Users suscription pay not maded successfully!')
                ->with('class', 'bg-red-500');
        }

        $payment = PaymentPll::save_payment_suscription($result, $user_suscription_updated);
        $invoice_status = '';
        $suscription_status = $user_suscription_updated->status;
        $user_suscription = $user_suscription_updated;

        return view('payments.show', compact('payment', 'invoice_status', 'suscription_status', 'user_suscription'));
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
