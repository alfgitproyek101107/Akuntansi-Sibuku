<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChartOfAccountsController extends Controller
{
    public function index()
    {
        $accounts = ChartOfAccount::active()
            ->orderBy('code')
            ->get()
            ->groupBy(function ($account) {
                return substr($account->code, 0, 1) . '000';
            });

        return view('chart-of-accounts.index', compact('accounts'));
    }

    public function show(ChartOfAccount $chartOfAccount)
    {
        $account = $chartOfAccount->load(['parent', 'children', 'journalLines.journalEntry']);

        // Get recent journal entries for this account
        $recentEntries = $account->journalLines()
            ->with('journalEntry')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('chart-of-accounts.show', compact('account', 'recentEntries'));
    }

    public function create()
    {
        $parents = ChartOfAccount::active()
            ->where('level', '<', 4)
            ->orderBy('code')
            ->get();

        return view('chart-of-accounts.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:chart_of_accounts,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'category' => 'required|string|max:50',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Determine level and normal balance based on parent or type
        $level = 1;
        $normalBalance = 'debit'; // default

        if ($request->parent_id) {
            $parent = ChartOfAccount::find($request->parent_id);
            $level = $parent->level + 1;
            $normalBalance = $parent->normal_balance;
        } else {
            // Set normal balance based on account type
            $normalBalance = match($request->type) {
                'asset' => 'debit',
                'liability', 'equity', 'revenue' => 'credit',
                'expense' => 'debit',
                default => 'debit'
            };
        }

        ChartOfAccount::create([
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
            'category' => $request->category,
            'parent_id' => $request->parent_id,
            'level' => $level,
            'normal_balance' => $normalBalance,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('chart-of-accounts.index')
            ->with('success', 'Akun berhasil ditambahkan ke Chart of Accounts');
    }

    public function edit(ChartOfAccount $chartOfAccount)
    {
        $parents = ChartOfAccount::active()
            ->where('level', '<', 4)
            ->where('id', '!=', $chartOfAccount->id)
            ->orderBy('code')
            ->get();

        return view('chart-of-accounts.edit', compact('chartOfAccount', 'parents'));
    }

    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:chart_of_accounts,code,' . $chartOfAccount->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'category' => 'required|string|max:50',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Prevent circular reference
        if ($request->parent_id && $this->wouldCreateCircularReference($chartOfAccount->id, $request->parent_id)) {
            return redirect()->back()
                ->withErrors(['parent_id' => 'Parent account would create a circular reference'])
                ->withInput();
        }

        // Determine level
        $level = 1;
        if ($request->parent_id) {
            $parent = ChartOfAccount::find($request->parent_id);
            $level = $parent->level + 1;
        }

        $chartOfAccount->update([
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
            'category' => $request->category,
            'parent_id' => $request->parent_id,
            'level' => $level,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('chart-of-accounts.index')
            ->with('success', 'Akun berhasil diperbarui');
    }

    public function destroy(ChartOfAccount $chartOfAccount)
    {
        // Check if account has children
        if ($chartOfAccount->children()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus akun yang memiliki sub-akun');
        }

        // Check if account has journal entries
        if ($chartOfAccount->journalLines()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus akun yang sudah digunakan dalam jurnal');
        }

        $chartOfAccount->delete();

        return redirect()->route('chart-of-accounts.index')
            ->with('success', 'Akun berhasil dihapus');
    }

    public function toggleActive(ChartOfAccount $chartOfAccount)
    {
        // Prevent deactivating accounts with children
        if (!$chartOfAccount->is_active && $chartOfAccount->children()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menonaktifkan akun yang memiliki sub-akun aktif');
        }

        $chartOfAccount->update(['is_active' => !$chartOfAccount->is_active]);

        $status = $chartOfAccount->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Akun berhasil {$status}");
    }


    private function wouldCreateCircularReference($accountId, $parentId): bool
    {
        $current = ChartOfAccount::find($parentId);

        while ($current) {
            if ($current->id == $accountId) {
                return true;
            }
            $current = $current->parent;
        }

        return false;
    }
}
