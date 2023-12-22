<?php

namespace Database\Factories;

use App\Options\ModelResourceStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 600),
            'price' =>  fake()->numberBetween(1000, 99999),
            'delivery_charge' =>  fake()->numberBetween(100, 99999),
            'delivery_location' =>  fake()->city(),
            'status' => ModelResourceStatus::PENDING,

        ];
    }
}
