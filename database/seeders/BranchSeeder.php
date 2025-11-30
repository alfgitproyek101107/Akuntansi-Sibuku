<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Branch::create([
            'name' => 'Kantor Pusat Jakarta',
            'address' => 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta 10220',
            'phone' => '+62-21-12345678',
            'email' => 'jakarta@akuntansisibuku.com',
        ]);

        \App\Models\Branch::create([
            'name' => 'Cabang Bandung',
            'address' => 'Jl. Asia Afrika No. 456, Bandung, Jawa Barat 40111',
            'phone' => '+62-22-87654321',
            'email' => 'bandung@akuntansisibuku.com',
        ]);

        \App\Models\Branch::create([
            'name' => 'Cabang Surabaya',
            'address' => 'Jl. Tunjungan No. 789, Surabaya, Jawa Timur 60275',
            'phone' => '+62-31-11223344',
            'email' => 'surabaya@akuntansisibuku.com',
        ]);

        \App\Models\Branch::create([
            'name' => 'Cabang Medan',
            'address' => 'Jl. Thamrin No. 321, Medan, Sumatera Utara 20211',
            'phone' => '+62-61-44556677',
            'email' => 'medan@akuntansisibuku.com',
        ]);
    }
}
