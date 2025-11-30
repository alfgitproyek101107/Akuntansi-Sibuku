<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedStarterData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'akuntansi:seed-starter {--fresh : Drop all tables and re-run all migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed starter data for Akuntansi Sibuku (recommended for new installations)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🌱 Seeding starter data for Akuntansi Sibuku...');

        if ($this->option('fresh')) {
            $this->info('🔄 Running fresh migrations...');
            $this->call('migrate:fresh', ['--seed' => false]);
        }

        $this->info('📊 Running starter seeder...');
        $this->call('db:seed', ['--class' => 'StarterSeeder']);

        $this->newLine();
        $this->info('✅ Starter data seeded successfully!');
        $this->line('Demo Account:');
        $this->line('  Email: demo@akuntansisibuku.com');
        $this->line('  Password: password');
        $this->newLine();
        $this->info('🚀 You can now login and start using Akuntansi Sibuku!');
    }
}
