<?php

namespace App\Http\Controllers;

use App\Constants\PaymentGateway;
use App\Constants\PaymentStatus;
use App\Contracts\PaymentService;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Site;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use function Laravel\Prompts\alert;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request): RedirectResponse
    {
        dd(Auth::user());
        
        $payment = new Payment();
        $payment->reference = date('ymdHis').'-'.strtoupper(Str::random(4));
        $payment->locale = $request->locale;
        $payment->amount = $request->total;
        $payment->description = $request->description;
        $payment->currency = $request->currency;
        $payment->gateway = PaymentGateway::PLACETOPAY;
        $payment->site()->associate($request->site_id);
        $payment->user()->associate(Auth::user()->id);
        $payment->status = PaymentStatus::PENDING; #Por qué esto está en pending? #Imagino que por si pasa algo, que quede en pendiente

        $payment->save();

        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class, [
            'payment' => $payment,
            'gateway' => $request->gateway,
        ]);

        $response = $paymentService->create([
            'name' => $request->name,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'document_number' => $request->document_number,
            'document_type' => $request->document_type,
        ]);

        return redirect()->away($response->url);
    }

    public function show(Request $request, Payment $payment): View
    {
        #dd($request->all());
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
}
