<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all branches
        $branches = Branch::all();

        if ($branches->isEmpty()) {
            $this->command->info('No branches found. Please run BranchesSeeder first.');
            return;
        }

        // Create COA for each branch
        foreach ($branches as $branch) {
            $this->createChartOfAccountsForBranch($branch);
        }

        $this->command->info('Chart of Accounts seeded successfully for all branches.');
    }

    private function createChartOfAccountsForBranch(Branch $branch)
    {
        // Note: Current COA table doesn't have branch_id, so we'll create global COA
        // In future, we can modify this to be branch-specific
        $accounts = [
            // === ASSETS ===
            [
                'code' => '1000',
                'name' => 'AKTIVA',
                'type' => 'asset',
                'category' => 'current_asset',
                'level' => 1,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'Header untuk semua aktiva',
            ],
            [
                'code' => '1100',
                'name' => 'AKTIVA LANCAR',
                'type' => 'asset',
                'category' => 'current_asset',
                'level' => 2,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'Aktiva yang dapat dicairkan dalam 1 tahun',
            ],
            [
                'code' => '1110',
                'name' => 'Kas dan Setara Kas',
                'type' => 'asset',
                'category' => 'current_asset',
                'level' => 3,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'Kas, bank, dan setara kas',
            ],
            [
                'code' => '1111',
                'name' => 'Kas Toko',
                'type' => 'asset',
                'category' => 'current_asset',
                'level' => 4,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'Kas fisik di toko',
            ],
            [
                'code' => '1112',
                'name' => 'Bank BCA',
                'type' => 'asset',
                'category' => 'current_asset',
                'level' => 4,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'Rekening bank BCA',
            ],
            [
                'code' => '1120',
                'name' => 'Piutang Usaha',
                'type' => 'asset',
                'category' => 'current_asset',
                'level' => 3,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'Piutang dari penjualan',
            ],
            [
                'code' => '1130',
                'name' => 'Persediaan',
                'type' => 'asset',
                'category' => 'current_asset',
                'level' => 3,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'Barang dagangan',
            ],

            // === LIABILITIES ===
            [
                'code' => '2000',
                'name' => 'KEWAJIBAN',
                'type' => 'liability',
                'category' => 'current_liability',
                'level' => 1,
                'normal_balance' => 'credit',
                'is_active' => true,
                'description' => 'Header untuk semua kewajiban',
            ],
            [
                'code' => '2100',
                'name' => 'KEWAJIBAN LANCAR',
                'type' => 'liability',
                'category' => 'current_liability',
                'level' => 2,
                'normal_balance' => 'credit',
                'is_active' => true,
                'description' => 'Kewajiban yang harus dibayar dalam 1 tahun',
            ],
            [
                'code' => '2110',
                'name' => 'Utang Usaha',
                'type' => 'liability',
                'category' => 'current_liability',
                'level' => 3,
                'normal_balance' => 'credit',
                'is_active' => true,
                'description' => 'Utang dari pembelian',
            ],

            // === EQUITY ===
            [
                'code' => '3000',
                'name' => 'MODAL',
                'type' => 'equity',
                'category' => 'owner_equity',
                'level' => 1,
                'normal_balance' => 'credit',
                'is_active' => true,
                'description' => 'Header untuk modal pemilik',
            ],
            [
                'code' => '3100',
                'name' => 'Modal Pemilik',
                'type' => 'equity',
                'category' => 'owner_equity',
                'level' => 2,
                'normal_balance' => 'credit',
                'is_active' => true,
                'description' => 'Modal awal dan tambahan',
            ],
            [
                'code' => '3200',
                'name' => 'Laba Ditahan',
                'type' => 'equity',
                'category' => 'retained_earnings',
                'level' => 2,
                'normal_balance' => 'credit',
                'is_active' => true,
                'description' => 'Laba yang ditahan dalam perusahaan',
            ],

            // === REVENUE ===
            [
                'code' => '4000',
                'name' => 'PENDAPATAN',
                'type' => 'revenue',
                'category' => 'sales_revenue',
                'level' => 1,
                'normal_balance' => 'credit',
                'is_active' => true,
                'description' => 'Header untuk semua pendapatan',
            ],
            [
                'code' => '4100',
                'name' => 'Pendapatan Penjualan',
                'type' => 'revenue',
                'category' => 'sales_revenue',
                'level' => 2,
                'normal_balance' => 'credit',
                'is_active' => true,
                'description' => 'Pendapatan dari penjualan produk',
            ],
            [
                'code' => '4110',
                'name' => 'Penjualan Produk',
                'type' => 'revenue',
                'category' => 'sales_revenue',
                'level' => 3,
                'normal_balance' => 'credit',
                'is_active' => true,
                'description' => 'Penjualan produk utama',
            ],
            [
                'code' => '4120',
                'name' => 'Jasa',
                'type' => 'revenue',
                'category' => 'other_revenue',
                'level' => 3,
                'normal_balance' => 'credit',
                'is_active' => true,
                'description' => 'Pendapatan dari jasa',
            ],
            [
                'code' => '4130',
                'name' => 'Pendapatan Sewa',
                'type' => 'revenue',
                'category' => 'other_revenue',
                'level' => 3,
                'normal_balance' => 'credit',
                'is_active' => true,
                'description' => 'Pendapatan dari sewa properti',
            ],

            // === EXPENSES ===
            [
                'code' => '5000',
                'name' => 'BEBAN',
                'type' => 'expense',
                'category' => 'operating_expense',
                'level' => 1,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'Header untuk semua beban',
            ],
            [
                'code' => '5100',
                'name' => 'Beban Pokok Penjualan',
                'type' => 'expense',
                'category' => 'cost_of_goods_sold',
                'level' => 2,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'Harga pokok penjualan',
            ],
            [
                'code' => '5110',
                'name' => 'Harga Pokok Penjualan',
                'type' => 'expense',
                'category' => 'cost_of_goods_sold',
                'level' => 3,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'HPP produk terjual',
            ],
            [
                'code' => '5200',
                'name' => 'Beban Operasional',
                'type' => 'expense',
                'category' => 'operating_expense',
                'level' => 2,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'Beban operasional sehari-hari',
            ],
            [
                'code' => '5210',
                'name' => 'Gaji Karyawan',
                'type' => 'expense',
                'category' => 'operating_expense',
                'level' => 3,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'Gaji dan tunjangan karyawan',
            ],
            [
                'code' => '5220',
                'name' => 'Biaya Operasional',
                'type' => 'expense',
                'category' => 'operating_expense',
                'level' => 3,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'Biaya operasional toko',
            ],
            [
                'code' => '5230',
                'name' => 'Sewa Tempat',
                'type' => 'expense',
                'category' => 'operating_expense',
                'level' => 3,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'Biaya sewa tempat usaha',
            ],
            [
                'code' => '5240',
                'name' => 'Transportasi',
                'type' => 'expense',
                'category' => 'operating_expense',
                'level' => 3,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'Biaya pengiriman dan transportasi',
            ],
            [
                'code' => '5250',
                'name' => 'Pembelian Bahan',
                'type' => 'expense',
                'category' => 'operating_expense',
                'level' => 3,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'Pembelian bahan baku',
            ],
            [
                'code' => '5260',
                'name' => 'Administrasi Bank',
                'type' => 'expense',
                'category' => 'operating_expense',
                'level' => 3,
                'normal_balance' => 'debit',
                'is_active' => true,
                'description' => 'Biaya administrasi perbankan',
            ],
        ];

        // Set parent relationships
        $parentMap = [];

        foreach ($accounts as $accountData) {
            // Only create if account doesn't exist
            $existingAccount = \App\Models\ChartOfAccount::where('code', $accountData['code'])->first();

            if (!$existingAccount) {
                $account = \App\Models\ChartOfAccount::create($accountData);

                // Store parent relationships for later
                if ($account->level > 1) {
                    $parentCode = substr($account->code, 0, -1 * strlen((string)($account->level - 1)));
                    $parentMap[$account->id] = $parentCode;
                }
            }
        }

        // Update parent relationships
        foreach ($parentMap as $accountId => $parentCode) {
            $parent = \App\Models\ChartOfAccount::where('code', $parentCode)->first();
            if ($parent) {
                \App\Models\ChartOfAccount::where('id', $accountId)->update(['parent_id' => $parent->id]);
            }
        }
    }
}
