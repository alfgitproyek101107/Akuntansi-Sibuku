<?php

namespace App\Console\Commands;

use App\Services\ApprovalService;
use Illuminate\Console\Command;

class ProcessExpiredApprovals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'approvals:process-expired {--dry-run : Show what would be processed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process expired approval requests';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing expired approvals...');

        $approvalService = app(ApprovalService::class);

        if ($this->option('dry-run')) {
            $this->info('DRY RUN MODE - No changes will be made');

            $expiredApprovals = \App\Models\Approval::pending()
                ->whereNotNull('due_date')
                ->where('due_date', '<', now())
                ->get();

            $this->info("Found {$expiredApprovals->count()} expired approvals:");
            foreach ($expiredApprovals as $approval) {
                $daysOverdue = now()->diffInDays($approval->due_date);
                $this->line("{$approval->approvable_title} (ID: {$approval->id}) - {$daysOverdue} days overdue");
            }

            return;
        }

        $expired = $approvalService->processExpiredApprovals();

        $this->info("Successfully processed {$expired} expired approvals");

        if ($expired > 0) {
            $this->info('Expired approval processing completed successfully');
        }
    }
}
