<?php

namespace App\Services\Payments\Gateways;

use App\Contracts\PaymentGateway;
use App\Models\Payment;
use App\Services\Payments\PaymentResponse;
use App\Services\Payments\QueryPaymentResponse;
use Illuminate\Support\Facades\Http;

class PlacetopayGateway implements PaymentGateway
{
    private array $data;

    private array $config;

    public function __construct()
    {
        $this->data = [
            'expiration' => now()->addHour()->format('c'),
            'ipAddress' => request()->ip(),
            'userAgent' => request()->userAgent(),
        ];

        $this->config = config('gateways.placetopay');
    }

    public function prepare(): self
    {
        $login = $this->config['login'];
        $secretKey = $this->config['secret_key'];
        $seed = date('c');
        $nonce = (string) rand();

        $tranKey = base64_encode(hash('sha256', $nonce.$seed.$secretKey, true));

        $nonce = base64_encode($nonce);

        $this->data['auth'] = [
            'login' => $login,
            'tranKey' => $tranKey,
            'nonce' => $nonce,
            'seed' => $seed,
        ];

        return $this;
    }

    public function buyer(array $data): self
    {
        $this->data['buyer'] = [
            'name' => $data['name'],
            'surname' => $data['last_name'],
            'email' => $data['email'],
            'document' => $data['document'],
            'documentType' => $data['document_type'],
            'address' => [
                'phone' => $data['phone'],
            ],
        ];

        return $this;
    }

    public function payment(Payment $payment): self
    {
        //dd($payment);
        $this->data['locale'] = $payment->locale;

        $this->data['payment'] = [
            'reference' => $payment->reference,
            'description' => $payment->description,
            'amount' => [
                'currency' => $payment->currency,
                'total' => $payment->amount,
            ],
            //'expiration' => $payment->expiration,
        ];

        $this->data['returnUrl'] = route('payment.show', [
            'payment' => $payment,
        ]);

        $invoice_id = $payment->getAttribute('invoice_id');
        //$invoice_id = $payment->invoice_id;

        $this->data['returnUrl'] = route('payment.show', compact('payment', 'invoice_id'));

        //dd($this->data);

        return $this;
    }

    public function process(): PaymentResponse
    {
        $response = Http::post($this->config['url'], $this->data);

        //AQUI HAY QUE HACER QUE EL PAYMENTRESPONSE EVALUE LAS DIFERENTES RESPUESTAS, ESTE SOLO ES EL CAMINO FELIZ
        $response = $response->json();

        return new PaymentResponse($response['requestId'], $response['processUrl']);
    }

    public function get(Payment $payment): QueryPaymentResponse
    {
        $url = $this->config['url'].'/'.$payment->process_identifier;

        $response = Http::post($url, $this->data);
        $response = $response->json();
        //dd($response);
        $status = $response['status'];

        return new QueryPaymentResponse($status['reason'], $status['status']);
    }
}
