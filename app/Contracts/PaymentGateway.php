<?php

namespace App\Contracts;

use App\Models\Payment;
use App\Services\Payments\PaymentResponse;
use App\Services\Payments\QueryPaymentResponse;

interface PaymentGateway
{
    public function prepare(): self;

    public function buyer(array $buyer): self;

    public function payment(Payment $payment): self;

    public function process(): PaymentResponse;

    public function get(Payment $payment): QueryPaymentResponse;
}
