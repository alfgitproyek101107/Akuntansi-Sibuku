<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\DemoSeeder;
use App\Models\User;
use App\Models\Branch;

class ResetDemoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:reset
                            {--force : Force reset without confirmation}
                            {--auto : Run automatically (for scheduled resets)}
                            {--check-only : Only check if reset is needed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset demo data to initial state with enterprise protection';

    private const DEMO_BRANCH_ID = 999;
    private const DEMO_RESET_HOURS = 24; // Reset every 24 hours

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if reset is needed
        if ($this->option('check-only')) {
            return $this->checkIfResetNeeded();
        }

        // Auto mode for scheduled resets
        if ($this->option('auto')) {
            return $this->autoReset();
        }

        // Manual reset with confirmation
        if (!$this->option('force')) {
            if (!$this->confirm('This will reset all demo data. Continue?')) {
                $this->info('Reset cancelled.');
                return;
            }
        }

        return $this->performReset();
    }

    /**
     * Check if demo reset is needed
     */
    private function checkIfResetNeeded()
    {
        $lastReset = Cache::get('demo_last_reset', now()->subHours(self::DEMO_RESET_HOURS + 1));
        $needsReset = $lastReset->addHours(self::DEMO_RESET_HOURS)->isPast();

        if ($needsReset) {
            $this->info('Demo data reset is needed.');
            return 1; // Exit code 1 means reset needed
        } else {
            $this->info('Demo data reset not needed yet.');
            return 0; // Exit code 0 means no reset needed
        }
    }

    /**
     * Auto reset for scheduled execution
     */
    private function autoReset()
    {
        $lastReset = Cache::get('demo_last_reset', now()->subHours(self::DEMO_RESET_HOURS + 1));

        if ($lastReset->addHours(self::DEMO_RESET_HOURS)->isPast()) {
            $this->info('Auto-resetting demo data...');
            return $this->performReset(true);
        } else {
            $this->info('Demo data auto-reset not needed yet.');
            return 0;
        }
    }

    /**
     * Perform the actual reset
     */
    private function performReset($isAuto = false)
    {
        $this->info('Starting demo data reset...');

        try {
            DB::beginTransaction();

            // Backup current demo state (for audit purposes)
            $this->backupDemoState();

            // Truncate demo data
            $this->truncateDemoData();

            // Re-seed demo data
            $this->reseedDemoData();

            // Update reset timestamp
            Cache::put('demo_last_reset', now(), now()->addHours(self::DEMO_RESET_HOURS * 2));

            // Log the reset
            $this->logReset($isAuto);

            DB::commit();

            $this->info('Demo data reset completed successfully!');

            // Force logout demo users
            $this->forceLogoutDemoUsers();

            return 0;

        } catch (\Exception $e) {
            DB::rollback();
            $this->error('Failed to reset demo data: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Backup current demo state for audit
     */
    private function backupDemoState()
    {
        $demoStats = [
            'accounts' => DB::table('accounts')->where('branch_id', self::DEMO_BRANCH_ID)->count(),
            'transactions' => DB::table('transactions')->where('branch_id', self::DEMO_BRANCH_ID)->count(),
            'products' => DB::table('products')->where('branch_id', self::DEMO_BRANCH_ID)->count(),
            'customers' => DB::table('customers')->where('branch_id', self::DEMO_BRANCH_ID)->count(),
            'timestamp' => now()
        ];

        Cache::put('demo_backup_' . now()->format('Y-m-d_H-i-s'), $demoStats, now()->addDays(7));
    }

    /**
     * Truncate demo data tables
     */
    private function truncateDemoData()
    {
        $this->info('Truncating demo data...');

        $tables = [
            'transactions',
            'transfers',
            'stock_movements',
            'invoices',
            'invoice_items',
            'payments',
            'products',
            'product_categories',
            'customers',
            'categories',
            'accounts',
            'recurring_templates',
            'journal_entries',
            'journal_lines',
        ];

        foreach ($tables as $table) {
            $count = DB::table($table)
                ->where('branch_id', self::DEMO_BRANCH_ID)
                ->delete();

            $this->line("Deleted {$count} records from {$table}");
        }
    }

    /**
     * Re-seed demo data
     */
    private function reseedDemoData()
    {
        $this->info('Re-seeding demo data...');

        $seeder = new DemoSeeder();
        $seeder->run();

        $this->info('Demo data seeded successfully');
    }

    /**
     * Log the reset operation
     */
    private function logReset($isAuto)
    {
        $logData = [
            'type' => 'demo_reset',
            'auto' => $isAuto,
            'timestamp' => now(),
            'ip' => request()->ip() ?? 'console',
            'user_agent' => request()->userAgent() ?? 'console'
        ];

        // Store in cache for audit purposes
        $existingLogs = Cache::get('demo_reset_logs', []);
        $existingLogs[] = $logData;

        // Keep only last 50 logs
        if (count($existingLogs) > 50) {
            $existingLogs = array_slice($existingLogs, -50);
        }

        Cache::put('demo_reset_logs', $existingLogs, now()->addMonths(6));
    }

    /**
     * Force logout demo users
     */
    private function forceLogoutDemoUsers()
    {
        // Invalidate sessions for demo users
        $demoUsers = User::where('demo_mode', true)->get();

        foreach ($demoUsers as $user) {
            // Clear user sessions (simplified - in production you'd use a more robust session management)
            Cache::forget("user_sessions_{$user->id}");
        }

        $this->info('Demo users logged out successfully');
    }
}
