<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\JournalLine;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialReportingService
{
    /**
     * Generate Balance Sheet report
     *
     * @param Carbon $asOfDate
     * @param int|null $branchId
     * @return array
     */
    public function generateBalanceSheet(Carbon $asOfDate, ?int $branchId = null): array
    {
        // Get all asset accounts with balances
        $assets = $this->getAccountBalancesByType('asset', $asOfDate, $branchId);

        // Get all liability accounts with balances
        $liabilities = $this->getAccountBalancesByType('liability', $asOfDate, $branchId);

        // Get all equity accounts with balances
        $equity = $this->getAccountBalancesByType('equity', $asOfDate, $branchId);

        // Calculate totals
        $totalAssets = collect($assets)->sum('balance');
        $totalLiabilities = collect($liabilities)->sum('balance');
        $totalEquity = collect($equity)->sum('balance');

        // Retained earnings calculation (simplified)
        $retainedEarnings = $this->calculateRetainedEarnings($asOfDate, $branchId);

        return [
            'as_of_date' => $asOfDate->format('Y-m-d'),
            'branch_id' => $branchId,
            'assets' => [
                'current_assets' => $this->filterByCategory($assets, 'current_asset'),
                'fixed_assets' => $this->filterByCategory($assets, 'fixed_asset'),
                'total' => $totalAssets,
            ],
            'liabilities' => [
                'current_liabilities' => $this->filterByCategory($liabilities, 'current_liability'),
                'long_term_liabilities' => $this->filterByCategory($liabilities, 'long_term_liability'),
                'total' => $totalLiabilities,
            ],
            'equity' => [
                'capital' => $this->filterByCategory($equity, 'capital'),
                'retained_earnings' => $retainedEarnings,
                'total' => $totalEquity + $retainedEarnings,
            ],
            'totals' => [
                'total_assets' => $totalAssets,
                'total_liabilities_equity' => $totalLiabilities + $totalEquity + $retainedEarnings,
                'balance_check' => $totalAssets - ($totalLiabilities + $totalEquity + $retainedEarnings),
            ],
        ];
    }

    /**
     * Generate Income Statement (Profit & Loss)
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param int|null $branchId
     * @return array
     */
    public function generateIncomeStatement(Carbon $startDate, Carbon $endDate, ?int $branchId = null): array
    {
        // Get revenue accounts with balances for the period
        $revenues = $this->getAccountBalancesByTypeForPeriod('income', $startDate, $endDate, $branchId);

        // Get expense accounts with balances for the period
        $expenses = $this->getAccountBalancesByTypeForPeriod('expense', $startDate, $endDate, $branchId);

        $totalRevenue = collect($revenues)->sum('balance');
        $totalExpenses = collect($expenses)->sum('balance');
        $netIncome = $totalRevenue - $totalExpenses;

        return [
            'period_start' => $startDate->format('Y-m-d'),
            'period_end' => $endDate->format('Y-m-d'),
            'branch_id' => $branchId,
            'revenues' => [
                'sales_revenue' => $this->filterByCategory($revenues, 'sales_revenue'),
                'other_income' => $this->filterByCategory($revenues, 'other_income'),
                'total' => $totalRevenue,
            ],
            'expenses' => [
                'cost_of_goods_sold' => $this->filterByCategory($expenses, 'cost_of_goods_sold'),
                'operating_expenses' => $this->filterByCategory($expenses, 'operating_expense'),
                'other_expenses' => $this->filterByCategory($expenses, 'other_expense'),
                'total' => $totalExpenses,
            ],
            'net_income' => $netIncome,
        ];
    }

    /**
     * Generate Trial Balance
     *
     * @param Carbon $asOfDate
     * @param int|null $branchId
     * @return array
     */
    public function generateTrialBalance(Carbon $asOfDate, ?int $branchId = null): array
    {
        $query = ChartOfAccount::with(['journalLines' => function ($query) use ($asOfDate) {
            $query->whereHas('journalEntry', function ($q) use ($asOfDate) {
                $q->where('date', '<=', $asOfDate)
                  ->where('status', 'posted');
            });
        }]);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $accounts = $query->get();

        $trialBalance = [];
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($accounts as $account) {
            $balance = $this->calculateAccountBalance($account, $asOfDate);

            if (abs($balance) > 0.01) { // Only include accounts with balances
                $debit = $balance > 0 ? abs($balance) : 0;
                $credit = $balance < 0 ? abs($balance) : 0;

                $trialBalance[] = [
                    'code' => $account->code,
                    'name' => $account->name,
                    'type' => $account->type,
                    'debit' => $debit,
                    'credit' => $credit,
                ];

                $totalDebit += $debit;
                $totalCredit += $credit;
            }
        }

        return [
            'as_of_date' => $asOfDate->format('Y-m-d'),
            'branch_id' => $branchId,
            'accounts' => $trialBalance,
            'totals' => [
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'difference' => $totalDebit - $totalCredit,
            ],
        ];
    }

    /**
     * Get account balances by type
     *
     * @param string $type
     * @param Carbon $asOfDate
     * @param int|null $branchId
     * @return array
     */
    private function getAccountBalancesByType(string $type, Carbon $asOfDate, ?int $branchId = null): array
    {
        $query = ChartOfAccount::where('type', $type)
            ->where('is_active', true);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $accounts = $query->get();
        $balances = [];

        foreach ($accounts as $account) {
            $balance = $this->calculateAccountBalance($account, $asOfDate);

            if (abs($balance) > 0.01) {
                $balances[] = [
                    'id' => $account->id,
                    'code' => $account->code,
                    'name' => $account->name,
                    'category' => $account->category,
                    'balance' => $balance,
                ];
            }
        }

        return $balances;
    }

    /**
     * Get account balances by type for a period
     *
     * @param string $type
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param int|null $branchId
     * @return array
     */
    private function getAccountBalancesByTypeForPeriod(string $type, Carbon $startDate, Carbon $endDate, ?int $branchId = null): array
    {
        $query = ChartOfAccount::where('type', $type)
            ->where('is_active', true);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $accounts = $query->get();
        $balances = [];

        foreach ($accounts as $account) {
            $balance = $this->calculateAccountBalanceForPeriod($account, $startDate, $endDate);

            if (abs($balance) > 0.01) {
                $balances[] = [
                    'id' => $account->id,
                    'code' => $account->code,
                    'name' => $account->name,
                    'category' => $account->category,
                    'balance' => $balance,
                ];
            }
        }

        return $balances;
    }

    /**
     * Calculate account balance as of date
     *
     * @param ChartOfAccount $account
     * @param Carbon $asOfDate
     * @return float
     */
    private function calculateAccountBalance(ChartOfAccount $account, Carbon $asOfDate): float
    {
        $debit = JournalLine::where('chart_of_account_id', $account->id)
            ->whereHas('journalEntry', function ($query) use ($asOfDate) {
                $query->where('date', '<=', $asOfDate)
                      ->where('status', 'posted');
            })
            ->sum('debit');

        $credit = JournalLine::where('chart_of_account_id', $account->id)
            ->whereHas('journalEntry', function ($query) use ($asOfDate) {
                $query->where('date', '<=', $asOfDate)
                      ->where('status', 'posted');
            })
            ->sum('credit');

        // For asset and expense accounts, debit increases balance
        // For liability, equity, and revenue accounts, credit increases balance
        if (in_array($account->type, ['asset', 'expense'])) {
            return $debit - $credit;
        } else {
            return $credit - $debit;
        }
    }

    /**
     * Calculate account balance for a period
     *
     * @param ChartOfAccount $account
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return float
     */
    private function calculateAccountBalanceForPeriod(ChartOfAccount $account, Carbon $startDate, Carbon $endDate): float
    {
        $debit = JournalLine::where('chart_of_account_id', $account->id)
            ->whereHas('journalEntry', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate])
                      ->where('status', 'posted');
            })
            ->sum('debit');

        $credit = JournalLine::where('chart_of_account_id', $account->id)
            ->whereHas('journalEntry', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate])
                      ->where('status', 'posted');
            })
            ->sum('credit');

        // For revenue accounts, credit increases balance
        // For expense accounts, debit increases balance
        if ($account->type === 'income') {
            return $credit - $debit;
        } elseif ($account->type === 'expense') {
            return $debit - $credit;
        }

        return 0;
    }

    /**
     * Filter accounts by category
     *
     * @param array $accounts
     * @param string $category
     * @return array
     */
    private function filterByCategory(array $accounts, string $category): array
    {
        return array_filter($accounts, function ($account) use ($category) {
            return $account['category'] === $category;
        });
    }

    /**
     * Calculate retained earnings (simplified)
     *
     * @param Carbon $asOfDate
     * @param int|null $branchId
     * @return float
     */
    private function calculateRetainedEarnings(Carbon $asOfDate, ?int $branchId = null): float
    {
        // This is a simplified calculation
        // In a real system, you'd calculate this based on historical income statements
        $startOfYear = Carbon::create($asOfDate->year, 1, 1);

        $revenues = $this->getAccountBalancesByTypeForPeriod('income', $startOfYear, $asOfDate, $branchId);
        $expenses = $this->getAccountBalancesByTypeForPeriod('expense', $startOfYear, $asOfDate, $branchId);

        $totalRevenue = collect($revenues)->sum('balance');
        $totalExpenses = collect($expenses)->sum('balance');

        return $totalRevenue - $totalExpenses;
    }
}