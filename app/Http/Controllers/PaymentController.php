<?php

namespace App\Http\Controllers;

use App\Constants\PaymentGateway;
use App\Constants\PaymentStatus;
use App\Contracts\PaymentService;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $payment = new Payment();
        $payment->reference = date('ymdHis').'-'.strtoupper(Str::random(4));
        $payment->description = $request->description;
        $payment->amount = $request->amount;
        $payment->currency = $request->currency;
        $payment->gateway = PaymentGateway::PLACETOPAY;
        $payment->status = PaymentStatus::PENDING;
        $payment->site()->associate($request->site);
        $payment->user()->associate($request->user);

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

    public function show(Payment $payment): View
    {
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
