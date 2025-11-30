<?php

namespace App\Console\Commands;

use App\Models\RecurringTemplate;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProcessRecurringTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-recurring-templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process recurring templates and create transactions for due dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $dueTemplates = RecurringTemplate::where('is_active', true)
            ->where('next_date', '<=', $today)
            ->get();

        $this->info("Found {$dueTemplates->count()} due recurring templates");

        foreach ($dueTemplates as $template) {
            DB::transaction(function () use ($template, $today) {
                // Create transaction
                $transaction = Transaction::create([
                    'user_id' => $template->user_id,
                    'account_id' => $template->account_id,
                    'category_id' => $template->category_id,
                    'amount' => $template->amount,
                    'description' => $template->description,
                    'date' => $template->next_date,
                    'type' => $template->type,
                ]);

                // Update account balance
                if ($template->type === 'income') {
                    $template->account->increment('balance', $template->amount);
                } else {
                    $template->account->decrement('balance', $template->amount);
                }

                // Calculate next date
                $nextDate = $this->calculateNextDate($template->next_date, $template->frequency);

                // Update template
                $template->update([
                    'next_date' => $nextDate,
                ]);

                $this->info("Processed template: {$template->name} - Next: {$nextDate->format('Y-m-d')}");
            });
        }

        $this->info('Recurring templates processed successfully');
    }

    private function calculateNextDate(Carbon $currentDate, string $frequency): Carbon
    {
        switch ($frequency) {
            case 'daily':
                return $currentDate->addDay();
            case 'weekly':
                return $currentDate->addWeek();
            case 'biweekly':
                return $currentDate->addWeeks(2);
            case 'monthly':
                return $currentDate->addMonth();
            case 'quarterly':
                return $currentDate->addMonths(3);
            case 'yearly':
                return $currentDate->addYear();
            default:
                return $currentDate->addMonth(); // Default to monthly
        }
    }
}
