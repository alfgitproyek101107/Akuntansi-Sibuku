<?php

namespace App\Services;

use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Exception;

class JournalService
{
    /**
     * Create journal entry from transaction
     *
     * @param Transaction $transaction
     * @return JournalEntry
     * @throws Exception
     */
    public function createJournalFromTransaction(Transaction $transaction): JournalEntry
    {
        DB::beginTransaction();

        try {
            // Create journal entry
            $journalEntry = JournalEntry::create([
                'date' => $transaction->date,
                'reference' => 'TXN-' . $transaction->id . '-' . time() . '-' . rand(1000, 9999),
                'description' => $this->getTransactionDescription($transaction),
                'status' => 'posted',
                'posted_at' => now(),
                'source_type' => Transaction::class,
                'source_id' => $transaction->id,
                'branch_id' => $transaction->branch_id,
                'created_by' => $transaction->created_by ?? auth()->id() ?? $transaction->user_id,
            ]);

            // Create journal lines based on transaction type
            $this->createJournalLinesForTransaction($journalEntry, $transaction);

            DB::commit();
            return $journalEntry;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to create journal entry: ' . $e->getMessage());
        }
    }

    /**
     * Create journal entry from transfer
     *
     * @param Transfer $transfer
     * @return JournalEntry
     * @throws Exception
     */
    public function createJournalFromTransfer(Transfer $transfer): JournalEntry
    {
        DB::beginTransaction();

        try {
            // Create journal entry
            $journalEntry = JournalEntry::create([
                'date' => $transfer->date,
                'reference' => 'TRF-' . $transfer->id . '-' . time() . '-' . rand(1000, 9999),
                'description' => 'Transfer antar rekening: ' . $transfer->fromAccount->name . ' ke ' . $transfer->toAccount->name,
                'status' => 'posted',
                'posted_at' => now(),
                'source_type' => Transfer::class,
                'source_id' => $transfer->id,
                'branch_id' => $transfer->branch_id,
                'created_by' => $transfer->created_by ?? auth()->id() ?? $transfer->user_id,
            ]);

            // Create journal lines for transfer
            $this->createJournalLinesForTransfer($journalEntry, $transfer);

            DB::commit();
            return $journalEntry;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to create journal entry for transfer: ' . $e->getMessage());
        }
    }

    /**
     * Create journal lines for transaction
     *
     * @param JournalEntry $journalEntry
     * @param Transaction $transaction
     */
    private function createJournalLinesForTransaction(JournalEntry $journalEntry, Transaction $transaction): void
    {
        $accountId = $transaction->account_id;
        $amount = $transaction->amount;

        if ($transaction->type === 'income') {
            // Debit cash/bank account, Credit income account
            $incomeAccount = $this->getIncomeAccount($transaction);

            // Debit cash/bank
            JournalLine::create([
                'journal_entry_id' => $journalEntry->id,
                'chart_of_account_id' => $accountId,
                'debit' => $amount,
                'credit' => 0,
                'description' => 'Penerimaan ' . $transaction->description,
            ]);

            // Credit income
            JournalLine::create([
                'journal_entry_id' => $journalEntry->id,
                'chart_of_account_id' => $incomeAccount->id,
                'debit' => 0,
                'credit' => $amount,
                'description' => 'Pendapatan ' . $transaction->description,
            ]);

        } elseif ($transaction->type === 'expense') {
            // Debit expense account, Credit cash/bank account
            $expenseAccount = $this->getExpenseAccount($transaction);

            // Debit expense
            JournalLine::create([
                'journal_entry_id' => $journalEntry->id,
                'chart_of_account_id' => $expenseAccount->id,
                'debit' => $amount,
                'credit' => 0,
                'description' => 'Beban ' . $transaction->description,
            ]);

            // Credit cash/bank
            JournalLine::create([
                'journal_entry_id' => $journalEntry->id,
                'chart_of_account_id' => $accountId,
                'debit' => 0,
                'credit' => $amount,
                'description' => 'Pembayaran ' . $transaction->description,
            ]);
        }
    }

