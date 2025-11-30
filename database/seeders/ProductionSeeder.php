<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Branch;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Account;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Transfer;
use Carbon\Carbon;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds for production.
     */
    public function run(): void
    {
        $this->command->info('Starting production database seeding...');

        // Create user roles
        $this->createUserRoles();

        // Create branches
        $this->createBranches();

        // Create users
        $this->createUsers();

        // Create accounts for each branch
        $this->createAccounts();

        // Create categories
        $this->createCategories();

        // Create product categories and products
        $this->createProductCategories();
        $this->createProducts();

        // Create customers
        $this->createCustomers();

        // Create sample transactions
        $this->createSampleTransactions();

        // Create demo branch and data
        $this->createDemoData();

        $this->command->info('Production database seeding completed!');
    }

    private function createUserRoles(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'Full system access with all permissions',
                'permissions' => json_encode(['*']),
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrative access to manage users and settings',
                'permissions' => json_encode(['manage_users', 'manage_settings', 'view_reports']),
            ],
            [
                'name' => 'branch_manager',
                'display_name' => 'Branch Manager',
                'description' => 'Manage branch operations and staff',
                'permissions' => json_encode(['manage_branch', 'view_reports', 'manage_inventory']),
            ],
            [
                'name' => 'kasir',
                'display_name' => 'Kasir',
                'description' => 'Handle cash transactions and sales',
                'permissions' => json_encode(['create_transactions', 'view_inventory']),
            ],
            [
                'name' => 'inventory_manager',
                'display_name' => 'Inventory Manager',
                'description' => 'Manage products and stock levels',
                'permissions' => json_encode(['manage_products', 'manage_inventory', 'view_reports']),
            ],
            [
                'name' => 'auditor',
                'display_name' => 'Auditor',
                'description' => 'View and audit financial reports',
                'permissions' => json_encode(['view_reports', 'audit_transactions']),
            ],
            [
                'name' => 'user',
                'display_name' => 'User',
                'description' => 'Basic user access',
                'permissions' => json_encode(['view_own_data']),
            ],
        ];

        foreach ($roles as $role) {
            UserRole::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }

    private function createBranches(): void
    {
        $branches = [
            [
                'id' => 1000,
                'code' => 'HO001',
                'name' => 'Kantor Pusat Jakarta',
                'address' => 'Jl. Sudirman No. 1, Jakarta Pusat, DKI Jakarta 10220',
                'phone' => '+62-21-1234567',
                'email' => 'admin@sibuku.com',
                'is_active' => true,
                'is_head_office' => true,
                'is_demo' => false,
            ],
            [
                'id' => 1001,
                'code' => 'BR001',
                'name' => 'Cabang Bandung',
                'address' => 'Jl. Asia Afrika No. 123, Bandung, Jawa Barat 40111',
                'phone' => '+62-22-9876543',
                'email' => 'bandung@sibuku.com',
                'is_active' => true,
                'is_head_office' => false,
                'is_demo' => false,
            ],
            [
                'id' => 1002,
                'code' => 'BR002',
                'name' => 'Cabang Surabaya',
                'address' => 'Jl. Tunjungan No. 456, Surabaya, Jawa Timur 60275',
                'phone' => '+62-31-5678901',
                'email' => 'surabaya@sibuku.com',
                'is_active' => true,
                'is_head_office' => false,
                'is_demo' => false,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::updateOrCreate(
                ['id' => $branch['id']],
                $branch
            );
        }
    }

    private function createUsers(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@sibuku.com',
                'password' => Hash::make('password'),
                'branch_id' => 1000,
                'user_role_id' => 1,
                'demo_mode' => false,
            ],
            [
                'name' => 'Admin Pusat',
                'email' => 'admin.pusat@sibuku.com',
                'password' => Hash::make('password'),
                'branch_id' => 1000,
                'user_role_id' => 2,
                'demo_mode' => false,
            ],
            [
                'name' => 'Manager Bandung',
                'email' => 'manager.bandung@sibuku.com',
                'password' => Hash::make('password'),
                'branch_id' => 1001,
                'user_role_id' => 3,
                'demo_mode' => false,
            ],
            [
                'name' => 'Kasir Surabaya',
                'email' => 'kasir.surabaya@sibuku.com',
                'password' => Hash::make('password'),
                'branch_id' => 1002,
                'user_role_id' => 4,
                'demo_mode' => false,
            ],
            [
                'name' => 'Staff Inventory',
                'email' => 'inventory@sibuku.com',
                'password' => Hash::make('password'),
                'branch_id' => 1000,
                'user_role_id' => 5,
                'demo_mode' => false,
            ],
            [
                'name' => 'Auditor',
                'email' => 'auditor@sibuku.com',
                'password' => Hash::make('password'),
                'branch_id' => 1000,
                'user_role_id' => 6,
                'demo_mode' => false,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Create user-branch relationship
            DB::table('user_branches')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'branch_id' => $userData['branch_id']
                ],
                [
                    'role_name' => UserRole::find($userData['user_role_id'])->name,
                    'is_default' => true,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    private function createAccounts(): void
    {
        // Head Office Accounts
        $hoAccounts = [
            ['name' => 'Kas Kecil', 'code' => '1101', 'type' => 'asset', 'balance' => 5000000],
            ['name' => 'Bank BCA', 'code' => '1102', 'type' => 'asset', 'balance' => 25000000],
            ['name' => 'Bank Mandiri', 'code' => '1103', 'type' => 'asset', 'balance' => 15000000],
            ['name' => 'Piutang Usaha', 'code' => '1201', 'type' => 'asset', 'balance' => 5000000],
            ['name' => 'Persediaan Barang', 'code' => '1301', 'type' => 'asset', 'balance' => 20000000],
            ['name' => 'Penjualan Produk', 'code' => '4101', 'type' => 'revenue', 'balance' => 0],
            ['name' => 'Jasa Konsultasi', 'code' => '4102', 'type' => 'revenue', 'balance' => 0],
            ['name' => 'Harga Pokok Penjualan', 'code' => '5101', 'type' => 'expense', 'balance' => 0],
            ['name' => 'Gaji Karyawan', 'code' => '5201', 'type' => 'expense', 'balance' => 0],
            ['name' => 'Sewa Kantor', 'code' => '5202', 'type' => 'expense', 'balance' => 0],
            ['name' => 'Utilitas', 'code' => '5203', 'type' => 'expense', 'balance' => 0],
            ['name' => 'Transportasi', 'code' => '5204', 'type' => 'expense', 'balance' => 0],
            ['name' => 'Entertainment', 'code' => '5205', 'type' => 'expense', 'balance' => 0],
        ];

        foreach ($hoAccounts as $account) {
            Account::updateOrCreate(
                ['code' => $account['code'], 'branch_id' => 1000],
                array_merge($account, ['branch_id' => 1000, 'is_active' => true])
            );
        }

        // Branch Accounts (simplified)
        $branchAccounts = [
            ['name' => 'Kas Kecil', 'code' => '1101', 'type' => 'asset', 'balance' => 3000000, 'branch_id' => 1001],
            ['name' => 'Bank BRI', 'code' => '1102', 'type' => 'asset', 'balance' => 12000000, 'branch_id' => 1001],
            ['name' => 'Kas Kecil', 'code' => '1101', 'type' => 'asset', 'balance' => 4000000, 'branch_id' => 1002],
            ['name' => 'Bank BNI', 'code' => '1102', 'type' => 'asset', 'balance' => 18000000, 'branch_id' => 1002],
        ];

        foreach ($branchAccounts as $account) {
            Account::updateOrCreate(
                ['code' => $account['code'], 'branch_id' => $account['branch_id']],
                array_merge($account, ['is_active' => true])
            );
        }
    }

    private function createCategories(): void
    {
        $categories = [
            ['name' => 'Penjualan Produk', 'type' => 'income', 'branch_id' => 1000],
            ['name' => 'Jasa Konsultasi', 'type' => 'income', 'branch_id' => 1000],
            ['name' => 'Komisi Penjualan', 'type' => 'income', 'branch_id' => 1000],
            ['name' => 'Beli Bahan Baku', 'type' => 'expense', 'branch_id' => 1000],
            ['name' => 'Gaji Karyawan', 'type' => 'expense', 'branch_id' => 1000],
            ['name' => 'Sewa Kantor', 'type' => 'expense', 'branch_id' => 1000],
            ['name' => 'Utilitas', 'type' => 'expense', 'branch_id' => 1000],
            ['name' => 'Transportasi', 'type' => 'expense', 'branch_id' => 1000],
            ['name' => 'Entertainment', 'type' => 'expense', 'branch_id' => 1000],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name'], 'branch_id' => $category['branch_id']],
                array_merge($category, ['is_active' => true])
            );
        }
    }

    private function createProductCategories(): void
    {
        $categories = [
            ['name' => 'Elektronik', 'branch_id' => 1000],
            ['name' => 'Pakaian', 'branch_id' => 1000],
            ['name' => 'Makanan', 'branch_id' => 1000],
            ['name' => 'Minuman', 'branch_id' => 1000],
            ['name' => 'ATK', 'branch_id' => 1000],
        ];

        foreach ($categories as $category) {
            ProductCategory::updateOrCreate(
                ['name' => $category['name'], 'branch_id' => $category['branch_id']],
                array_merge($category, ['is_active' => true])
            );
        }
    }

    private function createProducts(): void
    {
        $products = [
            [
                'name' => 'Laptop Gaming ASUS ROG',
                'code' => 'PROD001',
                'price' => 15000000,
                'cost' => 12000000,
                'stock' => 5,
                'min_stock' => 2,
                'product_category_id' => 1,
                'branch_id' => 1000,
                'tax_rate' => 11.00,
            ],
            [
                'name' => 'Kaos Polos Cotton',
                'code' => 'PROD002',
                'price' => 75000,
                'cost' => 50000,
                'stock' => 50,
                'min_stock' => 10,
                'product_category_id' => 2,
                'branch_id' => 1000,
                'tax_rate' => 11.00,
            ],
            [
                'name' => 'Nasi Goreng Special',
                'code' => 'PROD003',
                'price' => 25000,
                'cost' => 15000,
                'stock' => 100,
                'min_stock' => 20,
                'product_category_id' => 3,
                'branch_id' => 1000,
                'tax_rate' => 11.00,
            ],
            [
                'name' => 'Jus Jeruk Segar',
                'code' => 'PROD004',
                'price' => 15000,
                'cost' => 8000,
                'stock' => 200,
                'min_stock' => 30,
                'product_category_id' => 4,
                'branch_id' => 1000,
                'tax_rate' => 11.00,
            ],
            [
                'name' => 'Pulpen Pilot',
                'code' => 'PROD005',
                'price' => 25000,
                'cost' => 15000,
                'stock' => 100,
                'min_stock' => 20,
                'product_category_id' => 5,
                'branch_id' => 1000,
                'tax_rate' => 11.00,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['code' => $product['code'], 'branch_id' => $product['branch_id']],
                array_merge($product, ['is_active' => true, 'unit' => 'pcs'])
            );
        }
    }

    private function createCustomers(): void
    {
        $customers = [
            [
                'name' => 'PT. Maju Jaya',
                'email' => 'contact@majujaya.com',
                'phone' => '+62-21-9876543',
                'address' => 'Jl. Sudirman No. 45',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
                'postal_code' => '10220',
                'tax_id' => '01.234.567.8-123.000',
                'credit_limit' => 50000000,
                'branch_id' => 1000,
            ],
            [
                'name' => 'CV. Sukses Makmur',
                'email' => 'info@suksesmakmur.com',
                'phone' => '+62-21-8765432',
                'address' => 'Jl. Thamrin No. 67',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
                'postal_code' => '10230',
                'tax_id' => '02.345.678.9-234.000',
                'credit_limit' => 30000000,
                'branch_id' => 1000,
            ],
            [
                'name' => 'Toko Retail ABC',
                'email' => 'abc@retail.com',
                'phone' => '+62-21-7654321',
                'address' => 'Jl. Malioboro No. 12',
                'city' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
                'postal_code' => '55271',
                'credit_limit' => 15000000,
                'branch_id' => 1000,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['email' => $customer['email'], 'branch_id' => $customer['branch_id']],
                array_merge($customer, ['is_active' => true])
            );
        }
    }

    private function createSampleTransactions(): void
    {
        $transactions = [
            [
                'user_id' => 1,
                'account_id' => 6, // Penjualan Produk
                'category_id' => 1, // Penjualan Produk
                'amount' => 5000000,
                'description' => 'Penjualan laptop ke PT. Maju Jaya',
                'date' => Carbon::now()->subDays(5),
                'type' => 'income',
                'branch_id' => 1000,
                'tax_amount' => 550000,
                'tax_rate' => 11.00,
            ],
            [
                'user_id' => 1,
                'account_id' => 8, // Harga Pokok Penjualan
                'category_id' => 4, // Beli Bahan Baku
                'amount' => 3000000,
                'description' => 'Pembelian bahan baku elektronik',
                'date' => Carbon::now()->subDays(7),
                'type' => 'expense',
                'branch_id' => 1000,
                'tax_amount' => 330000,
                'tax_rate' => 11.00,
            ],
            [
                'user_id' => 1,
                'account_id' => 9, // Gaji Karyawan
                'category_id' => 5, // Gaji Karyawan
                'amount' => 15000000,
                'description' => 'Gaji karyawan bulan November',
                'date' => Carbon::now()->subDays(1),
                'type' => 'expense',
                'branch_id' => 1000,
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }

    private function createDemoData(): void
    {
        $this->command->info('Creating demo data...');

        // Create demo branch
        Branch::updateOrCreate(
            ['id' => 999],
            [
                'name' => 'Demo Cabang',
                'address' => 'Jl. Demo No. 123, Jakarta',
                'phone' => '+62-21-1234567',
                'email' => 'demo@company.com',
                'is_demo' => true,
                'is_active' => true,
            ]
        );

        // Create demo user
        $demoUser = User::updateOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('demo123'),
                'branch_id' => 999,
                'demo_mode' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create demo user-branch relationship
        DB::table('user_branches')->updateOrInsert(
            ['user_id' => $demoUser->id, 'branch_id' => 999],
            [
                'role_name' => 'admin',
                'is_default' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Demo accounts, categories, products, etc. (simplified)
        // You can expand this as needed

        $this->command->info('Demo data created successfully');
    }
}