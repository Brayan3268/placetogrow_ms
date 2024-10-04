<?php

namespace App\Http\Controllers;

use App\Constants\InvoiceStatus;
use App\Constants\OriginPayment;
use App\Constants\PaymentStatus;
use App\Contracts\PaymentService;
use App\Http\PersistantsLowLevel\InvoicePll;
use App\Http\PersistantsLowLevel\PaymentPll;
use App\Http\PersistantsLowLevel\UserPll;
use App\Http\PersistantsLowLevel\UserSuscriptionPll;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Notifications\PayNotification;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    private const SECONDS_EMAIL = 10;

    public function index(): View
    {
        /*if ($user->hasPermissionTo(Permissions::USER_GET_SUSCRIPTION)) {
            $user_plans = UserSuscriptionPll::get_specific_user_suscriptions($user->id);
            foreach ($user_plans as $key => $value) {
                foreach ($suscription_plans as $key_all => $value_all) {
                    if ($value->suscription_id == $value_all->id) {
                        array_push($user_plans_get_suscribe, $value);
                        unset($suscription_plans[$key_all]);
                    }
                }
            }
        }*/

        $pays = $this->validate_role() ? PaymentPll::get_all_pays() : PaymentPll::get_especific_user_pays(Auth::user()->id);

        $log[] = 'Ingresó a payment.index';
        $this->write_file($log);

        return view('payments.index', compact('pays'));
    }

    public function pays_especific_user(int $user_id): View
    {
        $pays = PaymentPll::get_especific_user_pays($user_id);

        $log[] = 'Consultó los pagos especificos del usuario con el id '.$user_id;
        $this->write_file($log);

        return view('payments.index', compact('pays'));
    }

    public function pays_especific_site(int $site_id): View
    {
        $pays = $this->validate_role() ? PaymentPll::get_especific_site_pays($site_id) : PaymentPll::get_especific_site_user_pays($site_id, Auth::user()->id);

        $log[] = 'Consultó los pagos especificos del sitio con el id '.$site_id;
        $this->write_file($log);

        return view('payments.index', compact('pays'));
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $payment = PaymentPll::save_payment($request);

        $payment->setAttribute('invoice_id', $request->invoice_id);

        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class, [
            'payment' => $payment,
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

        PaymentPll::save_response_url_payment($payment, $response->url);

        $log[] = 'Creó una sesion de pago para P2P';
        $this->write_file($log);

        return redirect()->away($response->url);
    }

    public function show(Request $request, Payment $payment): View
    {
        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class, [
            'payment' => $payment,
            'gateway' => $payment->gateway,
        ]);

        if ($payment->status === PaymentStatus::PENDING->value) {
            $payment = $paymentService->query();
            $log[] = 'Finalizó una sesion de pago en P2P de tipo '.$payment->origin_payment;
        }

        $invoice_id = intval($request->query('invoice_id'));
        $payment_id = intval($payment->id);
        $status = '';

        if ($payment->origin_payment == '') {
            $payment->update([
                'origin_payment' => ($invoice_id == 0 && $payment->origin_payment == '') ? OriginPayment::STANDART->value : OriginPayment::INVOICE->value,
            ]);
            Cache::flush();
        }

        if ($payment->origin_payment == OriginPayment::STANDART->value) {
            $invoice = InvoicePll::get_especific_invoice_with_pay_id($payment_id);
            $status = ($invoice !== '') ? $invoice->status : '';
        }

        $invoice = '';
        if ($payment->origin_payment == OriginPayment::INVOICE->value) {
            try {
                $invoice = InvoicePll::get_especific_invoice($invoice_id);
                if ($payment->reference != $invoice->reference) {
                    $payment = PaymentPll::update_reference_pay($payment->id, $invoice->reference);
                }
            } catch (\Exception $e) {
                dump('catch');
            }

            $status_payment = $payment->status;

            switch ($status_payment) {
                case PaymentStatus::APPROVED->value:
                    $status = InvoiceStatus::PAYED->value;
                    break;

                case PaymentStatus::REJECTED->value:
                    $status = InvoiceStatus::NOT_PAYED->value;
                    break;

                case PaymentStatus::PENDING->value:
                    $status = InvoiceStatus::PENDING->value;
                    break;

                default:
                    $status = 'not_payed';
                    break;
            }

            $invoice = InvoicePll::update_invoice($payment->reference, $status, $payment_id);
        }

        $suscription_status = '';
        $user_suscription = '';
        if ($payment->origin_payment == OriginPayment::SUSCRIPTION->value) {
            $user_suscription = UserSuscriptionPll::get_specific_user_suscription_request_id($payment->process_identifier);
            $suscription_status = $user_suscription->status;
        }

        $notification = new PayNotification(
            $payment,
            $status,
            $suscription_status,
            $invoice,
            $user_suscription,
        );

        Notification::send([Auth::user()], $notification->delay(self::SECONDS_EMAIL));
        $log[] = 'Envió un correo con la información del movimiento transaccional';

        $log[] = 'Ingresó a payments.show '.$payment->origin_payment;
        $this->write_file($log);

        return view('payments.show', [
            'payment' => $payment,
            'invoice_status' => $status,
            'invoice' => $invoice,
            'suscription_status' => $suscription_status,
            'user_suscription' => $user_suscription,
        ]);
    }

    private function validate_role(): bool
    {
        $role_name = UserPll::get_user_auth();

        return ($role_name[0] === 'super_admin' || $role_name[0] === 'admin') ? true : false;
    }

    //ELIMINAR ESTO Y CREAR LA POLICY Y ELIMINAR VALIDATE_ROL
    public function show_suscription_pay(int $payment)
    {
        //dd($payment);
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
