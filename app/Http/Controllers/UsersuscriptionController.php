<?php

namespace App\Http\Controllers;

use App\Constants\SuscriptionStatus;
use App\Contracts\PaymentService;
use App\Http\PersistantsLowLevel\PaymentPll;
use App\Http\PersistantsLowLevel\SuscriptionPll;
use App\Http\PersistantsLowLevel\UserSuscriptionPll;
use App\Http\Requests\UpdateUsersuscriptionRequest;
use App\Models\Suscription;
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

        $suscription = UserSuscription::query()->create([
            'reference' => Str::uuid(),
            'user_id' => Auth::user()->id,
            'expiration_time' => 30,
            'suscription_id' => $suscription_db->id,
            'status' => SuscriptionStatus::PENDING,
        ]);

        /*$paymentService = app(PaymentService::class, [
            'payment' => $suscription,
            'gateway' => $request->gateway,
        ]);

        $response = $paymentService->create([
            'name' => Auth::user()->name,
            'last_name' => Auth::user()->last_name,
            'email' => Auth::user()->email,
            'document' => Auth::user()->document,
            'document_type' => Auth::user()->document_type,
            'phone' => Auth::user()->phone,
        ]);

        PaymentPll::save_response_url_payment($payment, $response->url);*/

        $login = 'e3bba31e633c32c48011a4a70ff60497';
        $secretKey = 'ak5N6IPH2kjljHG3';
        $seed = date('c');
        $nonce = (string) rand();

        $tranKey = base64_encode(hash('sha256', $nonce.$seed.$secretKey, true));

        $nonce = base64_encode($nonce);

        $data = [
            'auth' => [
                'login' => $login,
                'tranKey' => $tranKey,
                'nonce' => $nonce,
                'seed' => $seed,
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
                'suscription_reference' => $suscription->reference,
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

        $suscription->update([
            'request_id' => $result['requestId'],
            'additional_datta' => $result['processUrl'],
        ]);

        UserSuscriptionPll::save_user_suscription($suscription);

        return redirect()->away($result['processUrl']);

        /*return redirect()->route('suscriptions.index')
            ->with('status', 'Users suscription created successfully!')
            ->with('class', 'bg-green-500');*/
    }

    public function show(Usersuscription $usersuscription)
    {
        //
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

    public function return(Usersuscription $user_suscription)
    {
        #dd($user_suscription);

        return redirect()->route('sites.index');
    }
}
