<?php

namespace App\Providers;

use App\Constants\PaymentGateway;
use App\Contracts\PaymentGateway as PaymentGatewayContract;
use App\Contracts\PaymentService as PaymentServiceContract;
use App\Services\Payments\Gateways\PlacetopayGateway;
use App\Services\Payments\PaymentService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentServiceContract::class, function (Application $app, array $data) {
            $gateway = $data['gateway'] ?? null;
            $payment = $data['payment'] ?? null;

            $gateway = $app->make(PaymentGatewayContract::class, ['gateway' => PaymentGateway::return_ptp()]);

            return new PaymentService($payment, $gateway);
        });

        $this->app->bind(PaymentGatewayContract::class, function (Application $app, array $data) {

            $gateway = $data['gateway'] ?? null;

            if (! isset($gateway)) {
                throw new \InvalidArgumentException('Gateway key is missing in data.');
            }

            return match (PaymentGateway::from($data['gateway'])) {
                PaymentGateway::PLACETOPAY => new PlacetopayGateway,
            };
        });
    }

    public function boot(): void
    {
        //
    }
}
