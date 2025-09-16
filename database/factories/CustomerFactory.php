<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->optional(0.8)->safeEmail(),
            'phone' => fake()->optional(0.9)->phoneNumber(),
            'address' => fake()->optional(0.6)->address(),
            'total_spent' => fake()->randomFloat(2, 0, 10000000),
            'total_orders' => fake()->numberBetween(0, 50),
            'last_order_at' => fake()->optional(0.8)->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the customer is a VIP.
     */
    public function vip(): static
    {
        return $this->state(fn (array $attributes) => [
            'total_spent' => fake()->randomFloat(2, 5000000, 20000000),
            'total_orders' => fake()->numberBetween(20, 100),
            'last_order_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Indicate that the customer is new.
     */
    public function newCustomer(): static
    {
        return $this->state(fn (array $attributes) => [
            'total_spent' => fake()->randomFloat(2, 0, 500000),
            'total_orders' => fake()->numberBetween(0, 3),
            'last_order_at' => fake()->optional(0.3)->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    /**
     * Indicate that the customer is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_order_at' => fake()->dateTimeBetween('-2 years', '-6 months'),
        ]);
    }
}