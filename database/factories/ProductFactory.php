<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hargaBeli = fake()->randomFloat(2, 5000, 500000);
        $profitMargin = fake()->randomFloat(2, 0.2, 0.8); // 20% to 80% profit
        $hargaJual = $hargaBeli * (1 + $profitMargin);

        return [
            'sku' => fake()->unique()->bothify('SKU-###-???-####'),
            'name' => fake()->randomElement([
                'Mouse Gaming RGB', 'Keyboard Mechanical', 'Monitor LED 24"',
                'Speaker Bluetooth', 'Headset Wireless', 'Powerbank 10000mAh',
                'Kabel USB Type-C', 'Flash Drive 32GB', 'Webcam HD 1080p',
                'Printer Inkjet', 'Router WiFi 6', 'Smart Watch',
                'Earbuds TWS', 'Charger Fast Charging', 'Stand Laptop'
            ]),
            'category_id' => Category::factory(),
            'harga_beli' => $hargaBeli,
            'harga_jual' => round($hargaJual, 2),
            'stok' => fake()->numberBetween(0, 100),
            'barcode' => fake()->optional(0.7)->ean13(),
            'description' => fake()->optional(0.8)->paragraph(),
            'is_active' => fake()->boolean(95),
        ];
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stok' => 0,
        ]);
    }

    /**
     * Indicate that the product has low stock.
     */
    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stok' => fake()->numberBetween(1, 5),
        ]);
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the product is expensive.
     */
    public function expensive(): static
    {
        return $this->state(function (array $attributes) {
            $hargaBeli = fake()->randomFloat(2, 1000000, 5000000);
            return [
                'harga_beli' => $hargaBeli,
                'harga_jual' => round($hargaBeli * 1.3, 2), // 30% profit
            ];
        });
    }
}