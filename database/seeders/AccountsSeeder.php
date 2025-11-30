<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            // Pusat Jakarta (Branch 1)
            [
                'name' => 'Kas Toko Pusat',
                'type' => 'cash',
                'balance' => 5000000.00,
                'user_id' => 1, // Super Admin
            ],
            [
                'name' => 'Bank BCA Pusat',
                'type' => 'checking',
                'balance' => 25000000.00,
                'user_id' => 1,
            ],
            [
                'name' => 'E-Wallet Pusat',
                'type' => 'checking',
                'balance' => 1000000.00,
                'user_id' => 1,
            ],

            // Bandung (Branch 2)
            [
                'name' => 'Kas Toko Bandung',
                'type' => 'cash',
                'balance' => 3000000.00,
                'user_id' => 3, // Manager Bandung
            ],
            [
                'name' => 'Bank BCA Bandung',
                'type' => 'checking',
                'balance' => 15000000.00,
                'user_id' => 3,
            ],
            [
                'name' => 'E-Wallet Bandung',
                'type' => 'checking',
                'balance' => 750000.00,
                'user_id' => 3,
            ],

            // Surabaya (Branch 3)
            [
                'name' => 'Kas Toko Surabaya',
                'type' => 'cash',
                'balance' => 4000000.00,
                'user_id' => 4, // Kasir Surabaya
            ],
            [
                'name' => 'Bank BCA Surabaya',
                'type' => 'checking',
                'balance' => 20000000.00,
                'user_id' => 4,
            ],
            [
                'name' => 'E-Wallet Surabaya',
                'type' => 'checking',
                'balance' => 500000.00,
                'user_id' => 4,
            ],
        ];

        foreach ($accounts as $account) {
            Account::create($account);
        }
    }
}
