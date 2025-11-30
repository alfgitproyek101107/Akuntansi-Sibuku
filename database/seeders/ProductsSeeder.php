<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Products
            [
                'name' => 'Produk A',
                'sku' => 'PRD-A-001',
                'description' => 'Produk A - Barang elektronik',
                'price' => 150000.00,
                'cost_price' => 100000.00,
                'stock_quantity' => 50,
                'unit' => 'pcs',
                'product_category_id' => 1, // Elektronik
                'user_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Produk B',
                'sku' => 'PRD-B-002',
                'description' => 'Produk B - Pakaian',
                'price' => 250000.00,
                'cost_price' => 150000.00,
                'stock_quantity' => 30,
                'unit' => 'pcs',
                'product_category_id' => 2, // Pakaian
                'user_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Produk C',
                'sku' => 'PRD-C-003',
                'description' => 'Produk C - Aksesoris',
                'price' => 75000.00,
                'cost_price' => 45000.00,
                'stock_quantity' => 100,
                'unit' => 'pcs',
                'product_category_id' => 2, // Pakaian
                'user_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Produk D',
                'sku' => 'PRD-D-004',
                'description' => 'Produk D - Buku',
                'price' => 50000.00,
                'cost_price' => 30000.00,
                'stock_quantity' => 75,
                'unit' => 'pcs',
                'product_category_id' => 3, // Buku & Alat Tulis
                'user_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Produk E',
                'sku' => 'PRD-E-005',
                'description' => 'Produk E - Alat tulis',
                'price' => 25000.00,
                'cost_price' => 15000.00,
                'stock_quantity' => 200,
                'unit' => 'pcs',
                'product_category_id' => 3, // Buku & Alat Tulis
                'user_id' => 1,
                'is_active' => true,
            ],

            // Services
            [
                'name' => 'Layanan Konsultasi',
                'sku' => 'SRV-KONS-001',
                'description' => 'Layanan konsultasi bisnis',
                'price' => 500000.00,
                'cost_price' => 0.00,
                'stock_quantity' => 0,
                'unit' => 'jam',
                'product_category_id' => 4, // Layanan
                'user_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Layanan Perbaikan',
                'sku' => 'SRV-REPAIR-002',
                'description' => 'Layanan perbaikan elektronik',
                'price' => 150000.00,
                'cost_price' => 0.00,
                'stock_quantity' => 0,
                'unit' => 'unit',
                'product_category_id' => 4, // Layanan
                'user_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Layanan Training',
                'sku' => 'SRV-TRAIN-003',
                'description' => 'Layanan training dan workshop',
                'price' => 1000000.00,
                'cost_price' => 0.00,
                'stock_quantity' => 0,
                'unit' => 'sesi',
                'product_category_id' => 4, // Layanan
                'user_id' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
