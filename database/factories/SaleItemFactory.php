<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleItem>
 */
class SaleItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 5);
        $unitPrice = fake()->randomFloat(2, 5000, 500000);
        $discountAmount = fake()->randomFloat(2, 0, $unitPrice * $quantity * 0.1);
        $totalPrice = ($unitPrice * $quantity) - $discountAmount;

        return [
            'sale_id' => Sale::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'discount_amount' => $discountAmount,
            'total_price' => $totalPrice,
        ];
    }

    /**
     * Indicate that the item has no discount.
     */
    public function noDiscount(): static
    {
        return $this->state(function (array $attributes) {
            $quantity = $attributes['quantity'] ?? 1;
            $unitPrice = $attributes['unit_price'] ?? fake()->randomFloat(2, 5000, 500000);
            
            return [
                'discount_amount' => 0,
                'total_price' => $unitPrice * $quantity,
            ];
        });
    }

    /**
     * Indicate that the item has a high discount.
     */
    public function highDiscount(): static
    {
        return $this->state(function (array $attributes) {
            $quantity = $attributes['quantity'] ?? 1;
            $unitPrice = $attributes['unit_price'] ?? fake()->randomFloat(2, 5000, 500000);
            $discountAmount = fake()->randomFloat(2, $unitPrice * $quantity * 0.2, $unitPrice * $quantity * 0.5);
            
            return [
                'discount_amount' => $discountAmount,
                'total_price' => ($unitPrice * $quantity) - $discountAmount,
            ];
        });
    }
}