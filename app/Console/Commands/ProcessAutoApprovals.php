<?php

namespace App\Console\Commands;

use App\Services\ApprovalService;
use Illuminate\Console\Command;

class ProcessAutoApprovals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'approvals:process-auto-approvals {--dry-run : Show what would be processed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process auto-approvals for overdue approval requests';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing auto-approvals...');

        $approvalService = app(ApprovalService::class);

        if ($this->option('dry-run')) {
            $this->info('DRY RUN MODE - No changes will be made');

            $approvals = \App\Models\Approval::pending()
                ->whereNotNull('due_date')
                ->get();

            $autoApprovals = 0;
            foreach ($approvals as $approval) {
                if ($approval->requested_at->addDays($approval->workflow->getAutoApproveDays() ?? 0)->isPast()) {
                    $autoApprovals++;
                    $this->line("Would auto-approve: {$approval->approvable_title} (ID: {$approval->id})");
                }
            }

            $this->info("Found {$autoApprovals} approvals that would be auto-approved");
            return;
        }

        $processed = $approvalService->processAutoApprovals();

        $this->info("Successfully processed {$processed} auto-approvals");

        if ($processed > 0) {
            $this->info('Auto-approval processing completed successfully');
        }
    }
}
