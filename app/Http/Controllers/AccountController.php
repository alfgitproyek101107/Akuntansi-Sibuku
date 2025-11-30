<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Get current branch ID from session
     */
    private function getCurrentBranchId()
    {
        $branchId = session('active_branch') ?? Auth::user()->branch_id ?? null;

        // Ensure demo branch exists if needed
        if ($branchId == 999) {
            $this->ensureDemoBranchExists();
        }

        return $branchId;
    }

    /**
     * Ensure demo branch exists
     */
    private function ensureDemoBranchExists()
    {
        \App\Models\Branch::firstOrCreate(
            ['id' => 999],
            [
                'name' => 'Demo Cabang',
                'address' => 'Jl. Demo No. 123, Jakarta',
                'phone' => '+62-21-1234567',
                'email' => 'demo@company.com',
                'is_demo' => true,
            ]
        );
    }

    public function index()
    {
        $branchId = $this->getCurrentBranchId();
        $accounts = Account::where('branch_id', $branchId)->get();
        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        $user = Auth::user();
        $availableBranches = [];

        // Get branches the user has access to
        if ($user) {
            $userBranches = DB::table('user_branches')
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->pluck('branch_id')
                ->toArray();

            if (!empty($userBranches)) {
                $availableBranches = \App\Models\Branch::whereIn('id', $userBranches)->get();
            } else {
                // Fallback to user's default branch
                $availableBranches = \App\Models\Branch::where('id', $user->branch_id)->get();
            }
        }

        return view('accounts.create', compact('availableBranches'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $availableBranchIds = [];

        // Get branches the user has access to
        if ($user) {
            $userBranches = DB::table('user_branches')
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->pluck('branch_id')
                ->toArray();

            if (!empty($userBranches)) {
                $availableBranchIds = $userBranches;
            } else {
                // Fallback to user's default branch
                $availableBranchIds = [$user->branch_id];
            }
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank,savings,checking,credit',
            'balance' => 'required|numeric|min:0',
            'branch_id' => 'nullable|integer|in:' . implode(',', $availableBranchIds),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['name', 'type', 'balance']);
        $data['branch_id'] = $request->branch_id ?? $this->getCurrentBranchId();

        // Ensure branch exists
        if (!\App\Models\Branch::find($data['branch_id'])) {
            return redirect()->back()
                ->withErrors(['branch_id' => 'Selected branch does not exist.'])
                ->withInput();
        }

        $data['user_id'] = Auth::id();

        Account::create($data);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    public function show(Account $account)
    {
        $branchId = $this->getCurrentBranchId();
        if ($account->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to account');
        }
        $transactions = $account->transactions()->with('category')->orderBy('date', 'desc')->get();
        return view('accounts.show', compact('account', 'transactions'));
    }

    public function edit(Account $account)
    {
        $branchId = $this->getCurrentBranchId();
        if ($account->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to account');
        }
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $branchId = $this->getCurrentBranchId();
        if ($account->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to account');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank,savings,checking,credit',
            'balance' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $account->update($request->only(['name', 'type', 'balance']));

        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $account)
    {
        $branchId = $this->getCurrentBranchId();
        if ($account->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to account');
        }

        // Check if account has transactions
        if ($account->transactions()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete account with existing transactions.');
        }

        $account->delete();

        return redirect()->route('accounts.index')->with('success', 'Account deleted successfully.');
    }

    public function reconcile(Account $account)
    {
        $branchId = $this->getCurrentBranchId();
        if ($account->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to account');
        }

        $transactions = $account->transactions()
            ->with('category')
            ->orderBy('date', 'desc')
            ->get();

        return view('accounts.reconcile', compact('account', 'transactions'));
    }

    public function toggleReconcile(Request $request, Account $account)
    {
        $branchId = $this->getCurrentBranchId();
        if ($account->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to account');
        }

        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
        ]);

        $transaction = $account->transactions()->find($request->transaction_id);
        if (!$transaction) {
            abort(404);
        }

        $transaction->update(['reconciled' => !$transaction->reconciled]);

        return response()->json(['success' => true, 'reconciled' => $transaction->reconciled]);
    }

    public function ledger(Account $account, Request $request)
    {
        $branchId = $this->getCurrentBranchId();
        if ($account->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to account');
        }

        // Get filter parameters
        $fromDate = $request->get('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->get('to_date', now()->endOfMonth()->format('Y-m-d'));
        $type = $request->get('type');
        $categoryId = $request->get('category_id');
        $reconciled = $request->get('reconciled');

        // Build query
        $query = $account->transactions()
            ->with(['category', 'transfer.fromAccount', 'transfer.toAccount'])
            ->whereBetween('date', [$fromDate, $toDate])
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'asc');

        if ($type) {
            $query->where('type', $type);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($reconciled !== null) {
            $query->where('reconciled', $reconciled === '1');
        }

        $transactions = $query->get();

        // Calculate running balance
        $runningBalance = $account->balance; // Start with current balance
        $ledgerEntries = [];

        // Reverse the transactions to calculate backwards from current balance
        $reversedTransactions = $transactions->reverse();

        foreach ($reversedTransactions as $transaction) {
            $amount = $transaction->amount;

            if ($transaction->type === 'income') {
                $runningBalance -= $amount; // Subtract income to get balance before
                $debit = $amount;
                $credit = 0;
            } elseif ($transaction->type === 'expense') {
                $runningBalance += $amount; // Add back expense to get balance before
                $debit = 0;
                $credit = $amount;
            } elseif ($transaction->type === 'transfer') {
                // For transfers, determine if this account is sender or receiver
                if ($transaction->transfer && $transaction->transfer->from_account_id === $account->id) {
                    // This account sent money
                    $runningBalance += $amount;
                    $debit = 0;
                    $credit = $amount;
                } elseif ($transaction->transfer && $transaction->transfer->to_account_id === $account->id) {
                    // This account received money
                    $runningBalance -= $amount;
                    $debit = $amount;
                    $credit = 0;
                } else {
                    $debit = 0;
                    $credit = 0;
                }
            }

            $ledgerEntries[] = [
                'transaction' => $transaction,
                'date' => $transaction->date,
                'description' => $this->getTransactionDescription($transaction),
                'category' => $transaction->category,
                'debit' => $debit,
                'credit' => $credit,
                'balance' => $runningBalance,
                'reconciled' => $transaction->reconciled,
            ];
        }

        // Reverse back to chronological order and convert to collection
        $ledgerEntries = collect(array_reverse($ledgerEntries));

        // Get categories for filter
        $categories = \App\Models\Category::where('branch_id', $branchId)->get();

        return view('accounts.ledger', compact(
            'account',
            'ledgerEntries',
            'fromDate',
            'toDate',
            'type',
            'categoryId',
            'reconciled',
            'categories'
        ));
    }

    private function getTransactionDescription($transaction)
    {
        if ($transaction->type === 'transfer') {
            if ($transaction->transfer) {
                if ($transaction->transfer->from_account_id === $transaction->account_id) {
                    return 'Transfer ke ' . $transaction->transfer->toAccount->name;
                } else {
                    return 'Transfer dari ' . $transaction->transfer->fromAccount->name;
                }
            }
        }

        return $transaction->description ?: ($transaction->category ? $transaction->category->name : 'Transaksi');
    }

    public function export(Request $request, Account $account)
    {
        $branchId = $this->getCurrentBranchId();
        if ($account->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to account');
        }

        $type = $request->get('type', 'excel');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        // Get ledger data
        $ledgerData = $this->getLedgerData($account, $fromDate, $toDate);

        if ($type === 'excel') {
            return $this->exportToExcel($account, $ledgerData);
        } elseif ($type === 'pdf') {
            return $this->exportToPDF($account, $ledgerData);
        }

        abort(400, 'Invalid export type');
    }

    private function getLedgerData(Account $account, $fromDate = null, $toDate = null)
    {
        $query = $account->transactions()
            ->with(['category', 'transfer.fromAccount', 'transfer.toAccount'])
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'asc');

        if ($fromDate && $toDate) {
            $query->whereBetween('date', [$fromDate, $toDate]);
        }

        $transactions = $query->get();

        // Calculate running balance
        $runningBalance = $account->balance;
        $ledgerEntries = [];

        $reversedTransactions = $transactions->reverse();

        foreach ($reversedTransactions as $transaction) {
            $amount = $transaction->amount;

            if ($transaction->type === 'income') {
                $runningBalance -= $amount;
                $debit = $amount;
                $credit = 0;
            } elseif ($transaction->type === 'expense') {
                $runningBalance += $amount;
                $debit = 0;
                $credit = $amount;
            } elseif ($transaction->type === 'transfer') {
                if ($transaction->transfer && $transaction->transfer->from_account_id === $account->id) {
                    $runningBalance += $amount;
                    $debit = 0;
                    $credit = $amount;
                } elseif ($transaction->transfer && $transaction->transfer->to_account_id === $account->id) {
                    $runningBalance -= $amount;
                    $debit = $amount;
                    $credit = 0;
                } else {
                    $debit = 0;
                    $credit = 0;
                }
            }

            $ledgerEntries[] = [
                'date' => $transaction->date->format('d/m/Y'),
                'description' => $this->getTransactionDescription($transaction),
                'reference' => $transaction->reference,
                'debit' => $debit,
                'credit' => $credit,
                'balance' => $runningBalance,
                'reconciled' => $transaction->reconciled ? 'Ya' : 'Tidak',
            ];
        }

        return array_reverse($ledgerEntries);
    }

    private function exportToExcel(Account $account, array $ledgerData)
    {
        $filename = 'mutasi_' . $account->name . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        // For now, return a simple CSV response
        // In a real application, you'd use a package like Laravel Excel
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($account, $ledgerData) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['Mutasi Rekening: ' . $account->name]);
            fputcsv($file, ['Tanggal Export: ' . now()->format('d/m/Y H:i:s')]);
            fputcsv($file, ['Saldo Saat Ini: Rp ' . number_format($account->balance, 0, ',', '.')]);
            fputcsv($file, []);

            // Column headers
            fputcsv($file, ['Tanggal', 'Deskripsi', 'Referensi', 'Debit', 'Kredit', 'Saldo', 'Rekonsiliasi']);

            // Data
            foreach ($ledgerData as $entry) {
                fputcsv($file, [
                    $entry['date'],
                    $entry['description'],
                    $entry['reference'] ?: '',
                    $entry['debit'] > 0 ? 'Rp ' . number_format($entry['debit'], 0, ',', '.') : '',
                    $entry['credit'] > 0 ? 'Rp ' . number_format($entry['credit'], 0, ',', '.') : '',
                    'Rp ' . number_format($entry['balance'], 0, ',', '.'),
                    $entry['reconciled'],
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToPDF(Account $account, array $ledgerData)
    {
        // For now, return a simple text response
        // In a real application, you'd use a package like DomPDF or TCPDF
        $filename = 'mutasi_' . $account->name . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        $content = "Mutasi Rekening: {$account->name}\n";
        $content .= "Tanggal Export: " . now()->format('d/m/Y H:i:s') . "\n";
        $content .= "Saldo Saat Ini: Rp " . number_format($account->balance, 0, ',', '.') . "\n\n";

        $content .= "Tanggal\tDeskripsi\tDebit\tKredit\tSaldo\tRekonsiliasi\n";

        foreach ($ledgerData as $entry) {
            $content .= $entry['date'] . "\t";
            $content .= $entry['description'] . "\t";
            $content .= ($entry['debit'] > 0 ? 'Rp ' . number_format($entry['debit'], 0, ',', '.') : '') . "\t";
            $content .= ($entry['credit'] > 0 ? 'Rp ' . number_format($entry['credit'], 0, ',', '.') : '') . "\t";
            $content .= 'Rp ' . number_format($entry['balance'], 0, ',', '.') . "\t";
            $content .= $entry['reconciled'] . "\n";
        }

        $headers = [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response($content, 200, $headers);
    }
}