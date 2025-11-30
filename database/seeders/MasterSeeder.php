<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Call individual seeders first
        $this->call([
            BranchSeeder::class,
            UserRoleSeeder::class,
            TaxSettingSeeder::class,
        ]);

        // Create users with different roles
        $adminRole = \App\Models\UserRole::where('name', 'Admin')->first();
        $managerRole = \App\Models\UserRole::where('name', 'Manager')->first();
        $staffRole = \App\Models\UserRole::where('name', 'Staff')->first();
        $jakartaBranch = \App\Models\Branch::where('name', 'Kantor Pusat Jakarta')->first();

        // Admin user
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@akuntansisibuku.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
                'branch_id' => $jakartaBranch->id,
                'user_role_id' => $adminRole->id,
            ]
        );

        // Manager users
        \App\Models\User::firstOrCreate(
            ['email' => 'manager.jakarta@akuntansisibuku.com'],
            [
                'name' => 'Manager Jakarta',
                'password' => bcrypt('password'),
                'branch_id' => $jakartaBranch->id,
                'user_role_id' => $managerRole->id,
            ]
        );

        // Staff users
        \App\Models\User::firstOrCreate(
            ['email' => 'staff.jakarta@akuntansisibuku.com'],
            [
                'name' => 'Staff Jakarta',
                'password' => bcrypt('password'),
                'branch_id' => $jakartaBranch->id,
                'user_role_id' => $staffRole->id,
            ]
        );

        // Create accounts
        $accounts = [
            ['name' => 'Kas Kecil Jakarta', 'type' => 'checking', 'balance' => 5000000, 'user_id' => 1, 'branch_id' => $jakartaBranch->id],
            ['name' => 'BCA Jakarta', 'type' => 'checking', 'balance' => 25000000, 'user_id' => 1, 'branch_id' => $jakartaBranch->id],
            ['name' => 'Tabungan Jakarta', 'type' => 'savings', 'balance' => 100000000, 'user_id' => 1, 'branch_id' => $jakartaBranch->id],
            ['name' => 'Kartu Kredit BCA', 'type' => 'credit', 'balance' => -5000000, 'user_id' => 1, 'branch_id' => $jakartaBranch->id],
        ];

        foreach ($accounts as $account) {
            \App\Models\Account::create($account);
        }

        // Create categories
        $incomeCategories = [
            'Gaji Karyawan',
            'Penjualan Produk',
            'Jasa Konsultasi',
            'Pendapatan Sewa',
            'Bonus & Komisi',
            'Pendapatan Investasi',
        ];

        $expenseCategories = [
            'Biaya Operasional',
            'Biaya Marketing',
            'Biaya Transportasi',
            'Biaya Utilitas',
            'Biaya Maintenance',
            'Biaya Administrasi',
            'Biaya Training',
            'Biaya Entertainment',
        ];

        foreach ($incomeCategories as $category) {
            \App\Models\Category::create([
                'name' => $category,
                'type' => 'income',
                'user_id' => 1,
            ]);
        }

        foreach ($expenseCategories as $category) {
            \App\Models\Category::create([
                'name' => $category,
                'type' => 'expense',
                'user_id' => 1,
            ]);
        }

        // Create product categories
        $productCategories = [
            'Elektronik',
            'Pakaian',
            'Makanan & Minuman',
            'Kosmetik',
            'Otomotif',
            'Peralatan Rumah Tangga',
        ];

        foreach ($productCategories as $category) {
            \App\Models\ProductCategory::create([
                'name' => $category,
                'description' => 'Kategori produk ' . $category,
                'user_id' => 1, // Admin user
            ]);
        }

        // Create products
        $products = [
            ['name' => 'Laptop Gaming', 'sku' => 'LPT-001', 'description' => 'Laptop gaming high performance', 'price' => 18000000, 'cost_price' => 15000000, 'stock_quantity' => 5, 'unit' => 'pcs', 'product_category_id' => 1, 'user_id' => 1, 'is_active' => true],
            ['name' => 'Smartphone Android', 'sku' => 'SPH-001', 'description' => 'Smartphone flagship', 'price' => 6500000, 'cost_price' => 5000000, 'stock_quantity' => 10, 'unit' => 'pcs', 'product_category_id' => 1, 'user_id' => 1, 'is_active' => true],
            ['name' => 'Kaos Polos', 'sku' => 'KSP-001', 'description' => 'Kaos polos cotton combed', 'price' => 75000, 'cost_price' => 50000, 'stock_quantity' => 50, 'unit' => 'pcs', 'product_category_id' => 2, 'user_id' => 1, 'is_active' => true],
            ['name' => 'Kopi Arabica', 'sku' => 'KOP-001', 'description' => 'Kopi arabica premium', 'price' => 150000, 'cost_price' => 100000, 'stock_quantity' => 20, 'unit' => 'kg', 'product_category_id' => 3, 'user_id' => 1, 'is_active' => true],
            ['name' => 'Lipstick Matte', 'sku' => 'LPS-001', 'description' => 'Lipstick matte long lasting', 'price' => 50000, 'cost_price' => 25000, 'stock_quantity' => 30, 'unit' => 'pcs', 'product_category_id' => 4, 'user_id' => 1, 'is_active' => true],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }

        // Create customers
        $customers = [
            ['name' => 'PT. Maju Jaya', 'email' => 'contact@majujaya.com', 'phone' => '+62-21-1234567', 'address' => 'Jl. Maju No. 123, Jakarta', 'user_id' => 1],
            ['name' => 'CV. Sukses Makmur', 'email' => 'info@suksesmakmur.com', 'phone' => '+62-21-7654321', 'address' => 'Jl. Sukses No. 456, Bandung', 'user_id' => 1],
            ['name' => 'Ahmad Rahman', 'email' => 'ahmad.rahman@email.com', 'phone' => '+62-812-3456789', 'address' => 'Jl. Merdeka No. 789, Surabaya', 'user_id' => 1],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@email.com', 'phone' => '+62-811-9876543', 'address' => 'Jl. Sudirman No. 321, Medan', 'user_id' => 1],
        ];

        foreach ($customers as $customer) {
            \App\Models\Customer::create($customer);
        }

        // Create services
        $services = [
            ['name' => 'Konsultasi IT', 'description' => 'Layanan konsultasi teknologi informasi', 'price' => 500000, 'user_id' => 1, 'product_category_id' => 1],
            ['name' => 'Perbaikan Komputer', 'description' => 'Service perbaikan hardware & software', 'price' => 150000, 'user_id' => 1, 'product_category_id' => 1],
            ['name' => 'Training Software', 'description' => 'Pelatihan penggunaan software', 'price' => 750000, 'user_id' => 1, 'product_category_id' => 1],
        ];

        foreach ($services as $service) {
            \App\Models\Service::create($service);
        }

        // Create sample transactions
        $accounts = \App\Models\Account::all();
        $incomeCategories = \App\Models\Category::where('type', 'income')->get();
        $expenseCategories = \App\Models\Category::where('type', 'expense')->get();

        // Income transactions
        for ($i = 0; $i < 20; $i++) {
            \App\Models\Transaction::create([
                'date' => now()->subDays(rand(0, 30)),
                'type' => 'income',
                'amount' => rand(500000, 5000000),
                'description' => 'Pemasukan ' . ($i + 1),
                'account_id' => $accounts->random()->id,
                'category_id' => $incomeCategories->random()->id,
                'tax_rate' => 11.00,
                'tax_amount' => 0, // Will be calculated
                'user_id' => 1,
            ]);
        }

        // Expense transactions
        for ($i = 0; $i < 15; $i++) {
            \App\Models\Transaction::create([
                'date' => now()->subDays(rand(0, 30)),
                'type' => 'expense',
                'amount' => rand(100000, 1000000),
                'description' => 'Pengeluaran ' . ($i + 1),
                'account_id' => $accounts->random()->id,
                'category_id' => $expenseCategories->random()->id,
                'tax_rate' => 0,
                'tax_amount' => 0,
                'user_id' => 1,
            ]);
        }

        // Create transfers
        for ($i = 0; $i < 5; $i++) {
            $fromAccount = $accounts->random();
            $toAccount = $accounts->where('id', '!=', $fromAccount->id)->random();

            \App\Models\Transfer::create([
                'date' => now()->subDays(rand(0, 30)),
                'amount' => rand(500000, 2000000),
                'description' => 'Transfer antar rekening ' . ($i + 1),
                'from_account_id' => $fromAccount->id,
                'to_account_id' => $toAccount->id,
                'user_id' => 1,
            ]);
        }

        // Create stock movements
        $products = \App\Models\Product::all();
        for ($i = 0; $i < 10; $i++) {
            $product = $products->random();
            $type = rand(0, 1) ? 'in' : 'out';
            $quantity = rand(1, 10);

            \App\Models\StockMovement::create([
                'product_id' => $product->id,
                'type' => $type,
                'quantity' => $quantity,
                'notes' => ucfirst($type) . ' stock ' . $product->name,
                'date' => now()->subDays(rand(0, 30)),
                'user_id' => 1,
            ]);
        }

        // Create invoices
        $customers = \App\Models\Customer::all();
        for ($i = 0; $i < 8; $i++) {
            $customer = $customers->random();
            $subtotal = rand(1000000, 5000000);
            $taxAmount = $subtotal * 0.11;

            \App\Models\Invoice::create([
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad(($i + 1), 4, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'issue_date' => now()->subDays(rand(0, 30)),
                'due_date' => now()->addDays(rand(7, 30)),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $subtotal + $taxAmount,
                'status' => ['draft', 'sent', 'paid'][rand(0, 2)],
                'branch_id' => $jakartaBranch->id,
            ]);
        }
    }
}
