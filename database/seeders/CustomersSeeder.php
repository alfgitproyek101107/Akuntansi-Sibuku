<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Andi Wijaya',
                'email' => 'andi.wijaya@email.com',
                'phone' => '081234567890',
                'address' => 'Jl. Malioboro No. 123, Yogyakarta',
                'user_id' => 1,
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'phone' => '081345678901',
                'address' => 'Jl. Sudirman No. 456, Jakarta',
                'user_id' => 1,
            ],
            [
                'name' => 'Sari Dewi',
                'email' => 'sari.dewi@email.com',
                'phone' => '081456789012',
                'address' => 'Jl. Asia Afrika No. 789, Bandung',
                'user_id' => 3,
            ],
            [
                'name' => 'Rina Kusuma',
                'email' => 'rina.kusuma@email.com',
                'phone' => '081567890123',
                'address' => 'Jl. Tunjungan No. 321, Surabaya',
                'user_id' => 4,
            ],
            [
                'name' => 'Tono Hartono',
                'email' => 'tono.hartono@email.com',
                'phone' => '081678901234',
                'address' => 'Jl. Malioboro No. 654, Yogyakarta',
                'user_id' => 1,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