    /**
     * Create journal lines for transfer
     *
     * @param JournalEntry $journalEntry
     * @param Transfer $transfer
     */
    private function createJournalLinesForTransfer(JournalEntry $journalEntry, Transfer $transfer): void
    {
        $fromAccountId = $transfer->from_account_id;
        $toAccountId = $transfer->to_account_id;
        $amount = $transfer->amount;

        // Debit from account
        JournalLine::create([
            'journal_entry_id' => $journalEntry->id,
            'chart_of_account_id' => $fromAccountId,
            'debit' => $amount,
            'credit' => 0,
            'description' => 'Transfer keluar ke ' . $transfer->toAccount->name,
        ]);

        // Credit to account
        JournalLine::create([
            'journal_entry_id' => $journalEntry->id,
            'chart_of_account_id' => $toAccountId,
            'debit' => 0,
            'credit' => $amount,
            'description' => 'Transfer masuk dari ' . $transfer->fromAccount->name,
        ]);
    }

    /**
     * Get appropriate income account for transaction
     *
     * @param Transaction $transaction
     * @return ChartOfAccount
     */
    private function getIncomeAccount(Transaction $transaction): ChartOfAccount
    {
        // Try to find income account based on category or use default
        if ($transaction->category) {
            // Map category to COA account
            $accountCode = $this->mapCategoryToAccountCode($transaction->category, 'income');
            $account = ChartOfAccount::where('code', $accountCode)->first();
            if ($account) {
                return $account;
            }
        }

        // Default income account
        return ChartOfAccount::where('code', '4110')->first()
            ?? ChartOfAccount::where('type', 'revenue')->first();
    }

    /**
     * Get appropriate expense account for transaction
     *
     * @param Transaction $transaction
     * @return ChartOfAccount
     */
    private function getExpenseAccount(Transaction $transaction): ChartOfAccount
    {
        // Try to find expense account based on category or use default
        if ($transaction->category) {
            $accountCode = $this->mapCategoryToAccountCode($transaction->category, 'expense');
            $account = ChartOfAccount::where('code', $accountCode)->first();
            if ($account) {
                return $account;
            }
        }

        // Default expense account
        return ChartOfAccount::where('code', '5220')->first()
            ?? ChartOfAccount::where('type', 'expense')->first();
    }

    /**
     * Map category to account code
     *
     * @param mixed $category
     * @param string $type
     * @return string
     */
    private function mapCategoryToAccountCode($category, string $type): string
    {
        // This is a simplified mapping - in production, you'd have a more comprehensive mapping table
        $mappings = [
            'income' => [
                'Penjualan' => '4110',
                'Jasa' => '4120',
                'Sewa' => '4130',
            ],
            'expense' => [
                'Gaji' => '5210',
                'Operasional' => '5220',
                'Sewa' => '5230',
                'Transportasi' => '5240',
                'Pembelian' => '5250',
                'Bank' => '5260',
            ],
        ];

        $categoryName = is_object($category) ? $category->name : $category;

        return $mappings[$type][$categoryName] ?? ($type === 'income' ? '4110' : '5220');
    }

    /**
     * Get transaction description
     *
     * @param Transaction $transaction
     * @return string
     */
    private function getTransactionDescription(Transaction $transaction): string
    {
        $type = $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran';
        return $type . ': ' . ($transaction->description ?? 'Tanpa deskripsi');
    }

    /**
     * Reverse a journal entry
     *
     * @param JournalEntry $journalEntry
     * @param string $reason
     * @return JournalEntry
     * @throws Exception
     */
    public function reverseJournalEntry(JournalEntry $journalEntry, string $reason = 'Reversal'): JournalEntry
    {
        DB::beginTransaction();

        try {
            // Create reversing journal entry
            $reversingEntry = JournalEntry::create([
                'date' => now(),
                'reference' => 'REV-' . $journalEntry->id,
                'description' => $reason . ' - Original: ' . $journalEntry->reference,
                'status' => 'posted',
                'posted_at' => now(),
                'source_type' => $journalEntry->source_type,
                'source_id' => $journalEntry->source_id,
                'branch_id' => $journalEntry->branch_id,
                'created_by' => auth()->id() ?? 1, // Default to user ID 1 if not authenticated
            ]);

            // Create reversing lines (swap debit/credit)
            foreach ($journalEntry->lines as $line) {
                JournalLine::create([
                    'journal_entry_id' => $reversingEntry->id,
                    'chart_of_account_id' => $line->chart_of_account_id,
                    'debit' => $line->credit,
                    'credit' => $line->debit,
                    'description' => 'Reversal: ' . $line->description,
                ]);
            }

            // Mark original as reversed
            $journalEntry->update([
                'status' => 'reversed',
                'reversed_at' => now(),
                'reversal_reference' => $reversingEntry->reference,
            ]);

            DB::commit();
            return $reversingEntry;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to reverse journal entry: ' . $e->getMessage());
        }
    }
}