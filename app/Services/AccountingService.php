<?php

namespace App\Services;

use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\ChartOfAccount;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Exception;

class AccountingService
{
    /**
     * Create a journal entry with double-entry accounting principles
     *
     * @param array $data Journal entry data
     * @return JournalEntry
     * @throws Exception
     */
    public function createJournalEntry(array $data): JournalEntry
    {
        return DB::transaction(function () use ($data) {
            // Validate journal entry data
            $this->validateJournalEntryData($data);

            // Create journal entry
            $journal = JournalEntry::create([
                'date' => $data['date'],
                'reference' => $data['reference'] ?? $this->generateReference(),
                'description' => $data['description'],
                'total_debit' => collect($data['lines'])->sum('debit'),
                'total_credit' => collect($data['lines'])->sum('credit'),
                'created_by' => auth()->id(),
                'status' => $data['status'] ?? 'draft',
            ]);

            // Validate: total_debit == total_credit
            if ($journal->total_debit !== $journal->total_credit) {
                throw new Exception('Journal entry is not balanced. Debit: ' . $journal->total_debit . ', Credit: ' . $journal->total_credit);
            }

            // Create journal lines
            foreach ($data['lines'] as $lineData) {
                $this->createJournalLine($journal, $lineData);
            }

            // Update account balances if posted
            if ($journal->status === 'posted') {
                $this->updateAccountBalances($journal);
            }

            return $journal;
        });
    }

    /**
     * Create journal entry for income transaction
     *
     * @param Transaction $transaction
     * @return JournalEntry
     */
    public function createIncomeJournalEntry(Transaction $transaction): JournalEntry
    {
        // Get revenue account from category mapping
        $revenueAccountId = $this->getRevenueAccountId($transaction->category_id);

        $journalData = [
            'date' => $transaction->date,
            'reference' => 'TXN-' . $transaction->id,
            'description' => $transaction->description ?: 'Income transaction',
            'status' => 'posted',
            'lines' => [
                // Debit: Cash/Bank Account
                [
                    'chart_of_account_id' => $transaction->account_id,
                    'debit' => $transaction->amount,
                    'description' => 'Cash receipt'
                ],
                // Credit: Revenue Account
                [
                    'chart_of_account_id' => $revenueAccountId,
                    'credit' => $transaction->amount,
                    'description' => 'Revenue from ' . ($transaction->description ?: 'transaction')
                ]
            ]
        ];

        return $this->createJournalEntry($journalData);
    }

    /**
     * Create journal entry for expense transaction
     *
     * @param Transaction $transaction
     * @return JournalEntry
     */
    public function createExpenseJournalEntry(Transaction $transaction): JournalEntry
    {
        // Get expense account from category mapping
        $expenseAccountId = $this->getExpenseAccountId($transaction->category_id);

        $journalData = [
            'date' => $transaction->date,
            'reference' => 'TXN-' . $transaction->id,
            'description' => $transaction->description ?: 'Expense transaction',
            'status' => 'posted',
            'lines' => [
                // Debit: Expense Account
                [
                    'chart_of_account_id' => $expenseAccountId,
                    'debit' => $transaction->amount,
                    'description' => 'Expense incurred'
                ],
                // Credit: Cash/Bank Account
                [
                    'chart_of_account_id' => $transaction->account_id,
                    'credit' => $transaction->amount,
                    'description' => 'Cash payment'
                ]
            ]
        ];

        return $this->createJournalEntry($journalData);
    }

    /**
     * Create journal entry for transfer transaction
     *
     * @param Transaction $fromTransaction
     * @param Transaction $toTransaction
     * @return JournalEntry
     */
    public function createTransferJournalEntry(Transaction $fromTransaction, Transaction $toTransaction): JournalEntry
    {
        $journalData = [
            'date' => $fromTransaction->date,
            'reference' => 'TXFR-' . $fromTransaction->transfer_id,
            'description' => $fromTransaction->description ?: 'Transfer transaction',
            'status' => 'posted',
            'lines' => [
                // Debit: To Account
                [
                    'chart_of_account_id' => $toTransaction->account_id,
                    'debit' => $fromTransaction->amount,
                    'description' => 'Transfer in'
                ],
                // Credit: From Account
                [
                    'chart_of_account_id' => $fromTransaction->account_id,
                    'credit' => $fromTransaction->amount,
                    'description' => 'Transfer out'
                ]
            ]
        ];

        return $this->createJournalEntry($journalData);
    }

