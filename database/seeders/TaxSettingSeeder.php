<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Global tax settings
        \App\Models\TaxSetting::create([
            'name' => 'PPN 11%',
            'rate' => 11.00,
            'type' => 'percent',
            'branch_id' => null, // Global
        ]);

        \App\Models\TaxSetting::create([
            'name' => 'PPh 21 5%',
            'rate' => 5.00,
            'type' => 'percent',
            'branch_id' => null, // Global
        ]);

        // Branch-specific tax settings
        $branches = \App\Models\Branch::all();
        foreach ($branches as $branch) {
            \App\Models\TaxSetting::create([
                'name' => 'PPN Cabang ' . $branch->name,
                'rate' => 11.00,
                'type' => 'percent',
                'branch_id' => $branch->id,
            ]);
        }
    }
}
