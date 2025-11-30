<?php

namespace App\Services;

use App\Models\WhatsAppLog;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * WhatsApp Report Service
 *
 * Handles all report generation and WhatsApp sending functionality
 * Combines report generation with WhatsApp API communication
 */
class WhatsAppReportService
{
    protected WhatsAppService $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Send report based on type
     */
    public function sendReport(string $reportType, string $phoneNumber, ?int $branchId = null, ?int $userId = null, string $source = 'manual'): array
    {
        try {
            // Validate phone number
            if (!$this->whatsAppService->validatePhoneNumber($phoneNumber)) {
                return [
                    'success' => false,
                    'message' => 'Format nomor WhatsApp tidak valid'
                ];
            }

            // Generate report content
            $reportData = $this->generateReport($reportType, $branchId);

            // Format message
            $message = $this->formatReportMessage($reportType, $reportData);

            // Send via WhatsApp
            $result = $this->whatsAppService->sendMessage($phoneNumber, $message);

            // Log the result
            $this->logReportActivity($reportType, $phoneNumber, $result, $userId, $branchId, $source);

            return [
                'success' => $result['success'],
                'message' => $result['success']
                    ? "Laporan {$reportType} berhasil dikirim"
                    : "Gagal mengirim laporan {$reportType}: " . ($result['message'] ?? 'Unknown error')
            ];

        } catch (\Exception $e) {
            Log::error("Failed to send {$reportType} report", [
                'error' => $e->getMessage(),
                'phone_number' => $phoneNumber,
                'branch_id' => $branchId,
                'user_id' => $userId
            ]);

            // Log failed attempt
            $this->logReportActivity($reportType, $phoneNumber, [
                'success' => false,
                'message' => $e->getMessage()
            ], $userId, $branchId, $source);

            return [
                'success' => false,
                'message' => 'Gagal mengirim laporan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate report data based on type
     */
    public function generateReport(string $reportType, ?int $branchId = null): array
    {
        return match ($reportType) {
            'daily' => $this->generateDailyReport($branchId),
            'weekly' => $this->generateWeeklyReport($branchId),
            'monthly' => $this->generateMonthlyReport($branchId),
            default => throw new \InvalidArgumentException("Unknown report type: {$reportType}")
        };
    }

    /**
     * Generate daily report
     */
    protected function generateDailyReport(?int $branchId = null): array
    {
        $query = Transaction::whereDate('date', today());

        if ($branchId && $branchId !== 'all') {
            $query->where('branch_id', $branchId);
        }

        $transactions = $query->with(['category', 'account'])->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');
        $netIncome = $income - $expense;

        // Get account balances
        $accountBalances = Account::when($branchId && $branchId !== 'all', function ($q) use ($branchId) {
            return $q->where('branch_id', $branchId);
        })->get()->map(function ($account) {
            return [
                'name' => $account->name,
                'balance' => $account->balance ?? 0
            ];
        });

        // Get recent transactions (last 5)
        $recentTransactions = $transactions->take(5)->map(function ($transaction) {
            return [
                'description' => $transaction->description,
                'amount' => $transaction->amount,
                'type' => $transaction->type,
                'category' => $transaction->category?->name ?? 'Uncategorized'
            ];
        });

        return [
            'date' => today()->format('d M Y'),
            'income' => $income,
            'expense' => $expense,
            'net_income' => $netIncome,
            'account_balances' => $accountBalances,
            'recent_transactions' => $recentTransactions,
            'total_transactions' => $transactions->count()
        ];
    }

    /**
     * Generate weekly report
     */
    protected function generateWeeklyReport(?int $branchId = null): array
    {
        $startDate = now()->startOfWeek();
        $endDate = now()->endOfWeek();

        $query = Transaction::whereBetween('date', [$startDate, $endDate]);

        if ($branchId && $branchId !== 'all') {
            $query->where('branch_id', $branchId);
        }

        $transactions = $query->with(['category'])->get();

        // Group by day
        $dailyStats = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dayTransactions = $transactions->where('date', $date->format('Y-m-d'));
            $dailyStats[$date->format('l')] = [
                'income' => $dayTransactions->where('type', 'income')->sum('amount'),
                'expense' => $dayTransactions->where('type', 'expense')->sum('amount'),
                'net' => $dayTransactions->where('type', 'income')->sum('amount') -
                        $dayTransactions->where('type', 'expense')->sum('amount')
            ];
        }

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $netIncome = $totalIncome - $totalExpense;

        // Top categories
        $topIncomeCategories = $transactions->where('type', 'income')
            ->groupBy('category_id')
            ->map(function ($group) {
                return [
                    'category' => $group->first()->category?->name ?? 'Uncategorized',
                    'amount' => $group->sum('amount')
                ];
            })
            ->sortByDesc('amount')
            ->take(3);

        $topExpenseCategories = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(function ($group) {
                return [
                    'category' => $group->first()->category?->name ?? 'Uncategorized',
                    'amount' => $group->sum('amount')
                ];
            })
            ->sortByDesc('amount')
            ->take(3);

        return [
            'period' => $startDate->format('d M') . ' - ' . $endDate->format('d M Y'),
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net_income' => $netIncome,
            'daily_stats' => $dailyStats,
            'top_income_categories' => $topIncomeCategories,
            'top_expense_categories' => $topExpenseCategories,
            'total_transactions' => $transactions->count()
        ];
    }

    /**
     * Generate monthly report
     */
    protected function generateMonthlyReport(?int $branchId = null): array
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        $query = Transaction::whereBetween('date', [$startDate, $endDate]);

        if ($branchId && $branchId !== 'all') {
            $query->where('branch_id', $branchId);
        }

        $transactions = $query->with(['category'])->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $netIncome = $totalIncome - $totalExpense;

        // Monthly comparison (current vs previous month)
        $prevMonthStart = now()->subMonth()->startOfMonth();
        $prevMonthEnd = now()->subMonth()->endOfMonth();

        $prevMonthTransactions = Transaction::whereBetween('date', [$prevMonthStart, $prevMonthEnd])
            ->when($branchId && $branchId !== 'all', function ($q) use ($branchId) {
                return $q->where('branch_id', $branchId);
            })
            ->get();

        $prevIncome = $prevMonthTransactions->where('type', 'income')->sum('amount');
        $prevExpense = $prevMonthTransactions->where('type', 'expense')->sum('amount');

        $incomeChange = $prevIncome > 0 ? (($totalIncome - $prevIncome) / $prevIncome) * 100 : 0;
        $expenseChange = $prevExpense > 0 ? (($totalExpense - $prevExpense) / $prevExpense) * 100 : 0;

        // Top categories
        $incomeByCategory = $transactions->where('type', 'income')
            ->groupBy('category_id')
            ->map(function ($group) {
                return [
                    'category' => $group->first()->category?->name ?? 'Uncategorized',
                    'amount' => $group->sum('amount'),
                    'count' => $group->count()
                ];
            })
            ->sortByDesc('amount')
            ->take(5);

        $expenseByCategory = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(function ($group) {
                return [
                    'category' => $group->first()->category?->name ?? 'Uncategorized',
                    'amount' => $group->sum('amount'),
                    'count' => $group->count()
                ];
            })
            ->sortByDesc('amount')
            ->take(5);

        return [
            'period' => $startDate->format('M Y'),
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net_income' => $netIncome,
            'comparison' => [
                'prev_income' => $prevIncome,
                'prev_expense' => $prevExpense,
                'income_change_percent' => round($incomeChange, 1),
                'expense_change_percent' => round($expenseChange, 1)
            ],
            'income_by_category' => $incomeByCategory,
            'expense_by_category' => $expenseByCategory,
            'total_transactions' => $transactions->count()
        ];
    }

    /**
     * Format report message for WhatsApp
     */
    protected function formatReportMessage(string $reportType, array $data): string
    {
        $header = "📊 LAPORAN " . strtoupper($reportType) . " - " . ($data['date'] ?? $data['period'] ?? now()->format('d M Y')) . "\n🏢 Sistem Sibuku\n\n";

        return match ($reportType) {
            'daily' => $this->formatDailyMessage($data),
            'weekly' => $this->formatWeeklyMessage($data),
            'monthly' => $this->formatMonthlyMessage($data),
            default => $header . "Laporan tidak tersedia"
        };
    }

    /**
     * Format daily report message
     */
    protected function formatDailyMessage(array $data): string
    {
        $message = "📊 LAPORAN KEUANGAN - {$data['date']}\n🏢 Sistem Sibuku\n\n";

        $message .= "💰 Pemasukan: Rp " . number_format($data['income'], 0, ',', '.') . "\n";
        $message .= "💸 Pengeluaran: Rp " . number_format($data['expense'], 0, ',', '.') . "\n";
        $message .= "📈 Laba Bersih: Rp " . number_format($data['net_income'], 0, ',', '.') . "\n\n";

        if (!empty($data['account_balances'])) {
            $message .= "🏦 Saldo Rekening:\n";
            foreach ($data['account_balances'] as $account) {
                $message .= "• {$account['name']}: Rp " . number_format($account['balance'], 0, ',', '.') . "\n";
            }
            $message .= "\n";
        }

        if (!empty($data['recent_transactions'])) {
            $message .= "📝 Transaksi Terbaru:\n";
            foreach ($data['recent_transactions'] as $transaction) {
                $emoji = $transaction['type'] === 'income' ? '➕' : '➖';
                $message .= "{$emoji} {$transaction['description']} - Rp " . number_format($transaction['amount'], 0, ',', '.') . "\n";
            }
            $message .= "\n";
        }

        $message .= "📊 Total Transaksi: {$data['total_transactions']}\n";

        return $message;
    }

    /**
     * Format weekly report message
     */
    protected function formatWeeklyMessage(array $data): string
    {
        $message = "📊 LAPORAN MINGGUAN - {$data['period']}\n🏢 Sistem Sibuku\n\n";

        $message .= "📈 Ringkasan:\n";
        $message .= "• Pemasukan: Rp " . number_format($data['total_income'], 0, ',', '.') . "\n";
        $message .= "• Pengeluaran: Rp " . number_format($data['total_expense'], 0, ',', '.') . "\n";
        $message .= "• Laba Bersih: Rp " . number_format($data['net_income'], 0, ',', '.') . "\n\n";

        if (!empty($data['daily_stats'])) {
            $message .= "📊 Tren Harian:\n";
            foreach ($data['daily_stats'] as $day => $stats) {
                if ($stats['income'] > 0 || $stats['expense'] > 0) {
                    $message .= "• {$day}: +Rp" . number_format($stats['income'], 0, ',', '.') .
                               " -Rp" . number_format($stats['expense'], 0, ',', '.') .
                               " = Rp" . number_format($stats['net'], 0, ',', '.') . "\n";
                }
            }
            $message .= "\n";
        }

        if (!empty($data['top_income_categories'])) {
            $message .= "🏆 Kategori Pemasukan Terbesar:\n";
            foreach ($data['top_income_categories'] as $index => $category) {
                $medal = ['🥇', '🥈', '🥉'][$index] ?? '🏅';
                $message .= "{$medal} {$category['category']}: Rp " . number_format($category['amount'], 0, ',', '.') . "\n";
            }
            $message .= "\n";
        }

        $message .= "📊 Total Transaksi: {$data['total_transactions']}\n";

        return $message;
    }

    /**
     * Format monthly report message
     */
    protected function formatMonthlyMessage(array $data): string
    {
        $message = "📊 LAPORAN BULANAN - {$data['period']}\n🏢 Sistem Sibuku\n\n";

        $message .= "💰 Total Pemasukan: Rp " . number_format($data['total_income'], 0, ',', '.') . "\n";
        $message .= "💸 Total Pengeluaran: Rp " . number_format($data['total_expense'], 0, ',', '.') . "\n";
        $message .= "📈 Laba Bersih: Rp " . number_format($data['net_income'], 0, ',', '.') . "\n\n";

        if (isset($data['comparison'])) {
            $message .= "📊 Perbandingan Bulan Lalu:\n";
            $incomeChange = $data['comparison']['income_change_percent'];
            $expenseChange = $data['comparison']['expense_change_percent'];

            $incomeIcon = $incomeChange >= 0 ? '📈' : '📉';
            $expenseIcon = $expenseChange >= 0 ? '📈' : '📉';

            $message .= "{$incomeIcon} Pemasukan: {$incomeChange}%\n";
            $message .= "{$expenseIcon} Pengeluaran: {$expenseChange}%\n\n";
        }

        if (!empty($data['income_by_category'])) {
            $message .= "💰 Pemasukan per Kategori:\n";
            foreach ($data['income_by_category'] as $index => $category) {
                $number = $index + 1;
                $message .= "{$number}. {$category['category']}: Rp " . number_format($category['amount'], 0, ',', '.') . "\n";
            }
            $message .= "\n";
        }

        if (!empty($data['expense_by_category'])) {
            $message .= "💸 Pengeluaran per Kategori:\n";
            foreach ($data['expense_by_category'] as $index => $category) {
                $number = $index + 1;
                $message .= "{$number}. {$category['category']}: Rp " . number_format($category['amount'], 0, ',', '.') . "\n";
            }
            $message .= "\n";
        }

        $message .= "📊 Total Transaksi: {$data['total_transactions']}\n";

        return $message;
    }

    /**
     * Log report activity
     */
    protected function logReportActivity(string $reportType, string $phoneNumber, array $result, ?int $userId = null, ?int $branchId = null, string $source = 'manual'): void
    {
        try {
            WhatsAppLog::create([
                'report_type' => $reportType,
                'phone_number' => $phoneNumber,
                'status' => $result['success'] ? 'success' : 'failed',
                'message' => $result['success'] ? "Report {$reportType} sent successfully" : ($result['message'] ?? 'Send failed'),
                'response_data' => $result,
                'error_message' => $result['success'] ? null : ($result['message'] ?? 'Unknown error'),
                'user_id' => $userId,
                'branch_id' => $branchId,
                'sent_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log WhatsApp activity', [
                'error' => $e->getMessage(),
                'report_type' => $reportType,
                'phone_number' => $phoneNumber,
                'result' => $result
            ]);
        }
    }
}