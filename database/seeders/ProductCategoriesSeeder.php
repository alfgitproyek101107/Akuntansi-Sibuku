<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik',
                'description' => 'Produk elektronik dan gadget',
                'user_id' => 1,
            ],
            [
                'name' => 'Pakaian',
                'description' => 'Pakaian dan aksesoris fashion',
                'user_id' => 1,
            ],
            [
                'name' => 'Buku & Alat Tulis',
                'description' => 'Buku, alat tulis, dan perlengkapan sekolah',
                'user_id' => 1,
            ],
            [
                'name' => 'Layanan',
                'description' => 'Layanan konsultasi dan jasa',
                'user_id' => 1,
            ],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }
    }
}
