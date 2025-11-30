<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard
            'view dashboard',

            // Accounts
            'view accounts',
            'create accounts',
            'edit accounts',
            'delete accounts',
            'view account ledger',
            'export accounts',

            // Income
            'view incomes',
            'create incomes',
            'edit incomes',
            'delete incomes',

            // Expense
            'view expenses',
            'create expenses',
            'edit expenses',
            'delete expenses',

            // Transfers
            'view transfers',
            'create transfers',
            'edit transfers',
            'delete transfers',

            // Categories
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',

            // Recurring Templates
            'view recurring templates',
            'create recurring templates',
            'edit recurring templates',
            'delete recurring templates',

            // Products
            'view products',
            'create products',
            'edit products',
            'delete products',

            // Product Categories
            'view product categories',
            'create product categories',
            'edit product categories',
            'delete product categories',

            // Customers
            'view customers',
            'create customers',
            'edit customers',
            'delete customers',

            // Stock Movements
            'view stock movements',
            'create stock movements',
            'edit stock movements',
            'delete stock movements',

            // Branches
            'view branches',
            'create branches',
            'edit branches',
            'delete branches',
            'switch branches',

            // Tax Settings
            'view tax settings',
            'create tax settings',
            'edit tax settings',
            'delete tax settings',

            // Users
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Chart of Accounts
            'view chart of accounts',
            'create chart of accounts',
            'edit chart of accounts',
            'delete chart of accounts',
            'toggle chart of accounts',

            // Reports
            'view reports',
            'generate reports',
            'export reports',

            // Services
            'view services',
            'create services',
            'edit services',
            'delete services',

            // Settings
            'view settings',
            'edit general settings',
            'edit notification settings',
            'manage roles',
            'manage permissions',
            'manage branches',
            'system maintenance',

            // Invoices (future feature)
            'view invoices',
            'create invoices',
            'edit invoices',
            'delete invoices',

            // Bills (future feature)
            'view bills',
            'create bills',
            'edit bills',
            'delete bills',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'view dashboard',
            'view accounts', 'create accounts', 'edit accounts', 'delete accounts', 'view account ledger', 'export accounts',
            'view incomes', 'create incomes', 'edit incomes', 'delete incomes',
            'view expenses', 'create expenses', 'edit expenses', 'delete expenses',
            'view transfers', 'create transfers', 'edit transfers', 'delete transfers',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view recurring templates', 'create recurring templates', 'edit recurring templates', 'delete recurring templates',
            'view products', 'create products', 'edit products', 'delete products',
            'view product categories', 'create product categories', 'edit product categories', 'delete product categories',
            'view customers', 'create customers', 'edit customers', 'delete customers',
            'view stock movements', 'create stock movements', 'edit stock movements', 'delete stock movements',
            'view branches', 'create branches', 'edit branches', 'delete branches', 'switch branches',
            'view tax settings', 'create tax settings', 'edit tax settings', 'delete tax settings',
            'view users', 'create users', 'edit users', 'delete users',
            'view chart of accounts', 'create chart of accounts', 'edit chart of accounts', 'delete chart of accounts', 'toggle chart of accounts',
            'view reports', 'generate reports', 'export reports',
            'view services', 'create services', 'edit services', 'delete services',
            'view settings', 'edit general settings', 'edit notification settings',
        ]);

        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->givePermissionTo([
            'view dashboard',
            'view accounts', 'view account ledger', 'export accounts',
            'view incomes', 'create incomes', 'edit incomes',
            'view expenses', 'create expenses', 'edit expenses',
            'view transfers', 'create transfers', 'edit transfers',
            'view categories',
            'view recurring templates', 'create recurring templates',
            'view products', 'create products', 'edit products',
            'view product categories',
            'view customers', 'create customers', 'edit customers',
            'view stock movements', 'create stock movements',
            'view branches', 'switch branches',
            'view tax settings',
            'view users',
            'view chart of accounts',
            'view reports', 'generate reports', 'export reports',
            'view services', 'create services', 'edit services',
            'view settings',
        ]);

        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $staffRole->givePermissionTo([
            'view dashboard',
            'view accounts', 'view account ledger',
            'view incomes', 'create incomes',
            'view expenses', 'create expenses',
            'view transfers', 'create transfers',
            'view categories',
            'view products',
            'view product categories',
            'view customers', 'create customers',
            'view stock movements', 'create stock movements',
            'view branches',
            'view reports',
            'view services',
        ]);

        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);
        $viewerRole->givePermissionTo([
            'view dashboard',
            'view accounts',
            'view incomes',
            'view expenses',
            'view transfers',
            'view categories',
            'view products',
            'view product categories',
            'view customers',
            'view stock movements',
            'view branches',
            'view reports',
            'view services',
        ]);
    }
}