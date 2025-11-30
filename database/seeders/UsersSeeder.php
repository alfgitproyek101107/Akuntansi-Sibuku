<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@sibuku.com',
                'password' => bcrypt('password'),
                'branch_id' => 1000, // Pusat Jakarta
                'user_role_id' => 1, // Super Admin
            ],
            [
                'name' => 'Admin Pusat',
                'email' => 'admin.pusat@sibuku.com',
                'password' => bcrypt('password'),
                'branch_id' => 1000, // Pusat Jakarta
                'user_role_id' => 2, // Admin
            ],
            [
                'name' => 'Manager Bandung',
                'email' => 'manager.bandung@sibuku.com',
                'password' => bcrypt('password'),
                'branch_id' => 1001, // Bandung
                'user_role_id' => 3, // Branch Manager
            ],
            [
                'name' => 'Kasir Surabaya',
                'email' => 'kasir.surabaya@sibuku.com',
                'password' => bcrypt('password'),
                'branch_id' => 1002, // Surabaya
                'user_role_id' => 4, // Kasir
            ],
            [
                'name' => 'Staff Inventory',
                'email' => 'inventory@sibuku.com',
                'password' => bcrypt('password'),
                'branch_id' => 1000, // Pusat Jakarta
                'user_role_id' => 5, // Inventory Manager
            ],
            [
                'name' => 'Auditor',
                'email' => 'auditor@sibuku.com',
                'password' => bcrypt('password'),
                'branch_id' => 1000, // Pusat Jakarta
                'user_role_id' => 6, // Auditor
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
