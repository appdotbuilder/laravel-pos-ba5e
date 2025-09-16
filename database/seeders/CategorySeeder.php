<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik',
                'slug' => 'elektronik',
                'description' => 'Produk elektronik seperti smartphone, laptop, dan aksesoris teknologi.',
            ],
            [
                'name' => 'Pakaian',
                'slug' => 'pakaian',
                'description' => 'Berbagai jenis pakaian pria, wanita, dan anak-anak.',
            ],
            [
                'name' => 'Makanan & Minuman',
                'slug' => 'makanan-minuman',
                'description' => 'Produk makanan dan minuman kemasan maupun fresh.',
            ],
            [
                'name' => 'Kesehatan & Kecantikan',
                'slug' => 'kesehatan-kecantikan',
                'description' => 'Produk perawatan kesehatan dan kecantikan.',
            ],
            [
                'name' => 'Rumah Tangga',
                'slug' => 'rumah-tangga',
                'description' => 'Peralatan dan perlengkapan rumah tangga.',
            ],
            [
                'name' => 'Olahraga',
                'slug' => 'olahraga',
                'description' => 'Peralatan olahraga dan aktivitas fisik.',
            ],
            [
                'name' => 'Otomotif',
                'slug' => 'otomotif',
                'description' => 'Aksesoris dan spare part kendaraan.',
            ],
            [
                'name' => 'Buku & Alat Tulis',
                'slug' => 'buku-alat-tulis',
                'description' => 'Buku, majalah, dan alat tulis kantor.',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create additional random categories
        Category::factory(5)->create();
    }
}