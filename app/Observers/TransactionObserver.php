<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Services\JournalService;
use App\Services\TaxInvoiceService;
use Exception;
use Illuminate\Support\Facades\Log;

class TransactionObserver
{
    protected $journalService;
    protected $taxInvoiceService;

    public function __construct(JournalService $journalService, TaxInvoiceService $taxInvoiceService)
    {
        $this->journalService = $journalService;
        $this->taxInvoiceService = $taxInvoiceService;
    }

    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        try {
            // Automatically create journal entry for the transaction
            $this->journalService->createJournalFromTransaction($transaction);

            Log::info('Journal entry created for transaction', [
                'transaction_id' => $transaction->id,
                'type' => $transaction->type,
                'amount' => $transaction->amount,
            ]);
        } catch (Exception $e) {
            Log::error('Failed to create journal entry for transaction', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
        }

        try {
            // Calculate tax automatically if enabled
            if ($transaction->is_taxable) {
                $transaction->calculateTax();

                // Save the calculated tax amounts
                $transaction->save();
            }

            // Generate tax invoice if required
            if ($transaction->shouldGenerateTaxInvoice()) {
                $taxInvoice = $this->taxInvoiceService->generateFromTransaction($transaction);

                if ($taxInvoice) {
                    Log::info('Tax invoice generated for transaction', [
                        'transaction_id' => $transaction->id,
                        'tax_invoice_id' => $taxInvoice->id,
                        'invoice_number' => $taxInvoice->invoice_number,
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::error('Failed to process tax for transaction', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        try {
            // Recalculate tax if relevant fields changed
            if ($transaction->is_taxable && $transaction->wasChanged(['amount', 'tax_type', 'is_taxable', 'tax_included_in_price'])) {
                $transaction->calculateTax();
                $transaction->save(); // Save without triggering observer again
            }

            // Update tax invoice if it exists and relevant data changed
            if ($transaction->taxInvoice && $transaction->wasChanged(['amount', 'tax_amount', 'customer_name', 'customer_npwp'])) {
                // Mark tax invoice as needing update
                $transaction->taxInvoice->update(['status' => 'needs_update']);
            }
        } catch (Exception $e) {
            Log::error('Failed to update tax for transaction', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
