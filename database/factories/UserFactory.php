<?php

namespace Database\Factories;

use App\Constants\DocumentTypes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password = null;

    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'document_type' => $this->faker->randomElement(array_column(DocumentTypes::cases(), 'name')),
            'document' => $this->faker->unique()->regexify('[A-Z0-9]{10}'),
            'email_verified_at' => $this->faker->optional()->dateTime(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
