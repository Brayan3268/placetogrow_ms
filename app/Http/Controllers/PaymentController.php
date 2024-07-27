<?php

namespace App\Http\Controllers;

use App\Constants\PaymentGateway;
use App\Constants\PaymentStatus;
use App\Contracts\PaymentService;
use App\Http\PersistantsLowLevel\PaymentPll;
use App\Http\PersistantsLowLevel\UserPll;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index(): View
    {
        $pays = $this->validate_role() ? PaymentPll::get_all_pays() : PaymentPll::get_especific_user_pays(Auth::user()->id);

        return view('payments.index', compact('pays'));
    }

    public function pays_especific_user(int $user_id): View
    {
        $pays = PaymentPll::get_especific_user_pays($user_id);

        return view('payments.index', compact('pays'));
    }

    public function pays_especific_site(int $site_id): View
    {
        $pays = $this->validate_role() ? PaymentPll::get_especific_site_pays($site_id) : PaymentPll::get_especific_site_user_pays($site_id, Auth::user()->id);

        return view('payments.index', compact('pays'));
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $user_id = Auth::user()->id;

        $payment = new Payment();
        $payment->reference = date('ymdHis').'-'.strtoupper(Str::random(4));
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

        //Guardar la url por si el pago queda pendiente
        //$payment->session_url = $response->url;
        //$payment->save();

        //Validar si hay un pago pendiente de este usuario y

        return redirect()->away($response->url);
    }

    public function show(Request $request, Payment $payment): View
    {
        //dd($request->all());
        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class, [
            'payment' => $payment,
            'gateway' => $payment->gateway,
        ]);

        if ($payment->status === PaymentStatus::PENDING->value) {
            $payment = $paymentService->query();
        }

        return view('payments.show', [
            'payment' => $payment,
        ]);
    }

    private function validate_role(): bool
    {
        $role_name = UserPll::get_user_auth();

        return ($role_name[0] === 'super_admin' || $role_name[0] === 'admin') ? true : false;
    }
}
