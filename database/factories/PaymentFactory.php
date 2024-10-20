<?php

namespace Database\Factories;

use App\Constants\CurrencyTypes;
use App\Constants\LocalesTypes;
use App\Constants\OriginPayment;
use App\Constants\PaymentGateway;
use App\Constants\PaymentStatus;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'locale' => $this->faker->randomElement(array_column(LocalesTypes::cases(), 'name')),
            'reference' => $this->faker->unique()->uuid,
            'description' => $this->faker->sentence(2),
            'amount' => $this->faker->numberBetween(1000, 100000),
            'currency' => $this->faker->randomElement(CurrencyTypes::toArray()),
            'status' => $this->faker->randomElement(PaymentStatus::toArray()),
            'gateway' => $this->faker->randomElement(PaymentGateway::toArray()),
            'process_identifier' => $this->faker->optional()->randomNumber(),
            'site_id' => Site::factory(),
            'user_id' => User::factory(),
            'url_session' => $this->faker->optional()->url(),
            'origin_payment' => $this->faker->optional()->randomElement(OriginPayment::toArray()),
        ];
    }
}
