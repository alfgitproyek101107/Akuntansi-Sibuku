<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedBranches();
        $this->seedUsers();
        $this->seedChartOfAccounts();
        $this->seedCategories();
        $this->seedAccounts();
        $this->seedCustomers();
        // $this->seedVendors(); // Vendor model doesn't exist
        $this->seedProductCategories();
        $this->seedProducts();
        $this->seedServices();
        $this->seedTaxSettings();
        $this->seedUserBranches();
        $this->seedTransactions();
        $this->seedApprovalWorkflows();
    }

    private function seedBranches(): void
    {
        $branches = [
            ['name' => 'Cabang Bali', 'address' => 'Jl. Sunset Road No. 88, Kuta, Bali', 'phone' => '+62-361-755123', 'email' => 'bali@sibuku.com', 'is_demo' => false],
            ['name' => 'Cabang Jakarta', 'address' => 'Jl. Thamrin No. 100, Jakarta Pusat', 'phone' => '+62-21-500123', 'email' => 'jakarta@sibuku.com', 'is_demo' => false],
            ['name' => 'Cabang Surabaya', 'address' => 'Jl. Tunjungan No. 200, Surabaya', 'phone' => '+62-31-501234', 'email' => 'surabaya@sibuku.com', 'is_demo' => false],
            ['name' => 'Cabang Bandung', 'address' => 'Jl. Asia Afrika No. 300, Bandung', 'phone' => '+62-22-502345', 'email' => 'bandung@sibuku.com', 'is_demo' => false],
            ['name' => 'Demo Cabang', 'address' => 'Jl. Demo No. 123, Jakarta', 'phone' => '+62-21-1234567', 'email' => 'demo@company.com', 'is_demo' => true],
        ];

        foreach ($branches as $branch) {
            \App\Models\Branch::firstOrCreate(['name' => $branch['name']], $branch);
        }
    }

    private function seedUsers(): void
    {
        $baliBranch = \App\Models\Branch::where('name', 'Cabang Bali')->first();
        $jakartaBranch = \App\Models\Branch::where('name', 'Cabang Jakarta')->first();
        $demoBranch = \App\Models\Branch::where('name', 'Demo Cabang')->first();

        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@sibuku.com',
                'password' => Hash::make('admin123'),
                'branch_id' => $baliBranch->id,
            ],
            [
                'name' => 'Manager Jakarta',
                'email' => 'manager.jakarta@sibuku.com',
                'password' => Hash::make('manager123'),
                'branch_id' => $jakartaBranch->id,
            ],
            [
                'name' => 'Staff Bali',
                'email' => 'staff.bali@sibuku.com',
                'password' => Hash::make('staff123'),
                'branch_id' => $baliBranch->id,
            ],
            [
                'name' => 'Admin Demo',
                'email' => 'admin@demo.com',
                'password' => Hash::make('password123'),
                'branch_id' => $demoBranch->id,
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::firstOrCreate(['email' => $user['email']], $user);
        }
    }

    private function seedChartOfAccounts(): void
    {
        $accounts = [
            // Assets
            ['code' => '1001', 'name' => 'Kas Utama', 'type' => 'asset', 'category' => 'current_asset'],
            ['code' => '1002', 'name' => 'Bank BCA', 'type' => 'asset', 'category' => 'current_asset'],
            ['code' => '1003', 'name' => 'Bank Mandiri', 'type' => 'asset', 'category' => 'current_asset'],
            ['code' => '1101', 'name' => 'Piutang Usaha', 'type' => 'asset', 'category' => 'current_asset'],
            ['code' => '1201', 'name' => 'Persediaan Barang', 'type' => 'asset', 'category' => 'current_asset'],

            // Liabilities
            ['code' => '2001', 'name' => 'Hutang Usaha', 'type' => 'liability', 'category' => 'current_liability'],
            ['code' => '2002', 'name' => 'Hutang Bank', 'type' => 'liability', 'category' => 'current_liability'],

            // Equity
            ['code' => '3001', 'name' => 'Modal Pemilik', 'type' => 'equity', 'category' => 'owner_equity'],
            ['code' => '3002', 'name' => 'Laba Ditahan', 'type' => 'equity', 'category' => 'retained_earnings'],

            // Income
            ['code' => '4001', 'name' => 'Penjualan Produk', 'type' => 'revenue', 'category' => 'sales_revenue'],
            ['code' => '4002', 'name' => 'Pendapatan Jasa', 'type' => 'revenue', 'category' => 'other_revenue'],
            ['code' => '4003', 'name' => 'Pendapatan Lain-lain', 'type' => 'revenue', 'category' => 'other_revenue'],

            // Expenses
            ['code' => '5001', 'name' => 'Beban Pokok Penjualan', 'type' => 'expense', 'category' => 'cost_of_goods_sold'],
            ['code' => '5002', 'name' => 'Beban Operasional', 'type' => 'expense', 'category' => 'operating_expense'],
            ['code' => '5003', 'name' => 'Beban Gaji', 'type' => 'expense', 'category' => 'operating_expense'],
            ['code' => '5004', 'name' => 'Beban Transportasi', 'type' => 'expense', 'category' => 'operating_expense'],
            ['code' => '5005', 'name' => 'Beban Utilitas', 'type' => 'expense', 'category' => 'operating_expense'],
        ];

        foreach ($accounts as $account) {
            \App\Models\ChartOfAccount::firstOrCreate(['code' => $account['code']], $account);
        }
    }

    private function seedCategories(): void
    {
        $baliBranch = \App\Models\Branch::where('name', 'Cabang Bali')->first();
        $jakartaBranch = \App\Models\Branch::where('name', 'Cabang Jakarta')->first();
        $adminUser = \App\Models\User::where('email', 'admin@sibuku.com')->first();

        $categories = [
            // Income Categories
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'name' => 'Penjualan Produk', 'type' => 'income'],
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'name' => 'Jasa Konsultasi', 'type' => 'income'],
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'name' => 'Pendapatan Sewa', 'type' => 'income'],
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'name' => 'Komisi', 'type' => 'income'],

            // Expense Categories
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'name' => 'Biaya Operasional', 'type' => 'expense'],
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'name' => 'Biaya Transportasi', 'type' => 'expense'],
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'name' => 'Biaya Marketing', 'type' => 'expense'],
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'name' => 'Biaya Administrasi', 'type' => 'expense'],
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'name' => 'Biaya Utilitas', 'type' => 'expense'],

            // Jakarta Branch Categories
            ['user_id' => $adminUser->id, 'branch_id' => $jakartaBranch->id, 'name' => 'Penjualan Jakarta', 'type' => 'income'],
            ['user_id' => $adminUser->id, 'branch_id' => $jakartaBranch->id, 'name' => 'Biaya Jakarta', 'type' => 'expense'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::firstOrCreate(
                ['user_id' => $category['user_id'], 'branch_id' => $category['branch_id'], 'name' => $category['name']],
                $category
            );
        }
    }

    private function seedAccounts(): void
    {
        $baliBranch = \App\Models\Branch::where('name', 'Cabang Bali')->first();
        $jakartaBranch = \App\Models\Branch::where('name', 'Cabang Jakarta')->first();
        $adminUser = \App\Models\User::where('email', 'admin@sibuku.com')->first();

        $accounts = [
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'name' => 'Kas Utama', 'type' => 'bank', 'balance' => 50000000.00],
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'name' => 'BCA 1234567890', 'type' => 'bank', 'balance' => 150000000.00],
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'name' => 'Mandiri 0987654321', 'type' => 'bank', 'balance' => 75000000.00],
            ['user_id' => $adminUser->id, 'branch_id' => $jakartaBranch->id, 'name' => 'Kas Jakarta', 'type' => 'bank', 'balance' => 25000000.00],
            ['user_id' => $adminUser->id, 'branch_id' => $jakartaBranch->id, 'name' => 'BCA Jakarta', 'type' => 'bank', 'balance' => 100000000.00],
        ];

        foreach ($accounts as $account) {
            \App\Models\Account::firstOrCreate(
                ['user_id' => $account['user_id'], 'branch_id' => $account['branch_id'], 'name' => $account['name']],
                $account
            );
        }
    }

    private function seedCustomers(): void
    {
        $baliBranch = \App\Models\Branch::where('name', 'Cabang Bali')->first();
        $jakartaBranch = \App\Models\Branch::where('name', 'Cabang Jakarta')->first();
        $adminUser = \App\Models\User::where('email', 'admin@sibuku.com')->first();

        $customers = [
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'name' => 'PT. Maju Jaya', 'email' => 'contact@majujaya.com', 'phone' => '+62-361-123456', 'address' => 'Jl. Raya Kuta No. 100, Bali'],
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'name' => 'CV. Bali Mandiri', 'email' => 'info@balimandiri.com', 'phone' => '+62-361-234567', 'address' => 'Jl. Legian No. 50, Bali'],
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'name' => 'Toko Sukses', 'email' => 'sukses@toko.com', 'phone' => '+62-361-345678', 'address' => 'Jl. Seminyak No. 25, Bali'],
            ['user_id' => $adminUser->id, 'branch_id' => $jakartaBranch->id, 'name' => 'PT. Jakarta Prima', 'email' => 'prima@jakarta.com', 'phone' => '+62-21-987654', 'address' => 'Jl. Sudirman No. 200, Jakarta'],
        ];

        foreach ($customers as $customer) {
            \App\Models\Customer::firstOrCreate(
                ['user_id' => $customer['user_id'], 'branch_id' => $customer['branch_id'], 'name' => $customer['name']],
                $customer
            );
        }
    }


    private function seedProductCategories(): void
    {
        $adminUser = \App\Models\User::where('email', 'admin@sibuku.com')->first();

        $categories = [
            ['user_id' => $adminUser->id, 'name' => 'Elektronik', 'description' => 'Produk elektronik dan gadget'],
            ['user_id' => $adminUser->id, 'name' => 'Pakaian', 'description' => 'Pakaian dan aksesoris'],
            ['user_id' => $adminUser->id, 'name' => 'Makanan', 'description' => 'Produk makanan dan minuman'],
            ['user_id' => $adminUser->id, 'name' => 'Otomotif', 'description' => 'Suku cadang dan aksesoris kendaraan'],
        ];

        foreach ($categories as $category) {
            \App\Models\ProductCategory::firstOrCreate(
                ['user_id' => $category['user_id'], 'name' => $category['name']],
                $category
            );
        }
    }

    private function seedProducts(): void
    {
        $baliBranch = \App\Models\Branch::where('name', 'Cabang Bali')->first();
        $adminUser = \App\Models\User::where('email', 'admin@sibuku.com')->first();
        $elektronik = \App\Models\ProductCategory::where('name', 'Elektronik')->first();
        $pakaian = \App\Models\ProductCategory::where('name', 'Pakaian')->first();

        $products = [
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'product_category_id' => $elektronik->id, 'name' => 'Laptop Gaming', 'sku' => 'LPT-001', 'price' => 15000000.00, 'cost_price' => 12000000.00, 'stock_quantity' => 10],
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'product_category_id' => $elektronik->id, 'name' => 'Mouse Wireless', 'sku' => 'MSE-001', 'price' => 250000.00, 'cost_price' => 150000.00, 'stock_quantity' => 50],
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'product_category_id' => $pakaian->id, 'name' => 'Kaos Polos', 'sku' => 'KPS-001', 'price' => 75000.00, 'cost_price' => 45000.00, 'stock_quantity' => 100],
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'product_category_id' => $pakaian->id, 'name' => 'Celana Jeans', 'sku' => 'CJN-001', 'price' => 350000.00, 'cost_price' => 200000.00, 'stock_quantity' => 30],
        ];

        foreach ($products as $product) {
            \App\Models\Product::firstOrCreate(
                ['user_id' => $product['user_id'], 'branch_id' => $product['branch_id'], 'sku' => $product['sku']],
                $product
            );
        }
    }

    private function seedServices(): void
    {
        $baliBranch = \App\Models\Branch::where('name', 'Cabang Bali')->first();
        $adminUser = \App\Models\User::where('email', 'admin@sibuku.com')->first();
        $elektronik = \App\Models\ProductCategory::where('name', 'Elektronik')->first();

        $services = [
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'product_category_id' => $elektronik->id, 'name' => 'Service Laptop', 'description' => 'Perbaikan dan maintenance laptop', 'price' => 150000.00],
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'product_category_id' => $elektronik->id, 'name' => 'Instalasi Software', 'description' => 'Instalasi dan konfigurasi software', 'price' => 200000.00],
        ];

        foreach ($services as $service) {
            \App\Models\Service::firstOrCreate(
                ['user_id' => $service['user_id'], 'branch_id' => $service['branch_id'], 'name' => $service['name']],
                $service
            );
        }
    }

    private function seedTaxSettings(): void
    {
        $baliBranch = \App\Models\Branch::where('name', 'Cabang Bali')->first();
        $adminUser = \App\Models\User::where('email', 'admin@sibuku.com')->first();

        $taxSettings = [
            ['user_id' => $adminUser->id, 'name' => 'PPN 11%', 'rate' => 11.00, 'type' => 'percent', 'branch_id' => $baliBranch->id],
            ['user_id' => $adminUser->id, 'name' => 'PPN UMKM 1.1%', 'rate' => 1.10, 'type' => 'percent', 'branch_id' => $baliBranch->id],
            ['user_id' => $adminUser->id, 'name' => 'PPh 21', 'rate' => 5.00, 'type' => 'percent', 'branch_id' => $baliBranch->id],
        ];

        foreach ($taxSettings as $tax) {
            \App\Models\TaxSetting::firstOrCreate(
                ['user_id' => $tax['user_id'], 'branch_id' => $tax['branch_id'], 'name' => $tax['name']],
                $tax
            );
        }
    }

    private function seedUserBranches(): void
    {
        $baliBranch = \App\Models\Branch::where('name', 'Cabang Bali')->first();
        $jakartaBranch = \App\Models\Branch::where('name', 'Cabang Jakarta')->first();
        $demoBranch = \App\Models\Branch::where('name', 'Demo Cabang')->first();

        $adminUser = \App\Models\User::where('email', 'admin@sibuku.com')->first();
        $managerUser = \App\Models\User::where('email', 'manager.jakarta@sibuku.com')->first();
        $staffUser = \App\Models\User::where('email', 'staff.bali@sibuku.com')->first();
        $demoUser = \App\Models\User::where('email', 'admin@demo.com')->first();

        $userBranches = [
            ['user_id' => $adminUser->id, 'branch_id' => $baliBranch->id, 'role_name' => 'admin', 'is_default' => true, 'is_active' => true],
            ['user_id' => $managerUser->id, 'branch_id' => $jakartaBranch->id, 'role_name' => 'manager', 'is_default' => true, 'is_active' => true],
            ['user_id' => $staffUser->id, 'branch_id' => $baliBranch->id, 'role_name' => 'staff', 'is_default' => true, 'is_active' => true],
            ['user_id' => $demoUser->id, 'branch_id' => $demoBranch->id, 'role_name' => 'admin', 'is_default' => true, 'is_active' => true],
        ];

        foreach ($userBranches as $userBranch) {
            \App\Models\UserBranch::firstOrCreate(
                ['user_id' => $userBranch['user_id'], 'branch_id' => $userBranch['branch_id']],
                $userBranch
            );
        }
    }

    private function seedTransactions(): void
    {
        $baliBranch = \App\Models\Branch::where('name', 'Cabang Bali')->first();
        $adminUser = \App\Models\User::where('email', 'admin@sibuku.com')->first();
        $kasAccount = \App\Models\Account::where('name', 'Kas Utama')->where('branch_id', $baliBranch->id)->first();
        $bcaAccount = \App\Models\Account::where('name', 'BCA 1234567890')->where('branch_id', $baliBranch->id)->first();
        $incomeCategory = \App\Models\Category::where('name', 'Penjualan Produk')->where('branch_id', $baliBranch->id)->first();
        $expenseCategory = \App\Models\Category::where('name', 'Biaya Operasional')->where('branch_id', $baliBranch->id)->first();

        $transactions = [
            [
                'user_id' => $adminUser->id,
                'branch_id' => $baliBranch->id,
                'account_id' => $kasAccount->id,
                'category_id' => $incomeCategory->id,
                'amount' => 5000000.00,
                'description' => 'Penjualan laptop gaming',
                'date' => now()->subDays(5),
                'type' => 'income',
                'status' => 'posted',
            ],
            [
                'user_id' => $adminUser->id,
                'branch_id' => $baliBranch->id,
                'account_id' => $bcaAccount->id,
                'category_id' => $incomeCategory->id,
                'amount' => 7500000.00,
                'description' => 'Penjualan kaos polos (50 pcs)',
                'date' => now()->subDays(3),
                'type' => 'income',
                'status' => 'posted',
            ],
            [
                'user_id' => $adminUser->id,
                'branch_id' => $baliBranch->id,
                'account_id' => $kasAccount->id,
                'category_id' => $expenseCategory->id,
                'amount' => 500000.00,
                'description' => 'Biaya listrik dan air bulan Oktober',
                'date' => now()->subDays(2),
                'type' => 'expense',
                'status' => 'posted',
            ],
            [
                'user_id' => $adminUser->id,
                'branch_id' => $baliBranch->id,
                'account_id' => $kasAccount->id,
                'category_id' => $expenseCategory->id,
                'amount' => 750000.00,
                'description' => 'Biaya transportasi delivery',
                'date' => now()->subDays(1),
                'type' => 'expense',
                'status' => 'posted',
            ],
        ];

        foreach ($transactions as $transaction) {
            \App\Models\Transaction::create($transaction);
        }

        // Update account balances
        $this->updateAccountBalances();
    }

    private function updateAccountBalances(): void
    {
        $accounts = \App\Models\Account::all();
        foreach ($accounts as $account) {
            $income = $account->transactions()->where('type', 'income')->sum('amount');
            $expense = $account->transactions()->where('type', 'expense')->sum('amount');
            // Skip transfer calculations for now since transfers table might not have is_demo column
            $transfersIn = 0;
            $transfersOut = 0;

            $balance = $account->balance + $income - $expense + $transfersIn - $transfersOut;
            $account->update(['balance' => $balance]);
        }
    }

    private function seedApprovalWorkflows(): void
    {
        $baliBranch = \App\Models\Branch::where('name', 'Cabang Bali')->first();
        $adminUser = \App\Models\User::where('email', 'admin@sibuku.com')->first();

        $workflows = [
            [
                'name' => 'Approval Transaksi > 5 Juta',
                'description' => 'Persetujuan otomatis untuk transaksi di atas 5 juta',
                'module_type' => 'transaction',
                'trigger_condition' => 'amount > 5000000',
                'approval_levels' => json_encode([
                    [
                        'level' => 1,
                        'approvers' => [$adminUser->id],
                        'min_approvals' => 1
                    ]
                ]),
                'min_amount' => 5000000.00,
                'is_active' => true,
                'require_all_levels' => false,
                'created_by' => $adminUser->id,
                'branch_id' => $baliBranch->id,
            ],
            [
                'name' => 'Approval Expense > 1 Juta',
                'description' => 'Persetujuan untuk pengeluaran di atas 1 juta',
                'module_type' => 'transaction',
                'trigger_condition' => 'type = expense AND amount > 1000000',
                'approval_levels' => json_encode([
                    [
                        'level' => 1,
                        'approvers' => [$adminUser->id],
                        'min_approvals' => 1
                    ]
                ]),
                'min_amount' => 1000000.00,
                'is_active' => true,
                'require_all_levels' => false,
                'created_by' => $adminUser->id,
                'branch_id' => $baliBranch->id,
            ],
        ];

        foreach ($workflows as $workflow) {
            \App\Models\ApprovalWorkflow::create($workflow);
        }
    }
}
