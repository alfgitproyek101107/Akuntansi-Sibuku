<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Konsultasi IT',
                'description' => 'Layanan konsultasi teknologi informasi dan sistem komputer',
                'price' => 500000,
                'user_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Perbaikan Komputer',
                'description' => 'Layanan perbaikan dan maintenance komputer desktop/laptop',
                'price' => 150000,
                'user_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Instalasi Software',
                'description' => 'Layanan instalasi dan konfigurasi software aplikasi',
                'price' => 300000,
                'user_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Training Pengguna',
                'description' => 'Pelatihan penggunaan sistem dan aplikasi komputer',
                'price' => 750000,
                'user_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Desain Logo',
                'description' => 'Layanan desain logo dan branding perusahaan',
                'price' => 1000000,
                'user_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Fotografi Produk',
                'description' => 'Layanan fotografi produk untuk katalog dan website',
                'price' => 400000,
                'user_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Editing Video',
                'description' => 'Layanan editing video untuk promosi dan presentasi',
                'price' => 600000,
                'user_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Pengembangan Website',
                'description' => 'Layanan pembuatan dan pengembangan website perusahaan',
                'price' => 2500000,
                'user_id' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
