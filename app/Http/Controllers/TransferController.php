<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\Account;
use App\Models\Transaction;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransferController extends Controller
{
    public function index()
    {
        // BranchScope will automatically filter transfers by current branch
        // Only show transfers where both accounts still exist
        $transfers = Transfer::with(['fromAccount', 'toAccount'])
            ->whereHas('fromAccount')
            ->whereHas('toAccount')
            ->orderBy('date', 'desc')
            ->get();
        return view('transfers.index', compact('transfers'));
    }

    public function create()
    {
        // BranchScope will automatically filter accounts by current branch
        $accounts = Account::orderBy('name')->get();
        return view('transfers.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_id' => 'required|exists:accounts,id|different:from_account_id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get account instances for balance updates
        $fromAccount = Account::find($request->from_account_id);
        $toAccount = Account::find($request->to_account_id);

        DB::transaction(function () use ($request, $fromAccount, $toAccount) {
            // Prepare branch_id and is_demo
            $branchId = session('active_branch') ?? Auth::user()->branch_id ?? null;
            $isDemo = session('is_demo') ?? Auth::user()->is_demo ?? false;

            // Create transfer
            $transfer = Auth::user()->transfers()->create([
                'from_account_id' => $request->from_account_id,
                'to_account_id' => $request->to_account_id,
                'amount' => $request->amount,
                'description' => $request->description,
                'date' => $request->date,
                'branch_id' => $branchId,
                'is_demo' => $isDemo,
            ]);

            // Create transactions for double-entry
            Auth::user()->transactions()->create([
                'account_id' => $request->from_account_id,
                'category_id' => null, // No category for transfers
                'amount' => $request->amount,
                'description' => $request->description,
                'date' => $request->date,
                'type' => 'transfer',
                'transfer_id' => $transfer->id,
                'branch_id' => $branchId,
                'is_demo' => $isDemo,
            ]);

            Auth::user()->transactions()->create([
                'account_id' => $request->to_account_id,
                'category_id' => null,
                'amount' => $request->amount,
                'description' => $request->description,
                'date' => $request->date,
                'type' => 'transfer',
                'transfer_id' => $transfer->id,
                'branch_id' => $branchId,
                'is_demo' => $isDemo,
            ]);

            // Update account balances
            $fromAccount->decrement('balance', $request->amount);
            $toAccount->increment('balance', $request->amount);
        });

        // Clear dashboard cache since data has changed
        DashboardController::clearDashboardCache();

        return redirect()->route('transfers.index')->with('success', 'Transfer recorded successfully.');
    }

    public function show($id)
    {
        // BranchScope will handle branch filtering
        $transfer = Transfer::where('id', $id)
            ->where('user_id', Auth::id()) // Additional security check
            ->firstOrFail();

        // BranchScope will automatically filter accounts by current branch
        $accounts = Account::orderBy('name')->get();

        return view('transfers.show', compact('transfer', 'accounts'));
    }

    public function edit($id)
    {
        // BranchScope will handle branch filtering
        $transfer = Transfer::where('id', $id)
            ->where('user_id', Auth::id()) // Additional security check
            ->firstOrFail();

        // BranchScope will automatically filter accounts by current branch
        $accounts = Account::orderBy('name')->get();

        return view('transfers.edit', compact('transfer', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $transfer = Auth::user()->transfers()->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_id' => 'required|exists:accounts,id|different:from_account_id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get account instances for balance updates
        $fromAccount = Account::find($request->from_account_id);
        $toAccount = Account::find($request->to_account_id);

        DB::transaction(function () use ($request, $transfer, $fromAccount, $toAccount) {
            // Revert old balances
            $transfer->fromAccount->increment('balance', $transfer->amount);
            $transfer->toAccount->decrement('balance', $transfer->amount);

            // Update transfer
            $transfer->update([
                'from_account_id' => $request->from_account_id,
                'to_account_id' => $request->to_account_id,
                'amount' => $request->amount,
                'description' => $request->description,
                'date' => $request->date,
            ]);

            // Update transactions
            $transfer->transactions()->update([
                'amount' => $request->amount,
                'description' => $request->description,
                'date' => $request->date,
            ]);

            // Update new balances
            $fromAccount->decrement('balance', $request->amount);
            $toAccount->increment('balance', $request->amount);
        });

        // Clear dashboard cache since data has changed
        DashboardController::clearDashboardCache();

        return redirect()->route('transfers.index')->with('success', 'Transfer updated successfully.');
    }

    public function destroy($id)
    {
        // BranchScope will handle branch filtering
        $transfer = Transfer::where('id', $id)
            ->where('user_id', Auth::id()) // Additional security check
            ->firstOrFail();

        DB::transaction(function () use ($transfer) {
            // Revert balances
            $transfer->fromAccount->increment('balance', $transfer->amount);
            $transfer->toAccount->decrement('balance', $transfer->amount);

            // Delete transactions and transfer (BranchScope will filter automatically)
            $transfer->transactions()->delete();
            $transfer->delete();
        });

        // Clear dashboard cache since data has changed
        DashboardController::clearDashboardCache();

        return redirect()->route('transfers.index')->with('success', 'Transfer deleted successfully.');
    }
}
