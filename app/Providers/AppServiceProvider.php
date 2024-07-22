<?php

namespace App\Providers;

use App\Constants\PaymentGateway;
use App\Contracts\PaymentGateway as PaymentGatewayContract;
use App\Contracts\PaymentService as PaymentServiceContract;
use App\Services\Payments\Gateways\PayPalGateway;
use App\Services\Payments\Gateways\PlacetopayGateway;
use App\Services\Payments\PaymentService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentServiceContract::class, function (Application $app, array $data) {
            ['payment' => $payment, 'gateway' => $gateway] = $data;

            $gateway = $app->make(PaymentGatewayContract::class, ['gateway' => PaymentGateway::test()]);

            return new PaymentService($payment, $gateway);
        });

        $this->app->bind(PaymentGatewayContract::class, function (Application $app, array $data) {
            return match (PaymentGateway::from($data['gateway'])) {
                PaymentGateway::PLACETOPAY => new PlacetopayGateway(),
                PaymentGateway::PAYPAL => new PayPalGateway(),
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
