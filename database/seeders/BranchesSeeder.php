<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Pusat Jakarta',
                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta 10220',
                'phone' => '021-12345678',
                'email' => 'pusat@akuntansisibuku.com',
            ],
            [
                'name' => 'Cabang Bandung',
                'address' => 'Jl. Asia Afrika No. 45, Bandung, Jawa Barat 40111',
                'phone' => '022-87654321',
                'email' => 'bandung@akuntansisibuku.com',
            ],
            [
                'name' => 'Cabang Surabaya',
                'address' => 'Jl. Tunjungan No. 67, Surabaya, Jawa Timur 60275',
                'phone' => '031-98765432',
                'email' => 'surabaya@akuntansisibuku.com',
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
