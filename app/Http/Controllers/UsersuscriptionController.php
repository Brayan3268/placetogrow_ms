<?php

namespace App\Http\Controllers;

use App\Constants\SuscriptionStatus;
use App\Constants\UserSuscriptionTypesNotification;
use App\Http\PersistantsLowLevel\PaymentPll;
use App\Http\PersistantsLowLevel\SitePll;
use App\Http\PersistantsLowLevel\SuscriptionPll;
use App\Http\PersistantsLowLevel\UserSuscriptionPll;
use App\Http\Requests\UpdateUsersuscriptionRequest;
use App\Models\Usersuscription;
use App\Notifications\PayNotification;
use App\Notifications\UserSuscriptionNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UsersuscriptionController extends Controller
{
    use AuthorizesRequests;

    private const SECONDS_EMAIL = 10;

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

        $days = [
            'WEEK' => 7,
            'FORTNIGHTLY' => 15,
            'MONTH' => 30,
            'QUARTERLY' => 90,
            'BIANNUAL' => 180,
            'ANNUAL' => 360,
        ];

        $suscription_db = SuscriptionPll::get_especific_suscription($request->suscription_id);
        $log[] = 'Consultó la información de la suscripción '.$request->suscription_id;

        $suscription = [
            'reference' => Str::uuid(),
            'user_id' => Auth::user()->id,
            'expiration_time' => $suscription_db->expiration_time,
            'days_until_next_payment' => $days[$suscription_db->frecuency_collection],
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

        $log[] = 'Creó una sesion para realizar una suscripción';

        $result = $response->json();
        if (! $response->ok()) {
            $log[] = 'La sesion no se creó correctamente';

            return redirect()->route('suscriptions.index')
                ->with('status', 'Users suscription not created successfully!')
                ->with('class', 'bg-red-500');
        }

        $suscription['request_id'] = $result['requestId'];
        $suscription['additional_data'] = $result['processUrl'];

        UserSuscriptionPll::save_user_suscription($suscription);

        $log[] = 'Redirige al usuario a la pasarela para la suscripción';
        $this->write_file($log);

        return redirect()->away($result['processUrl']);
    }

    public function show(string $usersuscription_reference)
    {
        $this->authorize('view', Usersuscription::class);

        $user_id = Auth::user()->id;

        $user_suscription = UsersuscriptionPll::get_specific_suscription($usersuscription_reference, $user_id);
        $site = SitePll::get_specific_site($user_suscription->suscription->site_id);

        $log[] = 'Ingresó a user_suscriptions.show';
        $this->write_file($log);

        //dd($user_suscription->suscription);

        return view('user_suscriptions.show', compact('user_suscription', 'site'));
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
        //Enviar petición para invaldiar el token

        $user_suscription = UserSuscriptionPll::get_specific_suscription_with_out_decode($reference, $user_id);
        UserSuscriptionPll::delete_user_suscription($reference, $user_id);

        $site = SitePll::get_specific_site($user_suscription->suscription->site_id);

        $notification = new UserSuscriptionNotification($user_suscription, $site, UserSuscriptionTypesNotification::UNSUSCRIPTION->value);
        Notification::send([Auth::user()], $notification->delay(self::SECONDS_EMAIL));
        $log[] = 'Envia notificación de la dessuscripcion del usuario';

        $log[] = 'Eliminó la suscripción '.$reference.' del usuario '.$user_id;
        $this->write_file($log);

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

        $log[] = 'Consulta la información de la sesion de suscripción';

        $user_suscription->token = $session_information['subscription']['instrument'][0]['value'];
        $user_suscription->sub_token = $session_information['subscription']['instrument'][1]['value'];

        $user_suscription_updated = UserSuscriptionPll::update_suscription($user_suscription, SuscriptionStatus::APPROVED);
        $log[] = 'Actualiza la información de la sesion de suscripción del usuario';

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
                'document' => Auth::user()->document,
                'mobile' => Auth::user()->phone,
            ],
            'payment' => [
                'reference' => substr(str(Str::uuid()), 0, 32),
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
        $log[] = 'Realiza el cobro por token a la suscripción del usuario';

        $result = $response->json();

        if (! $response->ok()) {
            $log[] = 'El cobro no se realiza correctamente';

            return redirect()->route('suscriptions.index')
                ->with('status', 'Users suscription pay not maded successfully!')
                ->with('class', 'bg-red-500');
        }

        $payment = PaymentPll::save_payment_suscription($result, $user_suscription_updated);
        $invoice_status = '';
        $suscription_status = $user_suscription_updated->status;
        $user_suscription = $user_suscription_updated;

        $site = SitePll::get_specific_site($user_suscription->suscription->site_id);
        $notification_sus = new UserSuscriptionNotification($user_suscription, $site, UserSuscriptionTypesNotification::SUSCRIPTION->value);
        Notification::send([Auth::user()], $notification_sus->delay(self::SECONDS_EMAIL));
        $log[] = 'Envia notificación de la suscripcion del usuario';

        $notification = new PayNotification(
            $payment,
            '',
            $suscription_status,
            '',
            $user_suscription,
        );
        Notification::send([Auth::user()], $notification->delay(self::SECONDS_EMAIL));
        $log[] = 'Envia notificación del cobro automatico por suscripcion del usuario';

        $log[] = 'Crea el payment del cobro automatico';
        $this->write_file($log);

        return view('payments.show', compact('payment', 'invoice_status', 'suscription_status', 'user_suscription'));
    }

    public function get_auth()
    {
        //Poner en el env y en el readmes
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

    protected function write_file(array $info)
    {
        $current_date_time = Carbon::now('America/Bogota')->format('Y-m-d H:i:s');
        $content = '';

        foreach ($info as $key => $value) {
            $content .= '    '.$value.' en la fecha '.$current_date_time;
        }

        Storage::disk('public_logs')->append('log.txt', $content);
    }
}
