<?php

namespace App\Http\Controllers;

use App\Constants\InvoiceStatus;
use App\Constants\PaymentStatus;
use App\Contracts\PaymentService;
use App\Http\PersistantsLowLevel\InvoicePll;
use App\Http\PersistantsLowLevel\PaymentPll;
use App\Http\PersistantsLowLevel\UserPll;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        }

        $invoice_id = intval($request->query('invoice_id'));
        $payment_id = intval($payment->id);
        $status = '';

        if ($invoice_id == 0) {
            $invoice = InvoicePll::get_especific_invoice_with_pay_id($payment_id);
            $status = ($invoice !== '') ? $invoice->status : '';
        } else {
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

            $invoice = InvoicePll::update_invoice($invoice_id, $status, $payment_id);
        }

        return view('payments.show', [
            'payment' => $payment,
            'invoice_status' => $status,
            'invoice' => $invoice,
        ]);
    }

    private function validate_role(): bool
    {
        $role_name = UserPll::get_user_auth();

        return ($role_name[0] === 'super_admin' || $role_name[0] === 'admin') ? true : false;
    }
}
