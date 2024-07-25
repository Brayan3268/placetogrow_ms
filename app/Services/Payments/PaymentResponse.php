<?php

namespace App\Services\Payments;

class PaymentResponse
{
    public function __construct(
        public readonly int $process_identifier,
        public readonly string $url,
    ) {}

    public function toArray(): array
    {
        return [
            'process_identifier' => $this->process_identifier,
            'url' => $this->url,
        ];
    }
}
