<?php

namespace Database\Factories;

use App\Models\User;
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
            'user_id' => User::inRandomOrder()->first()->id,
            'archived' => rand(0, 1),
            'order_type' => $this->faker->randomElement(['pickup', 'refund', 'exchange']),
            'order_date' => $this->faker->dateTimeBetween('-2 year', 'now')
        ];
    }
}
