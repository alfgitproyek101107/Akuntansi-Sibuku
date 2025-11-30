<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StarterSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds for basic starter data.
     */
    public function run(): void
    {
        // Create permissions and roles first
        $this->call(RolesAndPermissionsSeeder::class);

        // Create user roles if they don't exist
        $superAdminRole = \App\Models\UserRole::firstOrCreate(
            ['name' => 'super_admin'],
            ['name' => 'super_admin']
        );

        $adminRole = \App\Models\UserRole::firstOrCreate(
            ['name' => 'admin'],
            ['name' => 'admin']
        );

        // Create admin user
        $adminUser = \App\Models\User::firstOrCreate(
            ['email' => 'admin@sibuku.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
                'user_role_id' => $superAdminRole->id,
            ]
        );

        // Assign Spatie roles to users
        $adminUser->assignRole('super-admin');

        // Make sure the admin user has the super_admin role
        if (!$adminUser->user_role_id) {
            $adminUser->update(['user_role_id' => $superAdminRole->id]);
        }

        // Create a demo user if not exists
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'demo@akuntansisibuku.com'],
            [
                'name' => 'Demo User',
                'password' => bcrypt('password'),
                'user_role_id' => $adminRole->id,
            ]
        );

        // Assign Spatie role to demo user
        $user->assignRole('admin');

        // Make sure the demo user has the admin role
        if (!$user->user_role_id) {
            $user->update(['user_role_id' => $adminRole->id]);
        }

        // Create branches
        $mainBranch = \App\Models\Branch::firstOrCreate(
            ['name' => 'Cabang Utama'],
            [
                'name' => 'Cabang Utama',
                'address' => 'Jl. Raya No. 123, Jakarta',
                'phone' => '021-12345678',
                'email' => 'cabangutama@sibuku.com',
            ]
        );

        // Assign users to branch via user_branches table
        DB::table('user_branches')->updateOrInsert(
            ['user_id' => $adminUser->id, 'branch_id' => $mainBranch->id],
            [
                'role_name' => 'admin',
                'is_default' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('user_branches')->updateOrInsert(
            ['user_id' => $user->id, 'branch_id' => $mainBranch->id],
            [
                'role_name' => 'staff',
                'is_default' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create basic accounts
        $accounts = [
            [
                'name' => 'Kas Tunai',
                'type' => 'cash',
                'balance' => 2500000,
                'user_id' => $user->id,
                'branch_id' => $mainBranch->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BCA 1234567890',
                'type' => 'checking',
                'balance' => 15000000,
                'user_id' => $user->id,
                'branch_id' => $mainBranch->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tabungan Mandiri',
                'type' => 'savings',
                'balance' => 50000000,
                'user_id' => $user->id,
                'branch_id' => $mainBranch->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($accounts as $account) {
            \App\Models\Account::create($account);
        }

        // Create basic categories
        $incomeCategories = [
            'Penjualan Barang',
            'Jasa Freelance',
            'Gaji Bulanan',
            'Bonus & Komisi',
            'Pendapatan Lainnya',
        ];

        $expenseCategories = [
            'Beli Bahan Baku',
            'Transportasi',
            'Makan & Minum',
            'Utilitas (Listrik, Air)',
            'Sewa Tempat',
            'Biaya Promosi',
            'Peralatan & Supplies',
            'Biaya Lainnya',
        ];

        foreach ($incomeCategories as $category) {
            \App\Models\Category::create([
                'name' => $category,
                'type' => 'income',
                'user_id' => $user->id,
                'branch_id' => $mainBranch->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($expenseCategories as $category) {
            \App\Models\Category::create([
                'name' => $category,
                'type' => 'expense',
                'user_id' => $user->id,
                'branch_id' => $mainBranch->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create basic product categories
        $productCategory = \App\Models\ProductCategory::firstOrCreate(
            ['name' => 'Produk'],
            [
                'name' => 'Produk',
                'description' => 'Kategori untuk berbagai produk',
                'user_id' => $user->id,
            ]
        );

        $serviceCategory = \App\Models\ProductCategory::firstOrCreate(
            ['name' => 'Layanan'],
            [
                'name' => 'Layanan',
                'description' => 'Kategori untuk berbagai layanan jasa',
                'user_id' => $user->id,
            ]
        );

        // Create basic products
        $products = [
            [
                'name' => 'Produk A',
                'sku' => 'PRD-A-001',
                'description' => 'Produk A - Barang contoh',
                'price' => 50000,
                'cost_price' => 30000,
                'stock_quantity' => 10,
                'unit' => 'pcs',
                'product_category_id' => $productCategory->id,
                'user_id' => $user->id,
                'branch_id' => $mainBranch->id,
                'is_active' => true,
            ],
            [
                'name' => 'Produk B',
                'sku' => 'PRD-B-002',
                'description' => 'Produk B - Barang contoh lainnya',
                'price' => 75000,
                'cost_price' => 45000,
                'stock_quantity' => 15,
                'unit' => 'pcs',
                'product_category_id' => $productCategory->id,
                'user_id' => $user->id,
                'branch_id' => $mainBranch->id,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            \App\Models\Product::firstOrCreate(
                [
                    'name' => $productData['name'],
                    'user_id' => $productData['user_id'],
                    'branch_id' => $productData['branch_id']
                ],
                $productData
            );
        }

        // Create basic services
        $services = [
            [
                'name' => 'Konsultasi Bisnis',
                'description' => 'Layanan konsultasi dan advisory bisnis',
                'price' => 500000,
                'product_category_id' => $serviceCategory->id,
                'user_id' => $user->id,
                'branch_id' => $mainBranch->id,
                'is_active' => true,
            ],
            [
                'name' => 'Jasa Desain',
                'description' => 'Layanan desain grafis dan branding',
                'price' => 750000,
                'product_category_id' => $serviceCategory->id,
                'user_id' => $user->id,
                'branch_id' => $mainBranch->id,
                'is_active' => true,
            ],
            [
                'name' => 'Training & Workshop',
                'description' => 'Layanan pelatihan dan workshop',
                'price' => 1000000,
                'product_category_id' => $serviceCategory->id,
                'user_id' => $user->id,
                'branch_id' => $mainBranch->id,
                'is_active' => true,
            ],
            [
                'name' => 'Fotografi Produk',
                'description' => 'Layanan fotografi untuk katalog produk',
                'price' => 400000,
                'product_category_id' => $serviceCategory->id,
                'user_id' => $user->id,
                'branch_id' => $mainBranch->id,
                'is_active' => true,
            ],
        ];

        foreach ($services as $serviceData) {
            \App\Models\Service::firstOrCreate(
                [
                    'name' => $serviceData['name'],
                    'user_id' => $serviceData['user_id'],
                    'branch_id' => $serviceData['branch_id']
                ],
                $serviceData
            );
        }

        // Get created accounts and categories
        $accountIds = \App\Models\Account::where('branch_id', $mainBranch->id)->pluck('id')->toArray();
        $incomeCategoryIds = \App\Models\Category::where('branch_id', $mainBranch->id)->where('type', 'income')->pluck('id')->toArray();
        $expenseCategoryIds = \App\Models\Category::where('branch_id', $mainBranch->id)->where('type', 'expense')->pluck('id')->toArray();

        // Create realistic transactions for the last 3 months
        $transactions = [];

        // Income transactions
        $incomeData = [
            ['Penjualan Kaos Custom', 150000, '2024-10-01', 0],
            ['Jasa Design Logo', 750000, '2024-10-05', 1],
            ['Gaji Freelance Web', 2000000, '2024-10-10', 1],
            ['Penjualan Buku Tulis', 45000, '2024-10-12', 0],
            ['Komisi Penjualan', 300000, '2024-10-15', 1],
            ['Jasa Fotografi', 500000, '2024-10-18', 1],
            ['Penjualan Aksesoris', 125000, '2024-10-20', 0],
            ['Bonus Project', 1000000, '2024-10-25', 2],
            ['Penjualan Online', 275000, '2024-10-28', 0],
            ['Jasa Konsultasi', 400000, '2024-11-02', 1],
            ['Gaji Bulanan', 3500000, '2024-11-05', 2],
            ['Penjualan Grosir', 850000, '2024-11-08', 0],
            ['Komisi Affiliate', 150000, '2024-11-12', 1],
            ['Jasa Editing Video', 600000, '2024-11-15', 1],
            ['Penjualan Sparepart', 320000, '2024-11-18', 0],
            ['Bonus Kinerja', 500000, '2024-11-22', 2],
            ['Pendapatan Sewa', 800000, '2024-11-25', 1],
            ['Penjualan Digital', 95000, '2024-11-28', 0],
            ['Jasa Training', 1200000, '2024-12-02', 1],
            ['Gaji Freelance', 1800000, '2024-12-05', 1],
            ['Penjualan Stock', 650000, '2024-12-08', 0],
            ['Komisi Sales', 250000, '2024-12-12', 1],
            ['Jasa Maintenance', 350000, '2024-12-15', 1],
            ['Penjualan Seasonal', 425000, '2024-12-18', 0],
            ['Bonus Tahunan', 2000000, '2024-12-20', 2],
        ];

        foreach ($incomeData as $data) {
            $transactions[] = [
                'user_id' => $user->id,
                'account_id' => $accountIds[$data[3]],
                'category_id' => $incomeCategoryIds[array_rand($incomeCategoryIds)],
                'type' => 'income',
                'amount' => $data[1],
                'description' => $data[0],
                'date' => $data[2],
                'branch_id' => $mainBranch->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Expense transactions
        $expenseData = [
            ['Beli Kaos Grosir', 1200000, '2024-10-02', 0],
            ['Bensin & Transport', 150000, '2024-10-03', 0],
            ['Makan Siang', 35000, '2024-10-04', 0],
            ['Listrik & Internet', 250000, '2024-10-06', 1],
            ['Beli Kertas & Tinta', 180000, '2024-10-08', 0],
            ['Iklan Facebook', 300000, '2024-10-10', 1],
            ['Service Printer', 150000, '2024-10-12', 0],
            ['Beli Aksesoris', 450000, '2024-10-14', 0],
            ['Parkir & Tol', 75000, '2024-10-16', 0],
            ['Konsumsi Meeting', 120000, '2024-10-18', 0],
            ['Beli Sparepart', 280000, '2024-10-20', 0],
            ['Biaya Admin Bank', 50000, '2024-10-22', 1],
            ['Makan & Minum', 45000, '2024-10-24', 0],
            ['Beli Packaging', 95000, '2024-10-26', 0],
            ['Transportasi Customer', 100000, '2024-10-28', 0],
            ['Sewa Tempat Workshop', 500000, '2024-11-01', 1],
            ['Beli Bahan Baku', 800000, '2024-11-03', 0],
            ['Biaya Fotokopi', 25000, '2024-11-05', 0],
            ['Iklan Instagram', 400000, '2024-11-07', 1],
            ['Service Komputer', 200000, '2024-11-09', 0],
            ['Beli Souvenir', 150000, '2024-11-11', 0],
            ['Biaya Training', 300000, '2024-11-13', 1],
            ['Makan Siang Client', 85000, '2024-11-15', 0],
            ['Beli Tools', 350000, '2024-11-17', 0],
            ['Biaya Promosi', 200000, '2024-11-19', 1],
            ['Transportasi Delivery', 125000, '2024-11-21', 0],
            ['Listrik & Air', 180000, '2024-11-23', 1],
            ['Beli Stock Seasonal', 600000, '2024-11-25', 0],
            ['Biaya Maintenance', 175000, '2024-11-27', 0],
            ['Konsumsi Rapat', 65000, '2024-11-29', 0],
            ['Beli Packaging Premium', 200000, '2024-12-01', 0],
            ['Biaya Admin Online', 75000, '2024-12-03', 1],
            ['Service Equipment', 250000, '2024-12-05', 0],
            ['Iklan Marketplace', 350000, '2024-12-07', 1],
            ['Transportasi Supplier', 95000, '2024-12-09', 0],
            ['Biaya Utilitas', 220000, '2024-12-11', 1],
            ['Beli Display Unit', 450000, '2024-12-13', 0],
            ['Makan & Minum Event', 150000, '2024-12-15', 0],
            ['Biaya Sertifikasi', 500000, '2024-12-17', 1],
            ['Beli Gift Wrapping', 75000, '2024-12-19', 0],
        ];

        foreach ($expenseData as $data) {
            $transactions[] = [
                'user_id' => $user->id,
                'account_id' => $accountIds[$data[3]],
                'category_id' => $expenseCategoryIds[array_rand($expenseCategoryIds)],
                'type' => 'expense',
                'amount' => $data[1],
                'description' => $data[0],
                'date' => $data[2],
                'branch_id' => $mainBranch->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert transactions
        DB::table('transactions')->insert($transactions);

        // Update account balances based on transactions
        foreach ($accountIds as $accountId) {
            $balance = DB::table('transactions')
                ->where('account_id', $accountId)
                ->where('status', 'posted')
                ->selectRaw('
                    SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) -
                    SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as calculated_balance
                ')
                ->first()->calculated_balance ?? 0;

            \App\Models\Account::where('id', $accountId)->update(['balance' => $balance]);
        }

        // Create a few transfers
        $transfers = [
            [
                'user_id' => $user->id,
                'from_account_id' => $accountIds[1], // BCA
                'to_account_id' => $accountIds[0], // Cash
                'amount' => 1000000,
                'description' => 'Tarik tunai untuk operasional',
                'date' => '2024-11-15',
                'branch_id' => $mainBranch->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'from_account_id' => $accountIds[2], // Savings
                'to_account_id' => $accountIds[1], // BCA
                'amount' => 5000000,
                'description' => 'Transfer ke rekening operasional',
                'date' => '2024-12-01',
                'branch_id' => $mainBranch->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Create transfer transactions
        foreach ($transfers as $transfer) {
            // Insert transfer first
            $transferId = DB::table('transfers')->insertGetId($transfer);

            // Debit from source account
            DB::table('transactions')->insert([
                'user_id' => $user->id,
                'account_id' => $transfer['from_account_id'],
                'type' => 'transfer',
                'amount' => $transfer['amount'],
                'description' => $transfer['description'],
                'date' => $transfer['date'],
                'transfer_id' => $transferId,
                'branch_id' => $mainBranch->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Credit to destination account
            DB::table('transactions')->insert([
                'user_id' => $user->id,
                'account_id' => $transfer['to_account_id'],
                'type' => 'transfer',
                'amount' => $transfer['amount'],
                'description' => $transfer['description'],
                'date' => $transfer['date'],
                'transfer_id' => $transferId,
                'branch_id' => $mainBranch->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Starter data seeded successfully!');
        $this->command->info('Admin user: admin@sibuku.com');
        $this->command->info('Password: password');
        $this->command->info('Demo user: demo@akuntansisibuku.com');
        $this->command->info('Password: password');
    }
}