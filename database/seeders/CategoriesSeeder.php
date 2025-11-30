<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Income Categories
            [
                'name' => 'Penjualan Produk',
                'type' => 'income',
                'user_id' => 1,
            ],
            [
                'name' => 'Jasa',
                'type' => 'income',
                'user_id' => 1,
            ],
            [
                'name' => 'Sewa',
                'type' => 'income',
                'user_id' => 1,
            ],
            [
                'name' => 'Pendapatan Lain',
                'type' => 'income',
                'user_id' => 1,
            ],

            // Expense Categories
            [
                'name' => 'Gaji Karyawan',
                'type' => 'expense',
                'user_id' => 1,
            ],
            [
                'name' => 'Operasional',
                'type' => 'expense',
                'user_id' => 1,
            ],
            [
                'name' => 'Sewa Tempat',
                'type' => 'expense',
                'user_id' => 1,
            ],
            [
                'name' => 'Pembelian Bahan',
                'type' => 'expense',
                'user_id' => 1,
            ],
            [
                'name' => 'Transportasi',
                'type' => 'expense',
                'user_id' => 1,
            ],
            [
                'name' => 'Utilitas',
                'type' => 'expense',
                'user_id' => 1,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
