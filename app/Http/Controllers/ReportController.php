<?php

namespace App\Http\Controllers;

use App\Services\FinancialReportingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Monthly income and expense for the last 12 months
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('Y-m');

            $income = $user->transactions()
                ->where('type', 'income')
                ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
                ->sum('amount');

            $expense = $user->transactions()
                ->where('type', 'expense')
                ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
                ->sum('amount');

            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'income' => $income,
                'expense' => $expense,
                'net' => $income - $expense,
            ];
        }

        // Category breakdown for current month
        $currentMonth = now()->format('Y-m');
        $categoryIncome = $user->transactions()
            ->where('type', 'income')
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
            ->whereNotNull('category_id')
            ->with('category')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category->name ?? 'Uncategorized',
                    'total' => $item->total,
                ];
            });

        $categoryExpense = $user->transactions()
            ->where('type', 'expense')
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
            ->whereNotNull('category_id')
            ->with('category')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category->name ?? 'Uncategorized',
                    'total' => $item->total,
                ];
            });

        return view('reports.index', compact('monthlyData', 'categoryIncome', 'categoryExpense'));
    }

    public function daily(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $user = Auth::user();

        $transactions = $user->transactions()
            ->where('date', $date)
            ->with(['account', 'category', 'transfer.fromAccount', 'transfer.toAccount'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $totalTransfer = $transactions->where('type', 'transfer')->sum('amount');

        $netCashFlow = $totalIncome - $totalExpense;

        return view('reports.daily', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'totalTransfer',
            'netCashFlow',
            'date'
        ));
    }

    public function weekly(Request $request)
    {
        $week = $request->get('week', now()->format('Y-\WW'));
        $user = Auth::user();

        // Parse week format (YYYY-WW)
        [$year, $weekNum] = explode('-W', $week);
        $startOfWeek = (new \DateTime())->setISODate($year, $weekNum, 1)->format('Y-m-d');
        $endOfWeek = (new \DateTime())->setISODate($year, $weekNum, 7)->format('Y-m-d');

        $transactions = $user->transactions()
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->with(['account', 'category', 'transfer.fromAccount', 'transfer.toAccount'])
            ->orderBy('date', 'desc')
            ->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $totalTransfer = $transactions->where('type', 'transfer')->sum('amount');

        $netCashFlow = $totalIncome - $totalExpense;

        // Group by day
        $dailyBreakdown = $transactions->groupBy(function($transaction) {
            return $transaction->date->format('Y-m-d');
        })->map(function($dayTransactions) {
            return [
                'date' => $dayTransactions->first()->date,
                'income' => $dayTransactions->where('type', 'income')->sum('amount'),
                'expense' => $dayTransactions->where('type', 'expense')->sum('amount'),
                'transfer' => $dayTransactions->where('type', 'transfer')->sum('amount'),
                'net' => $dayTransactions->where('type', 'income')->sum('amount') -
                        $dayTransactions->where('type', 'expense')->sum('amount'),
                'count' => $dayTransactions->count()
            ];
        });

        return view('reports.weekly', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'totalTransfer',
            'netCashFlow',
            'dailyBreakdown',
            'week',
            'startOfWeek',
            'endOfWeek'
        ));
    }

    public function monthly(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $user = Auth::user();

        $transactions = $user->transactions()
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
            ->with(['account', 'category', 'transfer.fromAccount', 'transfer.toAccount'])
            ->orderBy('date', 'desc')
            ->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $totalTransfer = $transactions->where('type', 'transfer')->sum('amount');

        $netCashFlow = $totalIncome - $totalExpense;

        // Group by category
        $incomeByCategory = $transactions->where('type', 'income')
            ->groupBy('category_id')
            ->map(function($group) {
                $category = $group->first()->category;
                return [
                    'category' => $category ? $category->name : 'Uncategorized',
                    'amount' => $group->sum('amount'),
                    'count' => $group->count()
                ];
            });

        $expenseByCategory = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(function($group) {
                $category = $group->first()->category;
                return [
                    'category' => $category ? $category->name : 'Uncategorized',
                    'amount' => $group->sum('amount'),
                    'count' => $group->count()
                ];
            });

        return view('reports.monthly', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'totalTransfer',
            'netCashFlow',
            'incomeByCategory',
            'expenseByCategory',
            'month'
        ));
    }

    public function profitLoss(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $user = Auth::user();

        // Income transactions
        $incomeTransactions = $user->transactions()
            ->where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['category'])
            ->get();

        $totalIncome = $incomeTransactions->sum('amount');

        $incomeByCategory = $incomeTransactions->groupBy('category_id')->map(function($group) {
            $category = $group->first()->category;
            return [
                'category' => $category ? $category->name : 'Uncategorized',
                'amount' => $group->sum('amount'),
                'percentage' => 0 // Will be calculated after totals
            ];
        });

        // Expense transactions
        $expenseTransactions = $user->transactions()
            ->where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['category'])
            ->get();

        $totalExpense = $expenseTransactions->sum('amount');

        $expenseByCategory = $expenseTransactions->groupBy('category_id')->map(function($group) {
            $category = $group->first()->category;
            return [
                'category' => $category ? $category->name : 'Uncategorized',
                'amount' => $group->sum('amount'),
                'percentage' => 0 // Will be calculated after totals
            ];
        });

        // Calculate percentages
        foreach ($incomeByCategory as &$item) {
            $item['percentage'] = $totalIncome > 0 ? round(($item['amount'] / $totalIncome) * 100, 1) : 0;
        }

        foreach ($expenseByCategory as &$item) {
            $item['percentage'] = $totalExpense > 0 ? round(($item['amount'] / $totalExpense) * 100, 1) : 0;
        }

        $netProfit = $totalIncome - $totalExpense;
        $profitMargin = $totalIncome > 0 ? round(($netProfit / $totalIncome) * 100, 1) : 0;

        return view('reports.profit_loss', compact(
            'totalIncome',
            'totalExpense',
            'netProfit',
            'profitMargin',
            'incomeByCategory',
            'expenseByCategory',
            'startDate',
            'endDate'
        ));
    }

    public function cashFlow(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $user = Auth::user();

        // Get all transactions in date range
        $transactions = $user->transactions()
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['account', 'category', 'transfer.fromAccount', 'transfer.toAccount'])
            ->orderBy('date')
            ->get();

        // Calculate cash flow by period (monthly)
        $cashFlowData = [];
        $periods = [];

        $currentDate = \Carbon\Carbon::parse($startDate);
        $endDateCarbon = \Carbon\Carbon::parse($endDate);

        while ($currentDate->lte($endDateCarbon)) {
            $periodStart = $currentDate->copy()->startOfMonth();
            $periodEnd = $currentDate->copy()->endOfMonth();

            $periodTransactions = $transactions->filter(function($transaction) use ($periodStart, $periodEnd) {
                return $transaction->date >= $periodStart && $transaction->date <= $periodEnd;
            });

            $operatingIncome = $periodTransactions->where('type', 'income')->sum('amount');
            $operatingExpenses = $periodTransactions->where('type', 'expense')->sum('amount');
            $netOperatingCashFlow = $operatingIncome - $operatingExpenses;

            $transfers = $periodTransactions->where('type', 'transfer')->sum('amount');

            $netCashFlow = $netOperatingCashFlow - $transfers;

            $cashFlowData[] = [
                'period' => $currentDate->format('M Y'),
                'operating_income' => $operatingIncome,
                'operating_expenses' => $operatingExpenses,
                'net_operating' => $netOperatingCashFlow,
                'transfers' => $transfers,
                'net_cash_flow' => $netCashFlow,
                'cumulative' => 0 // Will be calculated
            ];

            $currentDate->addMonth();
        }

        // Calculate cumulative cash flow
        $cumulative = 0;
        foreach ($cashFlowData as &$data) {
            $cumulative += $data['net_cash_flow'];
            $data['cumulative'] = $cumulative;
        }

        return view('reports.cash_flow', compact(
            'cashFlowData',
            'startDate',
            'endDate'
        ));
    }

    public function accounts()
    {
        $user = Auth::user();

        $accounts = $user->accounts()->with('transactions')->get()->map(function ($account) {
            $balance = $account->balance;
            $transactions = $account->transactions()->orderBy('date')->get();

            $balanceHistory = [];
            $runningBalance = 0;

            foreach ($transactions as $transaction) {
                if ($transaction->type === 'income') {
                    $runningBalance += $transaction->amount;
                } elseif ($transaction->type === 'expense') {
                    $runningBalance -= $transaction->amount;
                } elseif ($transaction->type === 'transfer') {
                    // For transfers, check if this account is the source or destination
                    if ($transaction->account_id === $account->id) {
                        // This is the source account for the transfer
                        $runningBalance -= $transaction->amount;
                    }
                }

                $balanceHistory[] = [
                    'date' => $transaction->date->format('Y-m-d'),
                    'balance' => $runningBalance,
                ];
            }

            return [
                'account' => $account,
                'current_balance' => $balance,
                'balance_history' => $balanceHistory,
            ];
        });

        return view('reports.accounts', compact('accounts'));
    }

    public function transfers()
    {
        $user = Auth::user();

        $transfers = $user->transfers()
            ->with(['fromAccount', 'toAccount'])
            ->orderBy('date', 'desc')
            ->get();

        $totalTransferred = $transfers->sum('amount');

        return view('reports.transfers', compact('transfers', 'totalTransferred'));
    }

    public function reconciliation()
    {
        $user = Auth::user();

        $accounts = $user->accounts()->with('transactions')->get()->map(function ($account) {
            $totalTransactions = $account->transactions()->count();
            $reconciledTransactions = $account->transactions()->where('reconciled', true)->count();

            return [
                'account' => $account,
                'total_transactions' => $totalTransactions,
                'reconciled_transactions' => $reconciledTransactions,
                'reconciliation_percentage' => $totalTransactions > 0 ? round(($reconciledTransactions / $totalTransactions) * 100, 2) : 0,
            ];
        });

        return view('reports.reconciliation', compact('accounts'));
    }

    // Sales Reports
    public function totalSales(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = $user->transactions()
            ->where('type', 'income')
            ->whereNotNull('product_id');

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $totalSales = $query->sum('amount');
        $salesCount = $query->count();

        return view('reports.total_sales', compact('totalSales', 'salesCount', 'startDate', 'endDate'));
    }

    public function topProducts(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $limit = $request->get('limit', 10);

        $query = $user->transactions()
            ->where('type', 'income')
            ->whereNotNull('product_id')
            ->with('product')
            ->select('product_id', DB::raw('SUM(amount) as total_sales'), DB::raw('COUNT(*) as sales_count'))
            ->groupBy('product_id')
            ->orderBy('total_sales', 'desc')
            ->limit($limit);

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $topProducts = $query->get()->map(function ($item) {
            return [
                'product' => $item->product,
                'total_sales' => $item->total_sales,
                'sales_count' => $item->sales_count,
            ];
        });

        return view('reports.top_products', compact('topProducts', 'startDate', 'endDate', 'limit'));
    }

    public function salesByCustomer(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Since transactions don't have customer_id, we can't link transactions to specific customers
        // For now, just return all customers with placeholder data
        $customers = $user->customers()->get()->map(function ($customer) {
            return [
                'customer' => $customer,
                'total_sales' => 0, // Placeholder - no transaction data available
                'sales_count' => 0, // Placeholder - no transaction data available
            ];
        });

        return view('reports.sales_by_customer', compact('customers', 'startDate', 'endDate'));
    }

    // Inventory Reports
    public function stockLevels()
    {
        $user = Auth::user();

        $products = $user->products()->with('productCategory')->get()->map(function ($product) {
            return [
                'product' => $product,
                'stock_quantity' => $product->stock_quantity,
                'unit' => $product->unit,
                'value' => $product->stock_quantity * $product->cost_price,
            ];
        });

        $totalValue = $products->sum('value');

        return view('reports.stock_levels', compact('products', 'totalValue'));
    }

    public function stockMovements(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $type = $request->get('type'); // in, out, or null for all

        $query = $user->stockMovements()->with('product');

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }
        if ($type) {
            $query->where('type', $type);
        }

        $movements = $query->orderBy('date', 'desc')->get();

        return view('reports.stock_movements', compact('movements', 'startDate', 'endDate', 'type'));
    }

    public function inventoryValue()
    {
        $user = Auth::user();

        $products = $user->products()->get();

        $totalValue = $products->sum(function ($product) {
            return $product->stock_quantity * $product->cost_price;
        });

        $productsByCategory = $products->groupBy('product_category_id')->map(function ($group) {
            return [
                'category' => $group->first()->productCategory->name ?? 'Uncategorized',
                'total_value' => $group->sum(function ($product) {
                    return $product->stock_quantity * $product->cost_price;
                }),
                'products' => $group,
            ];
        });

        return view('reports.inventory_value', compact('totalValue', 'productsByCategory'));
    }

    // Financial Reports - Balance Sheet and Trial Balance
    public function balanceSheet(Request $request)
    {
        $asOfDate = $request->get('asOfDate', now()->format('Y-m-d'));
        // Use active branch from session, fallback to request parameter
        $branchId = session('active_branch') ?? $request->get('branch_id');

        $reportingService = new FinancialReportingService();
        $balanceSheet = $reportingService->generateBalanceSheet(Carbon::parse($asOfDate), $branchId);

        return view('reports.balance_sheet', compact('balanceSheet', 'asOfDate', 'branchId'));
    }

    public function trialBalance(Request $request)
    {
        $asOfDate = $request->get('asOfDate', now()->format('Y-m-d'));
        // Use active branch from session, fallback to request parameter
        $branchId = session('active_branch') ?? $request->get('branch_id');

        $reportingService = new FinancialReportingService();
        $trialBalance = $reportingService->generateTrialBalance(Carbon::parse($asOfDate), $branchId);

        return view('reports.trial_balance', compact('trialBalance', 'asOfDate', 'branchId'));
    }

    public function incomeStatement(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        // Use active branch from session, fallback to request parameter
        $branchId = session('active_branch') ?? $request->get('branch_id');

        $reportingService = new FinancialReportingService();
        $incomeStatement = $reportingService->generateIncomeStatement(
            Carbon::parse($startDate),
            Carbon::parse($endDate),
            $branchId
        );

        return view('reports.income_statement', compact('incomeStatement', 'startDate', 'endDate', 'branchId'));
    }
}