    /**
     * Post a draft journal entry
     *
     * @param JournalEntry $journal
     * @return bool
     * @throws Exception
     */
    public function postJournalEntry(JournalEntry $journal): bool
    {
        if ($journal->status !== 'draft') {
            throw new Exception('Only draft journal entries can be posted');
        }

        if (!$journal->isBalanced()) {
            throw new Exception('Cannot post unbalanced journal entry');
        }

        DB::transaction(function () use ($journal) {
            $journal->update([
                'status' => 'posted',
                'posted_at' => now(),
            ]);

            $this->updateAccountBalances($journal);
        });

        return true;
    }

    /**
     * Void a posted journal entry
     *
     * @param JournalEntry $journal
     * @return bool
     * @throws Exception
     */
    public function voidJournalEntry(JournalEntry $journal): bool
    {
        if ($journal->status !== 'posted') {
            throw new Exception('Only posted journal entries can be voided');
        }

        DB::transaction(function () use ($journal) {
            // Reverse account balances
            foreach ($journal->journalLines as $line) {
                $account = $line->account;
                $newBalance = $account->balance - $line->debit + $line->credit;
                $account->update(['balance' => $newBalance]);
            }

            $journal->update(['status' => 'voided']);
        });

        return true;
    }

    /**
     * Get trial balance
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getTrialBalance(string $startDate, string $endDate): array
    {
        $accounts = ChartOfAccount::active()
            ->with(['journalLines' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('journalEntry', function ($q) use ($startDate, $endDate) {
                    $q->posted()
                      ->whereBetween('date', [$startDate, $endDate]);
                });
            }])
            ->get();

        $trialBalance = [];

        foreach ($accounts as $account) {
            $debit = $account->journalLines->sum('debit');
            $credit = $account->journalLines->sum('credit');

            if ($debit > 0 || $credit > 0) {
                $trialBalance[] = [
                    'account_code' => $account->code,
                    'account_name' => $account->name,
                    'debit' => $debit,
                    'credit' => $credit,
                    'balance' => $debit - $credit,
                ];
            }
        }

        return $trialBalance;
    }

    /**
     * Validate journal entry data
     *
     * @param array $data
     * @throws Exception
     */
    private function validateJournalEntryData(array $data): void
    {
        if (!isset($data['lines']) || !is_array($data['lines']) || empty($data['lines'])) {
            throw new Exception('Journal entry must have at least one line');
        }

        foreach ($data['lines'] as $line) {
            if (!isset($line['chart_of_account_id'])) {
                throw new Exception('Each journal line must have a chart_of_account_id');
            }

            $debit = $line['debit'] ?? 0;
            $credit = $line['credit'] ?? 0;

            if (($debit > 0 && $credit > 0) || ($debit == 0 && $credit == 0)) {
                throw new Exception('Each journal line must have either debit or credit, but not both or neither');
            }
        }
    }

    /**
     * Create a journal line
     *
     * @param JournalEntry $journal
     * @param array $lineData
     * @return JournalLine
     */
    private function createJournalLine(JournalEntry $journal, array $lineData): JournalLine
    {
        static $lineNumber = 1;

        return JournalLine::create([
            'journal_entry_id' => $journal->id,
            'chart_of_account_id' => $lineData['chart_of_account_id'],
            'debit' => $lineData['debit'] ?? 0,
            'credit' => $lineData['credit'] ?? 0,
            'description' => $lineData['description'] ?? null,
            'line_number' => $lineNumber++,
        ]);
    }

    /**
     * Update account balances after posting
     *
     * @param JournalEntry $journal
     */
    private function updateAccountBalances(JournalEntry $journal): void
    {
        foreach ($journal->journalLines as $line) {
            $account = $line->account;
            $newBalance = $account->balance + $line->debit - $line->credit;
            $account->update(['balance' => $newBalance]);
        }
    }

    /**
     * Generate unique reference number
     *
     * @return string
     */
    private function generateReference(): string
    {
        do {
            $reference = 'JE-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (JournalEntry::where('reference', $reference)->exists());

        return $reference;
    }

    /**
     * Get revenue account ID from category
     *
     * @param int $categoryId
     * @return int
     */
    private function getRevenueAccountId(int $categoryId): int
    {
        // This should be configurable, but for now return a default revenue account
        // In a real implementation, you'd have a mapping table
        return ChartOfAccount::where('type', 'revenue')
            ->where('category', 'sales_revenue')
            ->first()->id ?? 1;
    }

    /**
     * Get expense account ID from category
     *
     * @param int $categoryId
     * @return int
     */
    private function getExpenseAccountId(int $categoryId): int
    {
        // This should be configurable, but for now return a default expense account
        // In a real implementation, you'd have a mapping table
        return ChartOfAccount::where('type', 'expense')
            ->where('category', 'operating_expense')
            ->first()->id ?? 2;
    }
}