<?php

namespace Database\Factories\Weather\Infrastructure;

use App\Weather\Infrastructure\Weather;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Weather\Infrastructure\Weather>
 */
class WeatherFactory extends Factory
{

    protected $model = Weather::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => $this->faker->name,
            "body" => json_encode(["key" => "value"]),
        ];
    }
}
