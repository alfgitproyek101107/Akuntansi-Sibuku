<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cacheKey = "dashboard_{$user->id}_" . (session('active_branch') ?? 'all');

        // Cache dashboard data for 10 minutes to improve performance
        $dashboardData = Cache::remember($cacheKey, 600, function () use ($user) {
            // Financial Summary
            $totalBalance = $user->accounts()->sum('balance');
            $totalAccounts = $user->accounts()->count();

            // Current Month Data
            $currentMonth = now()->format('Y-m');
            $monthlyIncome = $user->transactions()
                ->where('type', 'income')
                ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
                ->sum('amount');

            $monthlyExpense = $user->transactions()
                ->where('type', 'expense')
                ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
                ->sum('amount');

            $monthlyTransfer = $user->transactions()
                ->where('type', 'transfer')
                ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
                ->sum('amount');

            // Previous Month Comparison
            $previousMonth = now()->subMonth()->format('Y-m');
            $previousIncome = $user->transactions()
                ->where('type', 'income')
                ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$previousMonth])
                ->sum('amount');

            $previousExpense = $user->transactions()
                ->where('type', 'expense')
                ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$previousMonth])
                ->sum('amount');

            // Calculate percentage changes
            $incomeChange = $previousIncome > 0 ? (($monthlyIncome - $previousIncome) / $previousIncome) * 100 : 0;
            $expenseChange = $previousExpense > 0 ? (($monthlyExpense - $previousExpense) / $previousExpense) * 100 : 0;

            // Account Balances (limit to essential data)
            $accounts = $user->accounts()->select('id', 'name', 'balance', 'type')->get();

            // Recent Transactions (last 6 for better display)
            $recentTransactions = $user->transactions()
                ->with(['account:id,name', 'category:id,name', 'transfer.fromAccount:id,name', 'transfer.toAccount:id,name'])
                ->select('id', 'account_id', 'category_id', 'type', 'amount', 'description', 'date', 'transfer_id')
                ->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();

            // Top Categories This Month (optimized)
            $topIncomeCategories = $user->transactions()
                ->where('type', 'income')
                ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
                ->whereNotNull('category_id')
                ->with('category:id,name')
                ->selectRaw('category_id, SUM(amount) as total')
                ->groupBy('category_id')
                ->orderBy('total', 'desc')
                ->limit(3)
                ->get();

            $topExpenseCategories = $user->transactions()
                ->where('type', 'expense')
                ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
                ->whereNotNull('category_id')
                ->with('category:id,name')
                ->selectRaw('category_id, SUM(amount) as total')
                ->groupBy('category_id')
                ->orderBy('total', 'desc')
                ->limit(3)
                ->get();

            return compact(
                'totalBalance',
                'totalAccounts',
                'monthlyIncome',
                'monthlyExpense',
                'monthlyTransfer',
                'incomeChange',
                'expenseChange',
                'accounts',
                'recentTransactions',
                'topIncomeCategories',
                'topExpenseCategories'
            );
        });

        // Cache cash flow data separately (longer cache time)
        $cashFlowCacheKey = "cash_flow_{$user->id}_" . (session('active_branch') ?? 'all');
        $cashFlowData = Cache::remember($cashFlowCacheKey, 1800, function () use ($user) { // 30 minutes cache
            $data = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $monthStr = $month->format('Y-m');

                $income = $user->transactions()
                    ->where('type', 'income')
                    ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$monthStr])
                    ->sum('amount');

                $expense = $user->transactions()
                    ->where('type', 'expense')
                    ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$monthStr])
                    ->sum('amount');

                $data[] = [
                    'month' => $month->format('M Y'),
                    'income' => $income,
                    'expense' => $expense,
                    'net' => $income - $expense
                ];
            }
            return $data;
        });

        // Extract variables from cached data
        extract($dashboardData);

        return view('dashboard.index', compact(
            'totalBalance',
            'totalAccounts',
            'monthlyIncome',
            'monthlyExpense',
            'monthlyTransfer',
            'incomeChange',
            'expenseChange',
            'accounts',
            'recentTransactions',
            'cashFlowData',
            'topIncomeCategories',
            'topExpenseCategories'
        ));
    }

    /**
     * Clear dashboard cache when data changes
     */
    public static function clearDashboardCache($userId = null)
    {
        $user = $userId ? \App\Models\User::find($userId) : Auth::user();
        if ($user) {
            Cache::forget("dashboard_{$user->id}_all");
            Cache::forget("dashboard_{$user->id}_" . (session('active_branch') ?? 'all'));
            Cache::forget("cash_flow_{$user->id}_all");
            Cache::forget("cash_flow_{$user->id}_" . (session('active_branch') ?? 'all'));
        }
    }

    public function data()
    {
        $user = Auth::user();
        $cacheKey = "dashboard_data_{$user->id}_" . (session('active_branch') ?? 'all');

        return Cache::remember($cacheKey, 300, function () use ($user) { // Cache for 5 minutes
            // Financial Summary
            $totalBalance = $user->accounts()->sum('balance');
            $totalAccounts = $user->accounts()->count();

            // Current Month Data
            $currentMonth = now()->format('Y-m');
            $monthlyIncome = $user->transactions()
                ->where('type', 'income')
                ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
                ->sum('amount');

            $monthlyExpense = $user->transactions()
                ->where('type', 'expense')
                ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
                ->sum('amount');

            // Previous Month Comparison
            $previousMonth = now()->subMonth()->format('Y-m');
            $previousIncome = $user->transactions()
                ->where('type', 'income')
                ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$previousMonth])
                ->sum('amount');

            $previousExpense = $user->transactions()
                ->where('type', 'expense')
                ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$previousMonth])
                ->sum('amount');

            // Calculate percentage changes
            $incomeChange = $previousIncome > 0 ? (($monthlyIncome - $previousIncome) / $previousIncome) * 100 : 0;
            $expenseChange = $previousExpense > 0 ? (($monthlyExpense - $previousExpense) / $previousExpense) * 100 : 0;

            // Account Balances
            $accounts = $user->accounts()->select('id', 'name', 'balance', 'type')->get();

            // Recent Transactions (last 5)
            $recentTransactions = $user->transactions()
                ->with(['account:id,name', 'category:id,name', 'transfer.fromAccount:id,name', 'transfer.toAccount:id,name'])
                ->select('id', 'account_id', 'category_id', 'type', 'amount', 'description', 'date', 'transfer_id')
                ->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Cash Flow Data for Chart (last 6 months) - Cache this separately
            $cashFlowCacheKey = "cash_flow_{$user->id}_" . (session('active_branch') ?? 'all');
            $cashFlowData = Cache::remember($cashFlowCacheKey, 3600, function () use ($user) { // Cache for 1 hour
                $data = [];
                for ($i = 5; $i >= 0; $i--) {
                    $month = now()->subMonths($i);
                    $monthStr = $month->format('Y-m');

                    $income = $user->transactions()
                        ->where('type', 'income')
                        ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$monthStr])
                        ->sum('amount');

                    $expense = $user->transactions()
                        ->where('type', 'expense')
                        ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$monthStr])
                        ->sum('amount');

                    $data[] = [
                        'month' => $month->format('M Y'),
                        'income' => $income,
                        'expense' => $expense,
                        'net' => $income - $expense
                    ];
                }
                return $data;
            });

            // Top Categories This Month
            $topExpenseCategories = $user->transactions()
                ->where('type', 'expense')
                ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
                ->whereNotNull('category_id')
                ->with('category:id,name')
                ->selectRaw('category_id, SUM(amount) as total')
                ->groupBy('category_id')
                ->orderBy('total', 'desc')
                ->limit(3)
                ->get()
                ->map(function($item) {
                    return [
                        'category' => $item->category,
                        'total' => $item->total
                    ];
                });

            return response()->json([
                'kpis' => [
                    'total_balance' => $totalBalance,
                    'total_accounts' => $totalAccounts,
                    'monthly_income' => $monthlyIncome,
                    'monthly_expense' => $monthlyExpense,
                    'income_change' => round($incomeChange, 1),
                    'expense_change' => round($expenseChange, 1),
                ],
                'series' => $cashFlowData,
                'accounts' => $accounts,
                'recent_transactions' => $recentTransactions,
                'top_categories' => $topExpenseCategories
            ]);
        });
    }
}