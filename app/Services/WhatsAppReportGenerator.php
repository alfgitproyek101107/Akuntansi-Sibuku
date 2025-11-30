<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WhatsAppReportGenerator
{
    /**
     * Generate daily report
     */
    public function generateDaily(?int $branchId = null, ?Carbon $date = null): string
    {
        $date = $date ?: now();
        $startDate = $date->copy()->startOfDay();
        $endDate = $date->copy()->endOfDay();

        $query = Transaction::whereBetween('date', [$startDate, $endDate]);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $transactions = $query->with(['account', 'category'])->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $netIncome = $totalIncome - $totalExpense;

        // Get account balances
        $accountBalances = $this->getAccountBalances($branchId);

        // Get recent transactions (last 5)
        $recentTransactions = $transactions->sortByDesc('date')->take(5);

        $message = "📊 LAPORAN KEUANGAN - {$date->format('d M Y')}\n\n";

        $message .= "💰 Pemasukan: Rp " . number_format($totalIncome, 0, ',', '.') . "\n";
        $message .= "💸 Pengeluaran: Rp " . number_format($totalExpense, 0, ',', '.') . "\n";
        $message .= "📈 Laba Bersih: Rp " . number_format($netIncome, 0, ',', '.') . "\n\n";

        if ($accountBalances) {
            $message .= "🏦 Saldo Rekening:\n";
            foreach ($accountBalances as $account) {
                $message .= "• {$account['name']}: Rp " . number_format($account['balance'], 0, ',', '.') . "\n";
            }
            $message .= "\n";
        }

        $message .= "📝 Transaksi Terbaru:\n";
        foreach ($recentTransactions as $transaction) {
            $type = $transaction->type === 'income' ? '➕' : '➖';
            $message .= "{$type} " . ($transaction->description ?: 'Transaksi') . " - Rp " . number_format($transaction->amount, 0, ',', '.') . "\n";
        }

        $message .= "\n📅 Total Transaksi: {$transactions->count()}\n";
        $message .= "🏢 Sistem Sibuku";

        return $message;
    }

    /**
     * Generate weekly report
     */
    public function generateWeekly(?int $branchId = null, ?Carbon $date = null): string
    {
        $date = $date ?: now();
        $startDate = $date->copy()->startOfWeek();
        $endDate = $date->copy()->endOfWeek();

        $query = Transaction::whereBetween('date', [$startDate, $endDate]);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $transactions = $query->with(['account', 'category'])->get();

        // Group by day
        $dailyData = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startDate->copy()->addDays($i);
            $dayTransactions = $transactions->whereBetween('date', [$day->startOfDay(), $day->endOfDay()]);

            $dailyData[] = [
                'date' => $day->format('d/m'),
                'income' => $dayTransactions->where('type', 'income')->sum('amount'),
                'expense' => $dayTransactions->where('type', 'expense')->sum('amount'),
            ];
        }

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $netIncome = $totalIncome - $totalExpense;

        // Get top products/categories
        $topCategories = $this->getTopCategories($transactions, 3);

        $message = "📊 LAPORAN MINGGUAN - {$startDate->format('d M')} - {$endDate->format('d M Y')}\n\n";

        $message .= "💰 Total Pemasukan: Rp " . number_format($totalIncome, 0, ',', '.') . "\n";
        $message .= "💸 Total Pengeluaran: Rp " . number_format($totalExpense, 0, ',', '.') . "\n";
        $message .= "📈 Laba Bersih: Rp " . number_format($netIncome, 0, ',', '.') . "\n\n";

        $message .= "📅 Rekap Harian:\n";
        foreach ($dailyData as $day) {
            $net = $day['income'] - $day['expense'];
            $emoji = $net >= 0 ? '🟢' : '🔴';
            $message .= "{$emoji} {$day['date']}: Rp " . number_format($net, 0, ',', '.') . "\n";
        }
        $message .= "\n";

        if ($topCategories) {
            $message .= "📈 Kategori Teratas:\n";
            foreach ($topCategories as $category) {
                $message .= "• {$category['name']}: Rp " . number_format($category['amount'], 0, ',', '.') . "\n";
            }
            $message .= "\n";
        }

        $message .= "📊 Total Transaksi: {$transactions->count()}\n";
        $message .= "🏢 Sistem Sibuku";

        return $message;
    }

    /**
     * Generate monthly report
     */
    public function generateMonthly(?int $branchId = null, ?Carbon $date = null): string
    {
        $date = $date ?: now();
        $startDate = $date->copy()->startOfMonth();
        $endDate = $date->copy()->endOfMonth();

        $query = Transaction::whereBetween('date', [$startDate, $endDate]);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $transactions = $query->with(['account', 'category'])->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $netIncome = $totalIncome - $totalExpense;

        // Get category breakdown
        $incomeCategories = $this->getCategoryBreakdown($transactions->where('type', 'income'), 5);
        $expenseCategories = $this->getCategoryBreakdown($transactions->where('type', 'expense'), 5);

        // Get cash flow
        $cashFlow = $this->getCashFlow($startDate, $endDate, $branchId);

        $message = "📊 LAPORAN BULANAN - {$date->format('M Y')}\n\n";

        $message .= "💰 Total Pemasukan: Rp " . number_format($totalIncome, 0, ',', '.') . "\n";
        $message .= "💸 Total Pengeluaran: Rp " . number_format($totalExpense, 0, ',', '.') . "\n";
        $message .= "📈 Laba Bersih: Rp " . number_format($netIncome, 0, ',', '.') . "\n\n";

        if ($incomeCategories) {
            $message .= "💵 Pemasukan per Kategori:\n";
            foreach ($incomeCategories as $category) {
                $message .= "• {$category['name']}: Rp " . number_format($category['amount'], 0, ',', '.') . "\n";
            }
            $message .= "\n";
        }

        if ($expenseCategories) {
            $message .= "💸 Pengeluaran per Kategori:\n";
            foreach ($expenseCategories as $category) {
                $message .= "• {$category['name']}: Rp " . number_format($category['amount'], 0, ',', '.') . "\n";
            }
            $message .= "\n";
        }

        if ($cashFlow) {
            $message .= "💱 Arus Kas:\n";
            $message .= "• Saldo Awal: Rp " . number_format($cashFlow['opening_balance'], 0, ',', '.') . "\n";
            $message .= "• Saldo Akhir: Rp " . number_format($cashFlow['closing_balance'], 0, ',', '.') . "\n";
            $message .= "• Perubahan: Rp " . number_format($cashFlow['change'], 0, ',', '.') . "\n\n";
        }

        $message .= "📊 Total Transaksi: {$transactions->count()}\n";
        $message .= "🏢 Sistem Sibuku";

        return $message;
    }

    /**
     * Get account balances
     */
    private function getAccountBalances(?int $branchId = null): array
    {
        $query = Account::select('name')
            ->selectRaw('COALESCE(SUM(CASE WHEN type = "debit" THEN amount ELSE -amount END), 0) as balance')
            ->with('transactions')
            ->groupBy('id', 'name');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->get()->toArray();
    }

    /**
     * Get top categories by amount
     */
    private function getTopCategories($transactions, int $limit = 3): array
    {
        return $transactions->groupBy('category.name')
            ->map(function ($group, $categoryName) {
                return [
                    'name' => $categoryName ?: 'Uncategorized',
                    'amount' => $group->sum('amount')
                ];
            })
            ->sortByDesc('amount')
            ->take($limit)
            ->values()
            ->toArray();
    }

    /**
     * Get category breakdown
     */
    private function getCategoryBreakdown($transactions, int $limit = 5): array
    {
        return $transactions->groupBy('category.name')
            ->map(function ($group, $categoryName) {
                return [
                    'name' => $categoryName ?: 'Uncategorized',
                    'amount' => $group->sum('amount')
                ];
            })
            ->sortByDesc('amount')
            ->take($limit)
            ->values()
            ->toArray();
    }

    /**
     * Get cash flow data
     */
    private function getCashFlow(Carbon $startDate, Carbon $endDate, ?int $branchId = null): ?array
    {
        // Get opening balance (balance before start date)
        $openingQuery = Transaction::where('date', '<', $startDate);

        if ($branchId) {
            $openingQuery->where('branch_id', $branchId);
        }

        $openingBalance = $openingQuery->get()->reduce(function ($balance, $transaction) {
            return $balance + ($transaction->type === 'income' ? $transaction->amount : -$transaction->amount);
        }, 0);

        // Get closing balance (balance at end date)
        $closingQuery = Transaction::where('date', '<=', $endDate);

        if ($branchId) {
            $closingQuery->where('branch_id', $branchId);
        }

        $closingBalance = $closingQuery->get()->reduce(function ($balance, $transaction) {
            return $balance + ($transaction->type === 'income' ? $transaction->amount : -$transaction->amount);
        }, 0);

        return [
            'opening_balance' => $openingBalance,
            'closing_balance' => $closingBalance,
            'change' => $closingBalance - $openingBalance
        ];
    }
}
