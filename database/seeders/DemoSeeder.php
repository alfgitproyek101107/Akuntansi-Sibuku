<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Branch;
use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\RecurringTemplate;
use App\Models\Service;
use App\Models\StockMovement;
use App\Models\TaxSetting;
use App\Models\ChartOfAccount;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    private const DEMO_BRANCH_ID = 999;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo branch
        $this->createDemoBranch();

        // Create demo user first (before other data that references it)
        $this->createDemoUser();

        // Create demo data
        $this->createDemoChartOfAccounts();
        $this->createDemoAccounts();
        $this->createDemoCategories();
        $this->createDemoProductCategories();
        $this->createDemoProducts();
        $this->createDemoCustomers();
        $this->createDemoServices();
        $this->createDemoRecurringTemplates();
        $this->createDemoStockMovements();
        $this->createDemoTaxSettings();
        $this->createDemoTransactions();
        $this->createDemoTransfers();
    }

    private function createDemoBranch(): void
    {
        Branch::updateOrCreate(
            ['id' => self::DEMO_BRANCH_ID],
            [
                'name' => 'Demo Cabang',
                'address' => 'Jl. Demo No. 123, Jakarta',
                'phone' => '+62-21-1234567',
                'email' => 'demo@company.com',
                'is_demo' => true,
            ]
        );
    }

    private function createDemoChartOfAccounts(): void
    {
        $chartAccounts = [
            // Asset Accounts
            [
                'code' => '1101',
                'name' => 'Kas dan Setara Kas',
                'type' => 'asset',
                'category' => 'current_asset',
                'level' => 2,
                'normal_balance' => 'debit',
                'description' => 'Kas, bank, dan setara kas',
                'is_active' => true,
            ],
            [
                'code' => '1102',
                'name' => 'Piutang Usaha',
                'type' => 'asset',
                'category' => 'current_asset',
                'level' => 2,
                'normal_balance' => 'debit',
                'description' => 'Piutang dari penjualan barang/jasa',
                'is_active' => true,
            ],
            [
                'code' => '1301',
                'name' => 'Persediaan',
                'type' => 'asset',
                'category' => 'current_asset',
                'level' => 2,
                'normal_balance' => 'debit',
                'description' => 'Persediaan barang dagang',
                'is_active' => true,
            ],

            // Revenue Accounts
            [
                'code' => '4101',
                'name' => 'Penjualan Barang',
                'type' => 'revenue',
                'category' => 'sales_revenue',
                'level' => 2,
                'normal_balance' => 'credit',
                'description' => 'Pendapatan dari penjualan barang',
                'is_active' => true,
            ],
            [
                'code' => '4201',
                'name' => 'Pendapatan Jasa',
                'type' => 'revenue',
                'category' => 'other_revenue',
                'level' => 2,
                'normal_balance' => 'credit',
                'description' => 'Pendapatan dari jasa konsultasi',
                'is_active' => true,
            ],

            // Expense Accounts
            [
                'code' => '5101',
                'name' => 'Harga Pokok Penjualan',
                'type' => 'expense',
                'category' => 'cost_of_goods_sold',
                'level' => 2,
                'normal_balance' => 'debit',
                'description' => 'Biaya pembelian/pembuatan barang yang dijual',
                'is_active' => true,
            ],
            [
                'code' => '5201',
                'name' => 'Beban Operasional',
                'type' => 'expense',
                'category' => 'operating_expense',
                'level' => 2,
                'normal_balance' => 'debit',
                'description' => 'Beban operasional perusahaan',
                'is_active' => true,
            ],
            [
                'code' => '5202',
                'name' => 'Beban Gaji',
                'type' => 'expense',
                'category' => 'operating_expense',
                'level' => 3,
                'normal_balance' => 'debit',
                'description' => 'Beban gaji karyawan',
                'is_active' => true,
            ],
            [
                'code' => '5203',
                'name' => 'Beban Sewa',
                'type' => 'expense',
                'category' => 'operating_expense',
                'level' => 3,
                'normal_balance' => 'debit',
                'description' => 'Beban sewa kantor dan gedung',
                'is_active' => true,
            ],
        ];

        foreach ($chartAccounts as $account) {
            ChartOfAccount::updateOrCreate(
                ['code' => $account['code']],
                $account
            );
        }
    }

    private function createDemoUser(): void
    {
        // Create demo user without depending on user_roles table
        $user = User::updateOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('demo123'),
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_role_id' => null, // Will be set later if needed
                'demo_mode' => true,
                'email_verified_at' => now(),
            ]
        );

        // Ensure demo user has branch assignment
        DB::table('user_branches')->updateOrInsert(
            [
                'user_id' => $user->id,
                'branch_id' => self::DEMO_BRANCH_ID,
            ],
            [
                'role_name' => 'admin',
                'is_default' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function createDemoAccounts(): void
    {
        // Get the demo user ID
        $demoUser = User::where('email', 'demo@example.com')->first();
        if (!$demoUser) {
            throw new \Exception('Demo user not found');
        }

        $accounts = [
            [
                'name' => 'Kas Kecil',
                'type' => 'asset',
                'balance' => 5000000,
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'name' => 'Bank BCA',
                'type' => 'asset',
                'balance' => 25000000,
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'name' => 'Piutang Usaha',
                'type' => 'asset',
                'balance' => 1500000,
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'name' => 'Persediaan Barang',
                'type' => 'asset',
                'balance' => 8000000,
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'name' => 'Penjualan',
                'type' => 'revenue',
                'balance' => 0,
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'name' => 'Harga Pokok Penjualan',
                'type' => 'expense',
                'balance' => 0,
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'name' => 'Beban Operasional',
                'type' => 'expense',
                'balance' => 0,
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
        ];

        foreach ($accounts as $account) {
            Account::updateOrCreate(
                ['name' => $account['name'], 'branch_id' => self::DEMO_BRANCH_ID],
                $account
            );
        }
    }

    private function createDemoCategories(): void
    {
        // Get the demo user ID
        $demoUser = User::where('email', 'demo@example.com')->first();
        if (!$demoUser) {
            throw new \Exception('Demo user not found');
        }

        $categories = [
            ['name' => 'Penjualan Produk', 'type' => 'income', 'branch_id' => self::DEMO_BRANCH_ID, 'user_id' => $demoUser->id],
            ['name' => 'Jasa Konsultasi', 'type' => 'income', 'branch_id' => self::DEMO_BRANCH_ID, 'user_id' => $demoUser->id],
            ['name' => 'Beli Bahan Baku', 'type' => 'expense', 'branch_id' => self::DEMO_BRANCH_ID, 'user_id' => $demoUser->id],
            ['name' => 'Gaji Karyawan', 'type' => 'expense', 'branch_id' => self::DEMO_BRANCH_ID, 'user_id' => $demoUser->id],
            ['name' => 'Sewa Kantor', 'type' => 'expense', 'branch_id' => self::DEMO_BRANCH_ID, 'user_id' => $demoUser->id],
            ['name' => 'Utilitas', 'type' => 'expense', 'branch_id' => self::DEMO_BRANCH_ID, 'user_id' => $demoUser->id],
            ['name' => 'Transportasi', 'type' => 'expense', 'branch_id' => self::DEMO_BRANCH_ID, 'user_id' => $demoUser->id],
            ['name' => 'Entertainment', 'type' => 'expense', 'branch_id' => self::DEMO_BRANCH_ID, 'user_id' => $demoUser->id],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name'], 'branch_id' => self::DEMO_BRANCH_ID],
                $category
            );
        }
    }

    private function createDemoProductCategories(): void
    {
        // Get the demo user ID
        $demoUser = User::where('email', 'demo@example.com')->first();
        if (!$demoUser) {
            throw new \Exception('Demo user not found');
        }

        $categories = [
            ['name' => 'Elektronik', 'user_id' => $demoUser->id],
            ['name' => 'Pakaian', 'user_id' => $demoUser->id],
            ['name' => 'Makanan', 'user_id' => $demoUser->id],
            ['name' => 'Minuman', 'user_id' => $demoUser->id],
        ];

        foreach ($categories as $category) {
            ProductCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }

    private function createDemoProducts(): void
    {
        // Get the demo user ID
        $demoUser = User::where('email', 'demo@example.com')->first();
        if (!$demoUser) {
            throw new \Exception('Demo user not found');
        }

        $products = [
            [
                'name' => 'Laptop Gaming',
                'sku' => 'PROD001',
                'price' => 15000000,
                'cost_price' => 12000000,
                'stock_quantity' => 5,
                'product_category_id' => 1,
                'user_id' => $demoUser->id,
                'branch_id' => self::DEMO_BRANCH_ID,
            ],
            [
                'name' => 'Kaos Polos',
                'sku' => 'PROD002',
                'price' => 75000,
                'cost_price' => 50000,
                'stock_quantity' => 50,
                'product_category_id' => 2,
                'user_id' => $demoUser->id,
                'branch_id' => self::DEMO_BRANCH_ID,
            ],
            [
                'name' => 'Nasi Goreng Spesial',
                'sku' => 'PROD003',
                'price' => 25000,
                'cost_price' => 15000,
                'stock_quantity' => 100,
                'product_category_id' => 3,
                'user_id' => $demoUser->id,
                'branch_id' => self::DEMO_BRANCH_ID,
            ],
            [
                'name' => 'Jus Jeruk',
                'sku' => 'PROD004',
                'price' => 15000,
                'cost_price' => 8000,
                'stock_quantity' => 200,
                'product_category_id' => 4,
                'user_id' => $demoUser->id,
                'branch_id' => self::DEMO_BRANCH_ID,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name'], 'branch_id' => self::DEMO_BRANCH_ID],
                $product
            );
        }
    }

    private function createDemoCustomers(): void
    {
        // Get the demo user ID
        $demoUser = User::where('email', 'demo@example.com')->first();
        if (!$demoUser) {
            throw new \Exception('Demo user not found');
        }

        $customers = [
            [
                'name' => 'PT. Maju Jaya',
                'email' => 'contact@majujaya.com',
                'phone' => '+62-21-9876543',
                'address' => 'Jl. Sudirman No. 45, Jakarta',
                'user_id' => $demoUser->id,
                'branch_id' => self::DEMO_BRANCH_ID,
            ],
            [
                'name' => 'CV. Sukses Makmur',
                'email' => 'info@suksesmakmur.com',
                'phone' => '+62-21-8765432',
                'address' => 'Jl. Thamrin No. 67, Jakarta',
                'user_id' => $demoUser->id,
                'branch_id' => self::DEMO_BRANCH_ID,
            ],
            [
                'name' => 'Toko Retail ABC',
                'email' => 'abc@retail.com',
                'phone' => '+62-21-7654321',
                'address' => 'Jl. Malioboro No. 12, Yogyakarta',
                'user_id' => $demoUser->id,
                'branch_id' => self::DEMO_BRANCH_ID,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['email' => $customer['email'], 'branch_id' => self::DEMO_BRANCH_ID],
                $customer
            );
        }
    }

    private function createDemoTransactions(): void
    {
        // Get the demo user ID
        $demoUser = User::where('email', 'demo@example.com')->first();
        if (!$demoUser) {
            throw new \Exception('Demo user not found');
        }

        // Get account IDs by name
        $penjualan = Account::where('name', 'Penjualan')->where('branch_id', self::DEMO_BRANCH_ID)->first();
        $hargaPokok = Account::where('name', 'Harga Pokok Penjualan')->where('branch_id', self::DEMO_BRANCH_ID)->first();
        $bebanOperasional = Account::where('name', 'Beban Operasional')->where('branch_id', self::DEMO_BRANCH_ID)->first();

        if (!$penjualan || !$hargaPokok || !$bebanOperasional) {
            throw new \Exception('Required accounts not found');
        }

        $transactions = [
            // Income transactions
            [
                'account_id' => $penjualan->id,
                'amount' => 5000000,
                'description' => 'Penjualan Laptop Gaming ke PT. Maju Jaya',
                'date' => Carbon::now()->subDays(5),
                'type' => 'income',
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'account_id' => $penjualan->id,
                'amount' => 2000000,
                'description' => 'Jasa Konsultasi IT',
                'date' => Carbon::now()->subDays(3),
                'type' => 'income',
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            // Expense transactions
            [
                'account_id' => $hargaPokok->id,
                'amount' => 3000000,
                'description' => 'Pembelian bahan baku elektronik',
                'date' => Carbon::now()->subDays(7),
                'type' => 'expense',
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'account_id' => $bebanOperasional->id,
                'amount' => 15000000,
                'description' => 'Gaji karyawan bulan November',
                'date' => Carbon::now()->subDays(1),
                'type' => 'expense',
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'account_id' => $bebanOperasional->id,
                'amount' => 5000000,
                'description' => 'Sewa kantor bulan November',
                'date' => Carbon::now()->subDays(2),
                'type' => 'expense',
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }

    private function createDemoServices(): void
    {
        // Get the demo user ID
        $demoUser = User::where('email', 'demo@example.com')->first();
        if (!$demoUser) {
            throw new \Exception('Demo user not found');
        }

        $services = [
            [
                'name' => 'Konsultasi IT',
                'description' => 'Layanan konsultasi teknologi informasi dan sistem',
                'price' => 500000,
                'user_id' => $demoUser->id,
                'product_category_id' => 1, // Elektronik
            ],
            [
                'name' => 'Perbaikan Komputer',
                'description' => 'Layanan perbaikan dan maintenance komputer',
                'price' => 150000,
                'user_id' => $demoUser->id,
                'product_category_id' => 1, // Elektronik
            ],
            [
                'name' => 'Instalasi Software',
                'description' => 'Layanan instalasi dan konfigurasi software',
                'price' => 300000,
                'user_id' => $demoUser->id,
                'product_category_id' => 1, // Elektronik
            ],
            [
                'name' => 'Training Pengguna',
                'description' => 'Pelatihan penggunaan sistem dan aplikasi',
                'price' => 750000,
                'user_id' => $demoUser->id,
                'product_category_id' => 1, // Elektronik
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }

    private function createDemoRecurringTemplates(): void
    {
        // Get the demo user ID
        $demoUser = User::where('email', 'demo@example.com')->first();
        if (!$demoUser) {
            throw new \Exception('Demo user not found');
        }

        // Get account IDs by name
        $bebanOperasional = Account::where('name', 'Beban Operasional')->where('branch_id', self::DEMO_BRANCH_ID)->first();
        $penjualan = Account::where('name', 'Penjualan')->where('branch_id', self::DEMO_BRANCH_ID)->first();

        // Get category IDs by name
        $gajiKaryawan = Category::where('name', 'Gaji Karyawan')->where('branch_id', self::DEMO_BRANCH_ID)->first();
        $sewaKantor = Category::where('name', 'Sewa Kantor')->where('branch_id', self::DEMO_BRANCH_ID)->first();
        $utilitas = Category::where('name', 'Utilitas')->where('branch_id', self::DEMO_BRANCH_ID)->first();
        $jasaKonsultasi = Category::where('name', 'Jasa Konsultasi')->where('branch_id', self::DEMO_BRANCH_ID)->first();

        if (!$bebanOperasional || !$penjualan || !$gajiKaryawan || !$sewaKantor || !$utilitas || !$jasaKonsultasi) {
            throw new \Exception('Required accounts or categories not found');
        }

        $templates = [
            [
                'name' => 'Gaji Karyawan Bulanan',
                'description' => 'Pembayaran gaji karyawan setiap bulan',
                'amount' => 15000000,
                'type' => 'expense',
                'frequency' => 'monthly',
                'next_date' => Carbon::now()->addMonth()->startOfMonth(),
                'account_id' => $bebanOperasional->id,
                'category_id' => $gajiKaryawan->id,
                'is_active' => true,
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'name' => 'Sewa Kantor',
                'description' => 'Pembayaran sewa kantor bulanan',
                'amount' => 5000000,
                'type' => 'expense',
                'frequency' => 'monthly',
                'next_date' => Carbon::now()->addMonth()->startOfMonth(),
                'account_id' => $bebanOperasional->id,
                'category_id' => $sewaKantor->id,
                'is_active' => true,
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'name' => 'Utilitas Bulanan',
                'description' => 'Pembayaran listrik, air, dan internet',
                'amount' => 2000000,
                'type' => 'expense',
                'frequency' => 'monthly',
                'next_date' => Carbon::now()->addMonth()->startOfMonth(),
                'account_id' => $bebanOperasional->id,
                'category_id' => $utilitas->id,
                'is_active' => true,
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'name' => 'Penjualan Jasa Konsultasi',
                'description' => 'Pendapatan dari jasa konsultasi IT',
                'amount' => 2000000,
                'type' => 'income',
                'frequency' => 'monthly',
                'next_date' => Carbon::now()->addMonth()->startOfMonth(),
                'account_id' => $penjualan->id,
                'category_id' => $jasaKonsultasi->id,
                'is_active' => true,
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
        ];

        foreach ($templates as $template) {
            RecurringTemplate::updateOrCreate(
                ['name' => $template['name'], 'branch_id' => self::DEMO_BRANCH_ID],
                $template
            );
        }
    }

    private function createDemoStockMovements(): void
    {
        // Get the demo user ID
        $demoUser = User::where('email', 'demo@example.com')->first();
        if (!$demoUser) {
            throw new \Exception('Demo user not found');
        }

        $movements = [
            [
                'product_id' => 1, // Laptop Gaming
                'type' => 'in',
                'quantity' => 5,
                'date' => Carbon::now()->subDays(10),
                'reference' => 'PO-001',
                'notes' => 'Pembelian stock awal Laptop Gaming',
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'product_id' => 1, // Laptop Gaming
                'type' => 'out',
                'quantity' => 1,
                'date' => Carbon::now()->subDays(5),
                'reference' => 'INV-001',
                'notes' => 'Penjualan Laptop Gaming ke PT. Maju Jaya',
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'product_id' => 2, // Kaos Polos
                'type' => 'in',
                'quantity' => 100,
                'date' => Carbon::now()->subDays(8),
                'reference' => 'PO-002',
                'notes' => 'Pembelian stock awal Kaos Polos',
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'product_id' => 2, // Kaos Polos
                'type' => 'out',
                'quantity' => 10,
                'date' => Carbon::now()->subDays(3),
                'reference' => 'INV-002',
                'notes' => 'Penjualan Kaos Polos',
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'product_id' => 3, // Nasi Goreng
                'type' => 'in',
                'quantity' => 200,
                'date' => Carbon::now()->subDays(6),
                'reference' => 'PO-003',
                'notes' => 'Pembelian stock awal Nasi Goreng',
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'product_id' => 4, // Jus Jeruk
                'type' => 'in',
                'quantity' => 300,
                'date' => Carbon::now()->subDays(4),
                'reference' => 'PO-004',
                'notes' => 'Pembelian stock awal Jus Jeruk',
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
        ];

        foreach ($movements as $movement) {
            StockMovement::create($movement);
        }
    }

    private function createDemoTaxSettings(): void
    {
        // Get the demo user ID
        $demoUser = User::where('email', 'demo@example.com')->first();
        if (!$demoUser) {
            throw new \Exception('Demo user not found');
        }

        $taxes = [
            [
                'name' => 'PPN 11%',
                'rate' => 11.0,
                'type' => 'percent',
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'name' => 'PPh 21',
                'rate' => 5.0,
                'type' => 'percent',
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'name' => 'PPh 23',
                'rate' => 2.0,
                'type' => 'percent',
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
            [
                'name' => 'PPh Final 0.5%',
                'rate' => 0.5,
                'type' => 'percent',
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
        ];

        foreach ($taxes as $tax) {
            TaxSetting::updateOrCreate(
                ['name' => $tax['name'], 'branch_id' => self::DEMO_BRANCH_ID],
                $tax
            );
        }
    }

    private function createDemoTransfers(): void
    {
        // Get the demo user ID
        $demoUser = User::where('email', 'demo@example.com')->first();
        if (!$demoUser) {
            throw new \Exception('Demo user not found');
        }

        // Get account IDs by name
        $bankBCA = Account::where('name', 'Bank BCA')->where('branch_id', self::DEMO_BRANCH_ID)->first();
        $kasKecil = Account::where('name', 'Kas Kecil')->where('branch_id', self::DEMO_BRANCH_ID)->first();

        if (!$bankBCA || !$kasKecil) {
            throw new \Exception('Required accounts not found for transfers');
        }

        $transfers = [
            [
                'from_account_id' => $bankBCA->id,
                'to_account_id' => $kasKecil->id,
                'amount' => 2000000,
                'description' => 'Transfer ke kas kecil untuk operasional',
                'date' => Carbon::now()->subDays(4),
                'branch_id' => self::DEMO_BRANCH_ID,
                'user_id' => $demoUser->id,
            ],
        ];

        foreach ($transfers as $transfer) {
            Transfer::create($transfer);
        }
    }
}