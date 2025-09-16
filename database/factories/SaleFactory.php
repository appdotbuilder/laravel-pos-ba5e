<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 10000, 1000000);
        $discountAmount = fake()->randomFloat(2, 0, $subtotal * 0.2);
        $taxAmount = ($subtotal - $discountAmount) * 0.1; // 10% tax
        $totalAmount = $subtotal - $discountAmount + $taxAmount;
        $paidAmount = $totalAmount + fake()->randomFloat(2, 0, 50000);
        $changeAmount = $paidAmount - $totalAmount;

        return [
            'invoice_number' => 'INV' . fake()->date('Ymd') . str_pad((string)fake()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'customer_id' => fake()->optional(0.6)->randomElement(Customer::pluck('id')->toArray()) ?: null,
            'user_id' => User::factory(),
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'payment_method' => fake()->randomElement(['cash', 'card', 'digital']),
            'paid_amount' => $paidAmount,
            'change_amount' => $changeAmount,
            'notes' => fake()->optional(0.3)->sentence(),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the sale is cash payment.
     */
    public function cash(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'cash',
        ]);
    }

    /**
     * Indicate that the sale is card payment.
     */
    public function card(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'card',
            'change_amount' => 0,
        ]);
    }

    /**
     * Indicate that the sale is digital payment.
     */
    public function digital(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'digital',
            'change_amount' => 0,
        ]);
    }

    /**
     * Indicate that the sale has a high value.
     */
    public function highValue(): static
    {
        return $this->state(function (array $attributes) {
            $subtotal = fake()->randomFloat(2, 1000000, 5000000);
            $discountAmount = fake()->randomFloat(2, 0, $subtotal * 0.15);
            $taxAmount = ($subtotal - $discountAmount) * 0.1;
            $totalAmount = $subtotal - $discountAmount + $taxAmount;
            $paidAmount = $totalAmount;
            
            return [
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'change_amount' => 0,
                'payment_method' => fake()->randomElement(['card', 'digital']),
            ];
        });
    }
}