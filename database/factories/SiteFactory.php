<?php

namespace Database\Factories;

use App\Constants\CurrencyTypes;
use App\Constants\SiteTypes;
use App\Models\Category;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SiteFactory extends Factory
{
    protected $model = Site::class;

    public function definition(): array
    {
        return [
            'slug' => Str::slug($this->faker->unique()->words(2, true), '-'),
            'name' => $this->faker->word(),
            'category_id' => Category::factory(),
            'expiration_time' => $this->faker->numberBetween(1, 365),
            'currency_type' => $this->faker->randomElement(CurrencyTypes::toArray()),
            'site_type' => $this->faker->randomElement(array_column(SiteTypes::cases(), 'name')),
            'image' => $this->faker->optional()->imageUrl(640, 480, 'site', true),
            'enable_at' => now(),
        ];
    }
}
