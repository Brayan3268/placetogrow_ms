<?php

namespace App\Contracts;

use App\Models\Payment;
use App\Services\Payments\PaymentResponse;

interface PaymentService
{
    public function create(array $buyer): PaymentResponse;

    public function query(): Payment;
}
