<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;

class UserBranchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users and branches
        $users = User::all();
        $branches = Branch::all();

        if ($users->isEmpty() || $branches->isEmpty()) {
            $this->command->info('No users or branches found. Skipping user-branches seeding.');
            return;
        }

        // Clear existing user_branches data
        DB::table('user_branches')->truncate();

        // Assign users to branches
        foreach ($users as $user) {
            // If user has super-admin role, give access to all branches
            if ($user->hasRole('super-admin')) {
                foreach ($branches as $branch) {
                    DB::table('user_branches')->insert([
                        'user_id' => $user->id,
                        'branch_id' => $branch->id,
                        'role_name' => 'super-admin',
                        'is_default' => $branch->is_head_office ?? false,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                // For regular users, assign to first branch as default
                $defaultBranch = $branches->first();

                DB::table('user_branches')->insert([
                    'user_id' => $user->id,
                    'branch_id' => $defaultBranch->id,
                    'role_name' => 'staff', // Default role
                    'is_default' => true,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Optionally assign to additional branches
                if ($branches->count() > 1) {
                    $additionalBranches = $branches->skip(1)->take(2); // Assign to up to 2 more branches
                    foreach ($additionalBranches as $branch) {
                        DB::table('user_branches')->insert([
                            'user_id' => $user->id,
                            'branch_id' => $branch->id,
                            'role_name' => 'staff',
                            'is_default' => false,
                            'is_active' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        $this->command->info('User-branches relationships seeded successfully.');
    }
}
