<?php

namespace Database\Factories;

use App\Constants\CurrencyTypes;
use App\Constants\InvoiceStatus;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reference' => $this->faker->unique()->uuid,
            'amount' => $this->faker->numberBetween(1000, 100000),
            'currency' => $this->faker->randomElement(CurrencyTypes::toArray()),
            'status' => $this->faker->randomElement(InvoiceStatus::toArray()),
            'site_id' => Site::factory(),
            'user_id' => User::factory(),
            'payment_id' => $this->faker->optional()->randomNumber(),
            'date_created' => $this->faker->date(),
            'date_surcharge' => $this->faker->date(),
            'amount_surcharge' => $this->faker->numberBetween(0, 10000),
            'date_expiration' => $this->faker->date(),
        ];
    }
}
