<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $elektronikCategory = Category::where('slug', 'elektronik')->first();
        $makananCategory = Category::where('slug', 'makanan-minuman')->first();
        $pakaianCategory = Category::where('slug', 'pakaian')->first();

        // Sample electronics products
        $elektronikProducts = [
            [
                'sku' => 'ELK-001',
                'name' => 'Mouse Gaming RGB Wireless',
                'category_id' => $elektronikCategory?->id,
                'harga_beli' => 150000,
                'harga_jual' => 225000,
                'stok' => 25,
                'barcode' => '8901234567890',
                'description' => 'Mouse gaming wireless dengan lampu RGB dan sensor presisi tinggi.',
                'is_active' => true,
            ],
            [
                'sku' => 'ELK-002',
                'name' => 'Keyboard Mechanical Blue Switch',
                'category_id' => $elektronikCategory?->id,
                'harga_beli' => 300000,
                'harga_jual' => 450000,
                'stok' => 15,
                'barcode' => '8901234567891',
                'description' => 'Keyboard mechanical dengan switch biru untuk gaming dan typing.',
                'is_active' => true,
            ],
            [
                'sku' => 'ELK-003',
                'name' => 'Monitor LED 24 Inch Full HD',
                'category_id' => $elektronikCategory?->id,
                'harga_beli' => 1200000,
                'harga_jual' => 1800000,
                'stok' => 8,
                'barcode' => '8901234567892',
                'description' => 'Monitor LED 24 inch dengan resolusi Full HD dan refresh rate 75Hz.',
                'is_active' => true,
            ],
        ];

        // Sample food products
        $makananProducts = [
            [
                'sku' => 'FNB-001',
                'name' => 'Kopi Arabica Premium 200gr',
                'category_id' => $makananCategory?->id,
                'harga_beli' => 45000,
                'harga_jual' => 67500,
                'stok' => 50,
                'barcode' => '8901234567893',
                'description' => 'Kopi arabica premium single origin dengan cita rasa yang khas.',
                'is_active' => true,
            ],
            [
                'sku' => 'FNB-002',
                'name' => 'Teh Hijau Organik 100gr',
                'category_id' => $makananCategory?->id,
                'harga_beli' => 25000,
                'harga_jual' => 37500,
                'stok' => 30,
                'barcode' => '8901234567894',
                'description' => 'Teh hijau organik berkualitas tinggi dengan antioksidan alami.',
                'is_active' => true,
            ],
        ];

        // Sample clothing products
        $pakaianProducts = [
            [
                'sku' => 'CLT-001',
                'name' => 'T-Shirt Cotton Unisex Size M',
                'category_id' => $pakaianCategory?->id,
                'harga_beli' => 35000,
                'harga_jual' => 55000,
                'stok' => 40,
                'barcode' => '8901234567895',
                'description' => 'T-shirt cotton berkualitas tinggi dengan berbagai pilihan warna.',
                'is_active' => true,
            ],
        ];

        foreach (array_merge($elektronikProducts, $makananProducts, $pakaianProducts) as $product) {
            Product::create($product);
        }

        // Create random products for each category
        Category::all()->each(function ($category) {
            Product::factory(random_int(5, 15))
                   ->create(['category_id' => $category->id]);
        });

        // Create some low stock and out of stock products
        Product::factory(10)->lowStock()->create();
        Product::factory(5)->outOfStock()->create();
        Product::factory(3)->inactive()->create();
    }
}