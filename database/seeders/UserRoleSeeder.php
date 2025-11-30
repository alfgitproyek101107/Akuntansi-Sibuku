<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\UserRole::create([
            'name' => 'Admin',
            'description' => 'Administrator dengan akses penuh ke semua fitur sistem',
        ]);

        \App\Models\UserRole::create([
            'name' => 'Manager',
            'description' => 'Manager yang dapat mengelola transaksi dan laporan',
        ]);

        \App\Models\UserRole::create([
            'name' => 'Staff',
            'description' => 'Staff dengan akses terbatas untuk input data',
        ]);
    }
}
