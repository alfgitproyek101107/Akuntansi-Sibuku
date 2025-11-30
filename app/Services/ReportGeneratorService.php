<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportGeneratorService
{
    /**
     * Generate daily report data
     */
    public function generateDailyReport(?string $branchId, string $date): array
    {
        $startDate = Carbon::parse($date)->startOfDay();
        $endDate = Carbon::parse($date)->endOfDay();

        $query = Transaction::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'posted');

        if ($branchId && $branchId !== 'all') {
            $query->where('branch_id', $branchId);
        }

        $transactions = $query->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');
        $profit = $income - $expense;

        // Get stock movements for the day
        $stockQuery = StockMovement::whereBetween('date', [$startDate, $endDate]);

        if ($branchId && $branchId !== 'all') {
            $stockQuery->where('branch_id', $branchId);
        }

        $stockMovements = $stockQuery->with('product')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get top products/services
        $topProduct = $this->getTopProduct($transactions, $branchId);
        $topService = $this->getTopService($transactions, $branchId);

        return [
            'income' => $income,
            'expense' => $expense,
            'profit' => $profit,
            'transaction_count' => $transactions->count(),
            'stock_changes' => $stockMovements->map(function ($movement) {
                return [
                    'product_name' => $movement->product->name ?? 'Unknown',
                    'quantity' => $movement->quantity,
                    'type' => $movement->type,
                ];
            })->toArray(),
            'top_product' => $topProduct,
            'top_service' => $topService,
            'date' => $date,
            'period' => 'daily',
        ];
    }

    /**
     * Generate weekly report data
     */
    public function generateWeeklyReport(?string $branchId, string $date): array
    {
        $startDate = Carbon::parse($date)->startOfWeek();
        $endDate = Carbon::parse($date)->endOfWeek();

        $query = Transaction::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'posted');

        if ($branchId && $branchId !== 'all') {
            $query->where('branch_id', $branchId);
        }

        $transactions = $query->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');
        $profit = $income - $expense;

        // Get branch activity summary
        $branchActivity = $this->getBranchActivity($transactions);

        return [
            'income' => $income,
            'expense' => $expense,
            'profit' => $profit,
            'transaction_count' => $transactions->count(),
            'branch_activity' => $branchActivity,
            'top_product' => $this->getTopProduct($transactions, $branchId),
            'top_service' => $this->getTopService($transactions, $branchId),
            'date_range' => [$startDate->toDateString(), $endDate->toDateString()],
            'period' => 'weekly',
        ];
    }

    /**
     * Generate monthly report data
     */
    public function generateMonthlyReport(?string $branchId, string $date): array
    {
        $startDate = Carbon::parse($date)->startOfMonth();
        $endDate = Carbon::parse($date)->endOfMonth();

        $query = Transaction::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'posted');

        if ($branchId && $branchId !== 'all') {
            $query->where('branch_id', $branchId);
        }

        $transactions = $query->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');
        $profit = $income - $expense;

        // Get account balances
        $accountBalances = $this->getAccountBalances($branchId);

        // Get tax summary if applicable
        $taxSummary = $this->getTaxSummary($transactions);

        return [
            'income' => $income,
            'expense' => $expense,
            'profit' => $profit,
            'transaction_count' => $transactions->count(),
            'account_balances' => $accountBalances,
            'tax_summary' => $taxSummary,
            'top_product' => $this->getTopProduct($transactions, $branchId),
            'top_service' => $this->getTopService($transactions, $branchId),
            'date_range' => [$startDate->toDateString(), $endDate->toDateString()],
            'period' => 'monthly',
        ];
    }

    /**
     * Generate custom report data
     */
    public function generateCustomReport(?string $branchId, string $startDate, string $endDate): array
    {
        $query = Transaction::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'posted');

        if ($branchId && $branchId !== 'all') {
            $query->where('branch_id', $branchId);
        }

        $transactions = $query->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');
        $profit = $income - $expense;

        return [
            'income' => $income,
            'expense' => $expense,
            'profit' => $profit,
            'transaction_count' => $transactions->count(),
            'top_product' => $this->getTopProduct($transactions, $branchId),
            'top_service' => $this->getTopService($transactions, $branchId),
            'date_range' => [$startDate, $endDate],
            'period' => 'custom',
        ];
    }

    /**
     * Get top selling product
     */
    protected function getTopProduct($transactions, ?string $branchId): ?string
    {
        $incomeTransactions = $transactions->where('type', 'income');

        if ($incomeTransactions->isEmpty()) {
            return null;
        }

        // Get products from income items
        $productSales = DB::table('income_items')
            ->join('products', 'income_items.product_id', '=', 'products.id')
            ->whereIn('income_items.income_id', $incomeTransactions->pluck('id'))
            ->select('products.name', DB::raw('SUM(income_items.quantity) as total_quantity'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->first();

        return $productSales ? $productSales->name . " ({$productSales->total_quantity})" : null;
    }

    /**
     * Get top service
     */
    protected function getTopService($transactions, ?string $branchId): ?string
    {
        $serviceTransactions = $transactions->where('type', 'income')
            ->whereNotNull('service_id');

        if ($serviceTransactions->isEmpty()) {
            return null;
        }

        $topService = $serviceTransactions->groupBy('service_id')
            ->map(function ($group) {
                return [
                    'service' => $group->first()->service,
                    'count' => $group->count(),
                    'total' => $group->sum('amount')
                ];
            })
            ->sortByDesc('count')
            ->first();

        return $topService && $topService['service']
            ? $topService['service']->name . " ({$topService['count']}x)"
            : null;
    }

    /**
     * Get branch activity summary
     */
    protected function getBranchActivity($transactions): array
    {
        return $transactions->groupBy('branch_id')
            ->map(function ($branchTransactions, $branchId) {
                return [
                    'branch_name' => $branchTransactions->first()->branch?->name ?? 'Unknown',
                    'transaction_count' => $branchTransactions->count(),
                    'income' => $branchTransactions->where('type', 'income')->sum('amount'),
                    'expense' => $branchTransactions->where('type', 'expense')->sum('amount'),
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Get account balances
     */
    protected function getAccountBalances(?string $branchId): array
    {
        $query = Account::select('name', 'balance', 'type');

        if ($branchId && $branchId !== 'all') {
            $query->where('branch_id', $branchId);
        }

        return $query->get()
            ->groupBy('type')
            ->map(function ($accounts, $type) {
                return $accounts->sum('balance');
            })
            ->toArray();
    }

    /**
     * Get tax summary
     */
    protected function getTaxSummary($transactions): array
    {
        $taxableTransactions = $transactions->where('tax_rate', '>', 0);

        return [
            'total_taxable' => $taxableTransactions->sum('amount'),
            'total_tax_amount' => $taxableTransactions->sum('tax_amount'),
            'tax_rate' => $taxableTransactions->first()?->tax_rate ?? 0,
        ];
    }

    /**
     * Generate PDF report (placeholder for future implementation)
     */
    public function generatePdfReport(array $reportData): ?string
    {
        // TODO: Implement PDF generation using libraries like TCPDF, DomPDF, etc.
        // For now, return null to send text-only messages

        // Example implementation:
        // $pdf = new TCPDF();
        // ... generate PDF content ...
        // $filename = 'report-' . $reportData['period'] . '-' . now()->format('Y-m-d') . '.pdf';
        // $path = storage_path('app/public/reports/' . $reportData['period'] . '/' . $filename);
        // $pdf->Output($path, 'F');
        // return asset('storage/reports/' . $reportData['period'] . '/' . $filename);

        return null; // Text-only for now
    }
}