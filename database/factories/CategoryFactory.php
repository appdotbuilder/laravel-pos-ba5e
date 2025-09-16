<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Elektronik', 'Pakaian', 'Makanan & Minuman', 'Kesehatan & Kecantikan',
            'Rumah Tangga', 'Olahraga', 'Otomotif', 'Buku & Alat Tulis',
            'Peralatan Kantor', 'Mainan & Hobi', 'Perhiasan & Aksesoris', 'Gadget'
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(random_int(8, 15)),
        ];
    }

    /**
     * Indicate that the category is for electronics.
     */
    public function electronics(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Elektronik',
            'slug' => 'elektronik',
            'description' => 'Produk elektronik seperti smartphone, laptop, dan aksesoris.',
        ]);
    }

    /**
     * Indicate that the category is for food and beverages.
     */
    public function foodAndBeverage(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Makanan & Minuman',
            'slug' => 'makanan-minuman',
            'description' => 'Berbagai produk makanan dan minuman.',
        ]);
    }
}