<?php

namespace App\Observers;

use App\Models\Transfer;
use App\Services\JournalService;
use Exception;
use Illuminate\Support\Facades\Log;

class TransferObserver
{
    protected $journalService;

    public function __construct(JournalService $journalService)
    {
        $this->journalService = $journalService;
    }

    /**
     * Handle the Transfer "created" event.
     */
    public function created(Transfer $transfer): void
    {
        try {
            // Automatically create journal entry for the transfer
            $this->journalService->createJournalFromTransfer($transfer);

            Log::info('Journal entry created for transfer', [
                'transfer_id' => $transfer->id,
                'from_account' => $transfer->from_account_id,
                'to_account' => $transfer->to_account_id,
                'amount' => $transfer->amount,
            ]);
        } catch (Exception $e) {
            Log::error('Failed to create journal entry for transfer', [
                'transfer_id' => $transfer->id,
                'error' => $e->getMessage(),
            ]);

            // Log error but don't prevent transfer from being saved
        }
    }

    /**
     * Handle the Transfer "updated" event.
     */
    public function updated(Transfer $transfer): void
    {
        //
    }

    /**
     * Handle the Transfer "deleted" event.
     */
    public function deleted(Transfer $transfer): void
    {
        //
    }

    /**
     * Handle the Transfer "restored" event.
     */
    public function restored(Transfer $transfer): void
    {
        //
    }

    /**
     * Handle the Transfer "force deleted" event.
     */
    public function forceDeleted(Transfer $transfer): void
    {
        //
    }
}
