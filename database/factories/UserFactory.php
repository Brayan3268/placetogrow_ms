<?php

namespace Database\Factories;

use App\Constants\DocumentTypes;
use App\Models\User; // Asegúrate de importar el modelo User
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password = null;

    protected $model = User::class; // Asegúrate de definir el modelo

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'document' => $this->faker->numerify('##########'),
            'document_type' => $this->faker->randomElement(array_column(DocumentTypes::cases(), 'value')),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}
