<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [
            // Income transactions
            [
                'date' => '2025-11-01',
                'type' => 'income',
                'amount' => 2500000.00,
                'description' => 'Penjualan produk A ke customer Andi',
                'account_id' => 1, // Kas Toko Pusat
                'category_id' => 1, // Penjualan Produk
                'user_id' => 1,
                'tax_rate' => 11.0,
                'tax_amount' => 250000.00,
            ],
            [
                'date' => '2025-11-02',
                'type' => 'income',
                'amount' => 1500000.00,
                'description' => 'Pembayaran jasa konsultasi',
                'account_id' => 2, // Bank BCA Pusat
                'category_id' => 2, // Jasa
                'user_id' => 1,
                'tax_rate' => 11.0,
                'tax_amount' => 150000.00,
            ],
            [
                'date' => '2025-11-03',
                'type' => 'income',
                'amount' => 500000.00,
                'description' => 'Pendapatan sewa properti',
                'account_id' => 2, // Bank BCA Pusat
                'category_id' => 3, // Sewa
                'user_id' => 1,
                'tax_rate' => 11.0,
                'tax_amount' => 50000.00,
            ],

            // Expense transactions
            [
                'date' => '2025-11-01',
                'type' => 'expense',
                'amount' => 3000000.00,
                'description' => 'Gaji karyawan bulan November',
                'account_id' => 2, // Bank BCA Pusat
                'category_id' => 5, // Gaji Karyawan
                'user_id' => 1,
                'tax_rate' => 0.0,
                'tax_amount' => 0.00,
            ],
            [
                'date' => '2025-11-02',
                'type' => 'expense',
                'amount' => 500000.00,
                'description' => 'Biaya operasional toko',
                'account_id' => 1, // Kas Toko Pusat
                'category_id' => 6, // Operasional
                'user_id' => 1,
                'tax_rate' => 0.0,
                'tax_amount' => 0.00,
            ],
            [
                'date' => '2025-11-03',
                'type' => 'expense',
                'amount' => 2000000.00,
                'description' => 'Sewa tempat bulan November',
                'account_id' => 2, // Bank BCA Pusat
                'category_id' => 7, // Sewa Tempat
                'user_id' => 1,
                'tax_rate' => 0.0,
                'tax_amount' => 0.00,
            ],
            [
                'date' => '2025-11-04',
                'type' => 'expense',
                'amount' => 750000.00,
                'description' => 'Pembelian bahan baku',
                'account_id' => 1, // Kas Toko Pusat
                'category_id' => 8, // Pembelian Bahan
                'user_id' => 1,
                'tax_rate' => 11.0,
                'tax_amount' => 75000.00,
            ],

            // Additional expense transactions (transfers not supported in current schema)
            [
                'date' => '2025-11-05',
                'type' => 'expense',
                'amount' => 500000.00,
                'description' => 'Biaya administrasi bank',
                'account_id' => 2, // Bank BCA Pusat
                'category_id' => 6, // Operasional
                'user_id' => 1,
                'tax_rate' => 0.0,
                'tax_amount' => 0.00,
            ],
            [
                'date' => '2025-11-06',
                'type' => 'expense',
                'amount' => 250000.00,
                'description' => 'Biaya pengiriman',
                'account_id' => 1, // Kas Toko Pusat
                'category_id' => 9, // Transportasi
                'user_id' => 1,
                'tax_rate' => 0.0,
                'tax_amount' => 0.00,
            ],

            // More transactions for Bandung branch
            [
                'date' => '2025-11-01',
                'type' => 'income',
                'amount' => 1800000.00,
                'description' => 'Penjualan produk di Bandung',
                'account_id' => 4, // Kas Toko Bandung
                'category_id' => 1, // Penjualan Produk
                'user_id' => 3,
                'tax_rate' => 11.0,
                'tax_amount' => 180000.00,
            ],
            [
                'date' => '2025-11-02',
                'type' => 'expense',
                'amount' => 800000.00,
                'description' => 'Biaya operasional Bandung',
                'account_id' => 5, // Bank BCA Bandung
                'category_id' => 6, // Operasional
                'user_id' => 3,
                'tax_rate' => 0.0,
                'tax_amount' => 0.00,
            ],

            // More transactions for Surabaya branch
            [
                'date' => '2025-11-01',
                'type' => 'income',
                'amount' => 2200000.00,
                'description' => 'Penjualan produk di Surabaya',
                'account_id' => 7, // Kas Toko Surabaya
                'category_id' => 1, // Penjualan Produk
                'user_id' => 4,
                'tax_rate' => 11.0,
                'tax_amount' => 220000.00,
            ],
            [
                'date' => '2025-11-03',
                'type' => 'expense',
                'amount' => 600000.00,
                'description' => 'Biaya transportasi Surabaya',
                'account_id' => 8, // Bank BCA Surabaya
                'category_id' => 9, // Transportasi
                'user_id' => 4,
                'tax_rate' => 0.0,
                'tax_amount' => 0.00,
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}
