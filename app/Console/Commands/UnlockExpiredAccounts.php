<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UnlockExpiredAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:unlock-expired {--dry-run : Show what would be unlocked without actually unlocking}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unlock user accounts that have exceeded their lockout period';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->info('🔍 DRY RUN MODE - No accounts will be actually unlocked');
            $this->newLine();
        }

        // Find locked accounts where lockout period has expired
        $lockedAccounts = User::whereNotNull('locked_until')
            ->where('locked_until', '<=', now())
            ->get();

        if ($lockedAccounts->isEmpty()) {
            $this->info('✅ No expired locked accounts found.');
            return;
        }

        $this->info("🔓 Found {$lockedAccounts->count()} expired locked account(s):");
        $this->newLine();

        // Display table of accounts to be unlocked
        $this->table(
            ['ID', 'Name', 'Email', 'Locked Until', 'Failed Attempts'],
            $lockedAccounts->map(function ($user) {
                return [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->locked_until->format('Y-m-d H:i:s'),
                    $user->failed_login_attempts,
                ];
            })->toArray()
        );

        if ($isDryRun) {
            $this->info("💡 Use --dry-run=false to actually unlock these accounts");
            return;
        }

        // Confirm before proceeding
        if (!$this->confirm('Do you want to unlock these accounts?', true)) {
            $this->info('❌ Operation cancelled.');
            return;
        }

        // Unlock accounts
        $unlockedCount = 0;
        foreach ($lockedAccounts as $user) {
            $user->unlockAccount();
            $unlockedCount++;
            $this->line("✅ Unlocked account: {$user->email}");
        }

        $this->newLine();
        $this->info("🎉 Successfully unlocked {$unlockedCount} account(s)!");

        // Log the operation
        \Log::info("Account unlock command executed: {$unlockedCount} accounts unlocked", [
            'unlocked_accounts' => $lockedAccounts->pluck('email')->toArray(),
            'executed_by' => 'system',
            'timestamp' => now(),
        ]);
    }
}
