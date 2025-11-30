<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use App\Models\StockMovement;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function index()
    {
        // BranchScope will automatically filter transactions by current branch
        $expenses = Transaction::where('type', 'expense')
            ->with(['account', 'category'])
            ->orderBy('date', 'desc')
            ->get();
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        // BranchScope will automatically filter accounts, categories, products, and services by current branch
        $accounts = Account::orderBy('name')->get();
        $categories = Category::where('type', 'expense')->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('expenses.create', compact('accounts', 'categories', 'products', 'services'));
    }

    public function store(Request $request)
    {
        // Validate input mode first
        $inputMode = $request->input('input_mode', 'simple');

        $rules = [
            'input_mode' => 'required|in:simple,product',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'date' => 'required|date',
        ];

        if ($inputMode === 'simple') {
            $rules['amount'] = 'required|numeric|min:0.01';
        } elseif ($inputMode === 'product') {
            // For multi-item product mode, validate items array
            $rules['items'] = 'required|array|min:1';
            $rules['items.*.product_id'] = 'required|exists:products,id';
            $rules['items.*.quantity'] = 'required|integer|min:1';
            $rules['items.*.price'] = 'required|numeric|min:0';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Calculate amount based on input mode
        $totalAmount = 0;
        if ($inputMode === 'simple') {
            $totalAmount = $request->amount;
        } elseif ($inputMode === 'product') {
            // Calculate total from items
            foreach ($request->items as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }
        }

        // Check if user is superadmin (has access to all resources)
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('super-admin') || $user->hasRole('superadmin') ||
                       ($user->userRole && in_array($user->userRole->name, ['super_admin', 'super-admin', 'superadmin']));

        // Verify ownership and branch access
        $account = Account::find($request->account_id);
        $category = Category::find($request->category_id);

        // Check if category type is correct
        if ($category->type !== 'expense') {
            abort(403, 'Invalid category type for expense');
        }

        if (!$isSuperAdmin) {
            // Get current branch for access control
            $currentBranchId = session('active_branch') ?? $user->branch_id ?? null;

            // Check if user has branch access to the resources
            $userBranches = $user->branches()->pluck('branches.id')->toArray();

            // Check branch access (allow access if user owns the data or has branch access)
            $hasAccountAccess = $account->user_id === Auth::id() ||
                                ($currentBranchId && $account->branch_id === $currentBranchId) ||
                                in_array($account->branch_id, $userBranches);
            $hasCategoryAccess = $category->user_id === Auth::id() ||
                                 ($currentBranchId && $category->branch_id === $currentBranchId) ||
                                 in_array($category->branch_id, $userBranches);

            if (!$hasAccountAccess || !$hasCategoryAccess) {
                abort(403, 'Unauthorized access to account or category');
            }
        }

        // Verify product ownership if in product mode
        $products = [];
        if ($inputMode === 'product') {
            // Validate stock availability and access for all items
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    abort(404, 'Product not found');
                }

                // Check access if not superadmin
                if (!$isSuperAdmin) {
                    $currentBranchId = session('active_branch') ?? $user->branch_id ?? null;
                    $userBranches = $user->branches()->pluck('branches.id')->toArray();

                    $hasProductAccess = $product->user_id === Auth::id() ||
                                        ($currentBranchId && $product->branch_id === $currentBranchId) ||
                                        in_array($product->branch_id, $userBranches);
                    if (!$hasProductAccess) {
                        abort(403, 'Unauthorized access to product: ' . $product->name);
                    }
                }

                $products[] = $product;
            }
        }

        // Check sufficient balance
        if ($account->balance < $totalAmount) {
            return redirect()->back()->with('error', 'Insufficient account balance.')->withInput();
        }

        // Handle receipt image upload
        $receiptImagePath = null;
        if ($request->hasFile('receipt_image')) {
            $receiptImagePath = $request->file('receipt_image')->store('receipts', 'public');
        }

        DB::transaction(function () use ($request, $account, $inputMode, $products, $totalAmount, $receiptImagePath) {
            // Prepare transaction data with branch_id
            $transactionData = [
                'account_id' => $request->account_id,
                'category_id' => $request->category_id,
                'product_id' => null, // For multi-item, we don't set a single product_id
                'amount' => $totalAmount,
                'description' => $request->description,
                'receipt_image' => $receiptImagePath,
                'date' => $request->date,
                'type' => 'expense',
                'branch_id' => session('active_branch') ?? Auth::user()->branch_id ?? null,
            ];

            // Create transaction
            $transaction = Auth::user()->transactions()->create($transactionData);

            // Update account balance
            $account->decrement('balance', $totalAmount);

            // Handle product stock movement if in product mode
            if ($inputMode === 'product' && !empty($products)) {
                // Create expense items and handle stock for each item
                foreach ($request->items as $index => $item) {
                    $product = $products[$index];

                    // Create expense item record
                    \App\Models\ExpenseItem::create([
                        'expense_id' => $transaction->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);

                    StockMovement::create([
                        'user_id' => Auth::id(),
                        'product_id' => $product->id,
                        'type' => 'in',
                        'quantity' => $item['quantity'],
                        'date' => $request->date,
                        'reference' => 'Transaction #' . $transaction->id,
                        'notes' => 'Purchase transaction - ' . $product->name,
                        'branch_id' => $transactionData['branch_id'],
                    ]);

                    // Adjust stock
                    $product->increment('stock_quantity', $item['quantity']);
                }
            }
        });

        // Clear dashboard cache since data has changed
        DashboardController::clearDashboardCache();

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function show($id)
    {
        // BranchScope will handle branch filtering
        $transaction = Transaction::where('id', $id)
            ->where('type', 'expense')
            ->where('user_id', Auth::id()) // Additional security check
            ->with('expenseItems.product', 'expenseItems.service') // Load expense items with products and services
            ->firstOrFail();

        return view('expenses.show', compact('transaction'));
    }

    public function edit($id)
    {
        // BranchScope will handle branch filtering
        $transaction = Transaction::where('id', $id)
            ->where('type', 'expense')
            ->where('user_id', Auth::id()) // Additional security check
            ->with('expenseItems.product', 'expenseItems.service') // Load expense items with products and services
            ->firstOrFail();

        // BranchScope will automatically filter accounts, categories, products, and services by current branch
        $accounts = Account::orderBy('name')->get();
        $categories = Category::where('type', 'expense')->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $services = Service::where('is_active', true)->orderBy('name')->get();

        // Determine input mode based on existing expense items
        $inputMode = 'simple';
        if ($transaction->expenseItems->whereNotNull('product_id')->count() > 0) {
            $inputMode = 'product';
        }

        return view('expenses.edit', compact('transaction', 'accounts', 'categories', 'products', 'services', 'inputMode'));
    }

    public function update(Request $request, $id)
    {
        $transaction = Auth::user()->transactions()
            ->where('id', $id)
            ->where('type', 'expense')
            ->firstOrFail();

        // Validate input mode first
        $inputMode = $request->input('input_mode', 'simple');

        $rules = [
            'input_mode' => 'required|in:simple,product',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'date' => 'required|date',
        ];

        if ($inputMode === 'simple') {
            $rules['amount'] = 'required|numeric|min:0.01';
        } elseif ($inputMode === 'product') {
            // For multi-item product mode, validate items array
            $rules['items'] = 'required|array|min:1';
            $rules['items.*.product_id'] = 'required|exists:products,id';
            $rules['items.*.quantity'] = 'required|integer|min:1';
            $rules['items.*.price'] = 'required|numeric|min:0';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if user is superadmin (has access to all resources)
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('super-admin') || $user->hasRole('superadmin') ||
                        ($user->userRole && in_array($user->userRole->name, ['super_admin', 'super-admin', 'superadmin', 'Super Admin']));

        // Verify ownership and branch access
        $account = Account::find($request->account_id);
        $category = Category::find($request->category_id);

        // Check if category type is correct
        if ($category->type !== 'expense') {
            abort(403, 'Invalid category type for expense');
        }

        if (!$isSuperAdmin) {
            // Get current branch for access control
            $currentBranchId = session('active_branch') ?? $user->branch_id ?? null;

            // Check if user has branch access to the resources
            $userBranches = $user->branches()->pluck('branches.id')->toArray();

            // Check branch access (allow access if user owns the data or has branch access)
            $hasAccountAccess = $account->user_id === Auth::id() ||
                                ($currentBranchId && $account->branch_id === $currentBranchId) ||
                                in_array($account->branch_id, $userBranches);
            $hasCategoryAccess = $category->user_id === Auth::id() ||
                                 ($currentBranchId && $category->branch_id === $currentBranchId) ||
                                 in_array($category->branch_id, $userBranches);

            if (!$hasAccountAccess || !$hasCategoryAccess) {
                abort(403, 'Unauthorized access to account or category');
            }
        }

        // Calculate new amount based on input mode
        $newAmount = 0;
        if ($inputMode === 'simple') {
            $newAmount = $request->amount;
        } elseif ($inputMode === 'product') {
            // Calculate total from items
            foreach ($request->items as $item) {
                $newAmount += $item['price'] * $item['quantity'];
            }
        }

        // Check sufficient balance if account changed or amount increased
        $balanceAfterRevert = $transaction->account->balance + $transaction->amount;
        if ($balanceAfterRevert < $newAmount) {
            return redirect()->back()->with('error', 'Insufficient account balance.')->withInput();
        }

        // Verify product ownership if in product mode
        $products = [];
        if ($inputMode === 'product') {
            // Validate stock availability and access for all items
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    abort(404, 'Product not found');
                }

                // Check stock availability (need to account for existing stock)
                $existingItem = $transaction->expenseItems->where('product_id', $item['product_id'])->first();
                $existingQuantity = $existingItem ? $existingItem->quantity : 0;
                $additionalQuantity = $item['quantity'] - $existingQuantity;

                if ($additionalQuantity > 0 && $product->stock_quantity < $additionalQuantity) {
                    abort(400, "Insufficient stock for {$product->name}. Available: {$product->stock_quantity}, Requested additional: {$additionalQuantity}");
                }

                // Check access if not superadmin
                if (!$isSuperAdmin) {
                    $currentBranchId = session('active_branch') ?? $user->branch_id ?? null;
                    $userBranches = $user->branches()->pluck('branches.id')->toArray();

                    $hasProductAccess = $product->user_id === Auth::id() ||
                                        ($currentBranchId && $product->branch_id === $currentBranchId) ||
                                        in_array($product->branch_id, $userBranches);
                    if (!$hasProductAccess) {
                        abort(403, 'Unauthorized access to product: ' . $product->name);
                    }
                }

                $products[] = $product;
            }
        }

        // Handle receipt image upload
        $receiptImagePath = $transaction->receipt_image; // Keep existing image by default
        if ($request->hasFile('receipt_image')) {
            // Delete old image if exists
            if ($transaction->receipt_image) {
                \Storage::disk('public')->delete($transaction->receipt_image);
            }
            $receiptImagePath = $request->file('receipt_image')->store('receipts', 'public');
        }

        DB::transaction(function () use ($request, $transaction, $account, $inputMode, $products, $newAmount, $receiptImagePath) {
            // Revert old balance
            $transaction->account->increment('balance', $transaction->amount);

            // Revert old stock movements for all expense items
            $oldExpenseItems = $transaction->expenseItems;
            foreach ($oldExpenseItems as $item) {
                if ($item->product) {
                    $stockMovements = StockMovement::where('reference', 'Transaction #' . $transaction->id)
                        ->where('product_id', $item->product_id)
                        ->get();

                    foreach ($stockMovements as $stockMovement) {
                        $item->product->decrement('stock_quantity', $stockMovement->quantity);
                        $stockMovement->delete();
                    }
                }
            }

            // Delete old expense items
            $transaction->expenseItems()->delete();

            // Update transaction
            $transaction->update([
                'account_id' => $request->account_id,
                'category_id' => $request->category_id,
                'product_id' => null, // For multi-item, we don't set a single product_id
                'amount' => $newAmount,
                'description' => $request->description,
                'receipt_image' => $receiptImagePath,
                'date' => $request->date,
            ]);

            // Update new balance
            $account->decrement('balance', $newAmount);

            // Handle product stock movement if in product mode
            if ($inputMode === 'product' && !empty($products)) {
                // Create expense items and handle stock for each item
                foreach ($request->items as $index => $item) {
                    $product = $products[$index];

                    // Create expense item record
                    \App\Models\ExpenseItem::create([
                        'expense_id' => $transaction->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);

                    StockMovement::create([
                        'user_id' => Auth::id(),
                        'product_id' => $product->id,
                        'type' => 'in',
                        'quantity' => $item['quantity'],
                        'date' => $request->date,
                        'reference' => 'Transaction #' . $transaction->id,
                        'notes' => 'Purchase transaction - ' . $product->name,
                        'branch_id' => $transaction->branch_id,
                    ]);

                    // Adjust stock
                    $product->increment('stock_quantity', $item['quantity']);
                }
            }
        });

        // Clear dashboard cache since data has changed
        DashboardController::clearDashboardCache();

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy($id)
    {
        // BranchScope will handle branch filtering
        $transaction = Transaction::where('id', $id)
            ->where('type', 'expense')
            ->where('user_id', Auth::id()) // Additional security check
            ->firstOrFail();

        DB::transaction(function () use ($transaction) {
            // Revert balance
            $transaction->account->increment('balance', $transaction->amount);

            // Revert stock movements for all expense items
            $expenseItems = $transaction->expenseItems;
            foreach ($expenseItems as $item) {
                if ($item->product) {
                    $stockMovements = StockMovement::where('reference', 'Transaction #' . $transaction->id)
                        ->where('product_id', $item->product_id)
                        ->get();

                    foreach ($stockMovements as $stockMovement) {
                        $item->product->decrement('stock_quantity', $stockMovement->quantity);
                        $stockMovement->delete();
                    }
                }
            }

            // Delete expense items
            $transaction->expenseItems()->delete();

            $transaction->delete();
        });

        // Clear dashboard cache since data has changed
        DashboardController::clearDashboardCache();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}