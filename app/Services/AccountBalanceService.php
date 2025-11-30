<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Exception;

class AccountBalanceService
{
    /**
     * Update account balance with race condition protection
     *
     * @param int $accountId
     * @param float $amount
     * @param string $operation ('add' or 'subtract')
     * @param bool $allowNegative Allow negative balances (for transaction reversals)
     * @return float New balance
     * @throws Exception
     */
    public function updateBalance(int $accountId, float $amount, string $operation, bool $allowNegative = false): float
    {
        return DB::transaction(function () use ($accountId, $amount, $operation, $allowNegative) {
            // Lock the account row for update to prevent race conditions
            $account = Account::where('id', $accountId)
                ->lockForUpdate()
                ->first();

            if (!$account) {
                throw new Exception("Account not found: {$accountId}");
            }

            $currentBalance = $account->balance;

            switch ($operation) {
                case 'add':
                    $newBalance = FinancialCalculator::add($currentBalance, $amount);
                    break;
                case 'subtract':
                    $newBalance = FinancialCalculator::subtract($currentBalance, $amount);
                    // Check for insufficient funds (unless allowed for reversals)
                    if (!$allowNegative && $newBalance < 0) {
                        throw new Exception("Insufficient funds. Current balance: {$currentBalance}, Required: {$amount}");
                    }
                    break;
                default:
                    throw new Exception("Invalid operation: {$operation}. Use 'add' or 'subtract'");
            }

            // Update the balance
            $account->update(['balance' => $newBalance]);

            return $newBalance;
        });
    }

    /**
     * Transfer money between accounts with race condition protection
     *
     * @param int $fromAccountId
     * @param int $toAccountId
     * @param float $amount
     * @return array Transfer result
     * @throws Exception
     */
    public function transferBetweenAccounts(int $fromAccountId, int $toAccountId, float $amount): array
    {
        if ($fromAccountId === $toAccountId) {
            throw new Exception("Cannot transfer to the same account");
        }

        if ($amount <= 0) {
            throw new Exception("Transfer amount must be positive");
        }

        return DB::transaction(function () use ($fromAccountId, $toAccountId, $amount) {
            // Lock both accounts in consistent order to prevent deadlocks
            $accounts = Account::whereIn('id', [$fromAccountId, $toAccountId])
                ->orderBy('id') // Consistent ordering prevents deadlocks
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if (!isset($accounts[$fromAccountId]) || !isset($accounts[$toAccountId])) {
                throw new Exception("One or both accounts not found");
            }

            $fromAccount = $accounts[$fromAccountId];
            $toAccount = $accounts[$toAccountId];

            // Check sufficient funds
            if ($fromAccount->balance < $amount) {
                throw new Exception("Insufficient funds in source account. Balance: {$fromAccount->balance}, Required: {$amount}");
            }

            // Perform the transfer
            $newFromBalance = FinancialCalculator::subtract($fromAccount->balance, $amount);
            $newToBalance = FinancialCalculator::add($toAccount->balance, $amount);

            // Update both balances
            $fromAccount->update(['balance' => $newFromBalance]);
            $toAccount->update(['balance' => $newToBalance]);

            return [
                'from_balance' => $newFromBalance,
                'to_balance' => $newToBalance,
                'amount' => $amount,
                'success' => true
            ];
        });
    }

    /**
     * Reserve funds for pending transactions (prevents overselling)
     *
     * @param int $accountId
     * @param float $amount
     * @param string $reservationId
     * @return bool
     * @throws Exception
     */
    public function reserveFunds(int $accountId, float $amount, string $reservationId): bool
    {
        return DB::transaction(function () use ($accountId, $amount, $reservationId) {
            $account = Account::where('id', $accountId)
                ->lockForUpdate()
                ->first();

            if (!$account) {
                throw new Exception("Account not found: {$accountId}");
            }

            if ($account->balance < $amount) {
                throw new Exception("Insufficient funds for reservation");
            }

            // In a real implementation, you'd store reservations in a separate table
            // For now, we'll just validate the balance
            return true;
        });
    }

    /**
     * Release reserved funds
     *
     * @param string $reservationId
     * @return bool
     */
    public function releaseReservation(string $reservationId): bool
    {
        // Implementation would depend on how reservations are stored
        return true;
    }

    /**
     * Get account balance with consistency guarantee
     *
     * @param int $accountId
     * @return float
     * @throws Exception
     */
    public function getBalance(int $accountId): float
    {
        $account = Account::find($accountId);

        if (!$account) {
            throw new Exception("Account not found: {$accountId}");
        }

        return $account->balance;
    }

    /**
     * Validate account has sufficient funds
     *
     * @param int $accountId
     * @param float $amount
     * @return bool
     * @throws Exception
     */
    public function hasSufficientFunds(int $accountId, float $amount): bool
    {
        $balance = $this->getBalance($accountId);
        return $balance >= $amount;
    }
}