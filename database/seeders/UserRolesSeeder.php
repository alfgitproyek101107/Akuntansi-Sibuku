<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'description' => 'Akses penuh ke semua fitur sistem',
            ],
            [
                'name' => 'Admin',
                'description' => 'Administrator dengan akses mayoritas fitur',
            ],
            [
                'name' => 'Branch Manager',
                'description' => 'Manager cabang dengan akses transaksi dan laporan',
            ],
            [
                'name' => 'Kasir',
                'description' => 'Kasir dengan akses transaksi dan faktur',
            ],
            [
                'name' => 'Inventory Manager',
                'description' => 'Manager inventori dengan akses produk dan stok',
            ],
            [
                'name' => 'Auditor',
                'description' => 'Auditor dengan akses read-only',
            ],
        ];

        foreach ($roles as $role) {
            UserRole::create($role);
        }
    }
}
