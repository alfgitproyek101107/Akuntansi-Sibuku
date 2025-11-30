<?php

namespace App\Console\Commands;

use App\Services\WhatsAppReportService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendScheduledWhatsAppReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp-reports:send-scheduled
                            {--dry-run : Show what would be sent without actually sending}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled WhatsApp reports based on system settings';

    protected WhatsAppReportService $reportService;

    public function __construct(WhatsAppReportService $reportService)
    {
        parent::__construct();
        $this->reportService = $reportService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for scheduled WhatsApp reports...');

        // Check if WhatsApp reports are enabled
        if (!config('app_settings.whatsapp_reports_enabled', false)) {
            $this->info('WhatsApp reports are disabled in settings.');
            return;
        }

        $now = now();
        $currentTime = $now->format('H:i');
        $currentDayOfWeek = $now->dayOfWeek; // 0 = Sunday, 1 = Monday, etc.
        $currentDayOfMonth = $now->day;

        $this->info("Current time: {$currentTime}, Day of week: {$currentDayOfWeek}, Day of month: {$currentDayOfMonth}");

        $reportsToSend = [];

        // Check daily report
        $dailyTime = config('app_settings.whatsapp_daily_time');
        if ($dailyTime && $currentTime === $dailyTime) {
            $reportsToSend[] = [
                'type' => 'daily',
                'time' => $dailyTime,
                'description' => 'Daily report'
            ];
        }

        // Check weekly report
        $weeklyDay = config('app_settings.whatsapp_weekly_day');
        $weeklyTime = config('app_settings.whatsapp_weekly_time');
        if ($weeklyDay !== null && $weeklyTime && $currentDayOfWeek == $weeklyDay && $currentTime === $weeklyTime) {
            $reportsToSend[] = [
                'type' => 'weekly',
                'time' => $weeklyTime,
                'description' => 'Weekly report'
            ];
        }

        // Check monthly report
        $monthlyDay = config('app_settings.whatsapp_monthly_day');
        $monthlyTime = config('app_settings.whatsapp_monthly_time');
        if ($monthlyDay && $monthlyTime && $currentDayOfMonth == $monthlyDay && $currentTime === $monthlyTime) {
            $reportsToSend[] = [
                'type' => 'monthly',
                'time' => $monthlyTime,
                'description' => 'Monthly report'
            ];
        }

        if (empty($reportsToSend)) {
            $this->info('No scheduled reports due at this time.');
            return;
        }

        $this->info("Found " . count($reportsToSend) . " scheduled report(s) to send.");

        $ownerNumber = config('app_settings.whatsapp_owner_number');
        if (!$ownerNumber) {
            $this->error('Owner WhatsApp number not configured in settings.');
            return;
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($reportsToSend as $report) {
            try {
                $this->line("Processing {$report['description']} ({$report['type']}) for {$ownerNumber}");

                if ($this->option('dry-run')) {
                    $this->info("DRY RUN: Would send {$report['type']} report to {$ownerNumber}");
                    continue;
                }

                // Send the report
                $result = $this->reportService->sendReport(
                    $report['type'],
                    $ownerNumber,
                    null, // branch_id (null for all branches)
                    null, // user_id (system)
                    'scheduled'
                );

                if ($result['success']) {
                    $sentCount++;
                    $this->info("✓ Sent {$report['type']} report to {$ownerNumber}");
                } else {
                    $failedCount++;
                    $this->error("✗ Failed to send {$report['type']} report: {$result['message']}");
                }

            } catch (\Exception $e) {
                $failedCount++;
                $this->error("✗ Failed to send {$report['type']} report: {$e->getMessage()}");

                Log::error('Failed to send scheduled WhatsApp report', [
                    'report_type' => $report['type'],
                    'owner_number' => $ownerNumber,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("\nSummary:");
        $this->info("✓ Sent: {$sentCount}");
        if ($failedCount > 0) {
            $this->error("✗ Failed: {$failedCount}");
        }

        Log::info('Scheduled WhatsApp reports processed', [
            'total_due' => count($reportsToSend),
            'sent' => $sentCount,
            'failed' => $failedCount,
            'dry_run' => $this->option('dry-run')
        ]);
    }
}