<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use App\Models\StockMovement;
use App\Services\AccountBalanceService;
use App\Services\ActivityLogService;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IncomeController extends Controller
{
    public function index()
    {
        // BranchScope will automatically filter transactions by current branch
        $incomes = Transaction::where('type', 'income')
            ->with(['account', 'category'])
            ->orderBy('date', 'desc')
            ->get();
        return view('incomes.index', compact('incomes'));
    }

    public function create()
    {
        // BranchScope will automatically filter accounts and categories by current branch
        $accounts = Account::orderBy('name')->get();
        $categories = Category::where('type', 'income')->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('incomes.create', compact('accounts', 'categories', 'products', 'services'));
    }

    public function store(Request $request)
    {
        // Validate input mode first
        $inputMode = $request->input('input_mode', 'simple');

        $rules = [
            'input_mode' => 'required|in:simple,product,service',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
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
        } elseif ($inputMode === 'service') {
            // For multi-item service mode, validate services array
            $rules['services'] = 'required|array|min:1';
            $rules['services.*.service_id'] = 'required|exists:services,id';
            $rules['services.*.quantity'] = 'required|integer|min:1';
            $rules['services.*.price'] = 'required|numeric|min:0';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Calculate amount based on input mode (skip tax calculations for now)
        $totalAmount = 0;
        $taxCalculation = null;
        if ($inputMode === 'simple') {
            $totalAmount = $request->amount;
        } elseif ($inputMode === 'product') {
            // Calculate total from items (skip taxes)
            foreach ($request->items as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }
        } elseif ($inputMode === 'service') {
            // Calculate total from services
            foreach ($request->services as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }
        }

        // Check if user is superadmin (has access to all resources)
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('super-admin') || $user->hasRole('superadmin') ||
                        ($user->userRole && in_array($user->userRole->name, ['super_admin', 'super-admin', 'superadmin', 'Super Admin']));

        // Verify ownership and branch access
        $account = Account::find($request->account_id);
        $category = Category::find($request->category_id);

        // Check if category type is correct
        if ($category->type !== 'income') {
            abort(403, 'Invalid category type for income');
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
        $services = [];
        if ($inputMode === 'product') {
            // Validate stock availability and access for all items
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    abort(404, 'Product not found');
                }

                // Check stock availability
                if ($product->stock_quantity < $item['quantity']) {
                    abort(400, "Insufficient stock for {$product->name}. Available: {$product->stock_quantity}, Requested: {$item['quantity']}");
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
        } elseif ($inputMode === 'service') {
            // Validate access for all services
            foreach ($request->services as $item) {
                $service = Service::find($item['service_id']);
                if (!$service) {
                    abort(404, 'Service not found');
                }

                // Check access if not superadmin
                if (!$isSuperAdmin) {
                    $currentBranchId = session('active_branch') ?? $user->branch_id ?? null;
                    $userBranches = $user->branches()->pluck('branches.id')->toArray();

                    $hasServiceAccess = $service->user_id === Auth::id() ||
                                        ($currentBranchId && $service->branch_id === $currentBranchId) ||
                                        in_array($service->branch_id, $userBranches);
                    if (!$hasServiceAccess) {
                        abort(403, 'Unauthorized access to service: ' . $service->name);
                    }
                }

                $services[] = $service;
            }
        }


        // Prepare transaction data with branch_id
        $transactionData = [
            'account_id' => $request->account_id,
            'category_id' => $request->category_id,
            'product_id' => null, // For multi-item, we don't set a single product_id
            'service_id' => null, // For multi-service, we don't set a single service_id
            'amount' => $totalAmount,
            'description' => $request->description,
            'date' => $request->date,
            'type' => 'income',
            'branch_id' => session('active_branch') ?? Auth::user()->branch_id ?? null,
        ];

        // Create transaction outside closure for logging access
        $transaction = Auth::user()->transactions()->create($transactionData);

        DB::transaction(function () use ($request, $account, $inputMode, $products, $totalAmount, $transaction, $taxCalculation) {
            $balanceService = new AccountBalanceService();

            // Update account balance with race condition protection
            $balanceService->updateBalance($account->id, $totalAmount, 'add');

            // Handle product stock movement if in product mode
            if ($inputMode === 'product' && !empty($products)) {
                // Create income items and handle stock for each item
                foreach ($request->items as $index => $item) {
                    $product = $products[$index];

                    // Create income item record (skip tax calculations)
                    \App\Models\IncomeItem::create([
                        'income_id' => $transaction->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                        'tax_rate' => 0,
                        'tax_amount' => 0,
                        'tax_type' => null,
                        'total_with_tax' => $item['price'] * $item['quantity'],
                    ]);

                    // Decrease stock
                    $product->decrement('stock_quantity', $item['quantity']);

                    // Create stock movement record
                    StockMovement::create([
                        'user_id' => Auth::id(),
                        'product_id' => $product->id,
                        'type' => 'out',
                        'quantity' => $item['quantity'],
                        'date' => $request->date,
                        'reference' => 'Transaction #' . $transaction->id,
                        'notes' => 'Sale transaction - ' . $product->name,
                        'branch_id' => session('active_branch') ?? Auth::user()->branch_id ?? null,
                    ]);
                }
            }

            // Handle service items if in service mode
            if ($inputMode === 'service' && !empty($services)) {
                // Create income items for each service
                foreach ($request->services as $index => $item) {
                    // Create income item record for service
                    \App\Models\IncomeItem::create([
                        'income_id' => $transaction->id,
                        'service_id' => $item['service_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);
                }
            }

            // Skip journal entries for tax transactions for now
        });

        // Prepare product/service info for logging
        $productNames = [];
        $serviceNames = [];

        if ($inputMode === 'product' && !empty($products)) {
            $productNames = array_map(function($product) {
                return $product->name;
            }, $products);
        }

        if ($inputMode === 'service' && !empty($services)) {
            $serviceNames = array_map(function($service) {
                return $service->name;
            }, $services);
        }

        // Log activity (skip tax info for now)
        ActivityLogService::log(
            'income_created',
            "Created income transaction: {$request->description} (Rp " . number_format($totalAmount, 0, ',', '.') . ")",
            [
                'transaction_id' => $transaction->id,
                'amount' => $totalAmount,
                'account_name' => $account->name,
                'category_name' => $category->name,
                'input_mode' => $inputMode,
                'product_names' => $productNames,
                'service_names' => $serviceNames,
                'item_count' => $inputMode === 'product' ? count($products) : ($inputMode === 'service' ? count($services) : 1),
            ]
        );

        // Clear dashboard cache since data has changed
        DashboardController::clearDashboardCache();

        return redirect()->route('incomes.index')->with('success', 'Income recorded successfully.');
    }

    public function show($id)
    {
        // BranchScope will handle branch filtering
        $transaction = Transaction::where('id', $id)
            ->where('type', 'income')
            ->where('user_id', Auth::id()) // Additional security check
            ->with('incomeItems.product') // Load income items with products
            ->firstOrFail();

        return view('incomes.show', compact('transaction'));
    }

    public function edit($id)
    {
        // BranchScope will handle branch filtering
        $transaction = Transaction::where('id', $id)
            ->where('type', 'income')
            ->where('user_id', Auth::id()) // Additional security check
            ->with('incomeItems.product', 'incomeItems.service') // Load income items with products and services
            ->firstOrFail();

        // BranchScope will automatically filter accounts and categories by current branch
        $accounts = Account::orderBy('name')->get();
        $categories = Category::where('type', 'income')->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $services = Service::where('is_active', true)->orderBy('name')->get();

        // Determine input mode based on existing income items
        $inputMode = 'simple';
        if ($transaction->incomeItems->whereNotNull('product_id')->count() > 0) {
            $inputMode = 'product';
        } elseif ($transaction->incomeItems->whereNotNull('service_id')->count() > 0) {
            $inputMode = 'service';
        }

        return view('incomes.edit', compact('transaction', 'accounts', 'categories', 'products', 'services', 'inputMode'));
    }

    public function update(Request $request, $id)
    {
        $transaction = Auth::user()->transactions()
            ->where('id', $id)
            ->where('type', 'income')
            ->firstOrFail();

        // Validate input mode first
        $inputMode = $request->input('input_mode', 'simple');

        $rules = [
            'input_mode' => 'required|in:simple,product,service',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
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
        } elseif ($inputMode === 'service') {
            // For multi-item service mode, validate services array
            $rules['services'] = 'required|array|min:1';
            $rules['services.*.service_id'] = 'required|exists:services,id';
            $rules['services.*.quantity'] = 'required|integer|min:1';
            $rules['services.*.price'] = 'required|numeric|min:0';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify ownership and branch access
        $account = Account::find($request->account_id);
        $category = Category::find($request->category_id);

        // Check if category type is correct
        if ($category->type !== 'income') {
            abort(403, 'Invalid category type for income');
        }

        // Check if user is superadmin (has access to all resources)
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('super-admin') || $user->hasRole('superadmin') ||
                       ($user->userRole && in_array($user->userRole->name, ['super_admin', 'super-admin', 'superadmin', 'Super Admin']));

        if ($isSuperAdmin) {
            // Superadmin has access to all resources
            $hasAccountAccess = true;
            $hasCategoryAccess = true;
            $hasProductAccess = true;
        } else {
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
        $services = [];
        if ($inputMode === 'product') {
            // Validate stock availability and access for all items
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    abort(404, 'Product not found');
                }

                // Check stock availability (need to account for existing stock)
                $existingItem = $transaction->incomeItems->where('product_id', $item['product_id'])->first();
                $existingQuantity = $existingItem ? $existingItem->quantity : 0;
                $additionalQuantity = $item['quantity'] - $existingQuantity;

                if ($additionalQuantity > 0 && $product->stock_quantity < $additionalQuantity) {
                    abort(400, "Insufficient stock for {$product->name}. Available: {$product->stock_quantity}, Requested additional: {$additionalQuantity}");
                }

                // Check access if not superadmin
                if (!$isSuperAdmin) {
                    $hasProductAccess = $product->user_id === Auth::id() ||
                                        ($currentBranchId && $product->branch_id === $currentBranchId) ||
                                        in_array($product->branch_id, $userBranches);
                    if (!$hasProductAccess) {
                        abort(403, 'Unauthorized access to product: ' . $product->name);
                    }
                }

                $products[] = $product;
            }
        } elseif ($inputMode === 'service') {
            // Validate access for all services
            foreach ($request->services as $item) {
                $service = Service::find($item['service_id']);
                if (!$service) {
                    abort(404, 'Service not found');
                }

                // Check access if not superadmin
                if (!$isSuperAdmin) {
                    $hasServiceAccess = $service->user_id === Auth::id() ||
                                        ($currentBranchId && $service->branch_id === $currentBranchId) ||
                                        in_array($service->branch_id, $userBranches);
                    if (!$hasServiceAccess) {
                        abort(403, 'Unauthorized access to service: ' . $service->name);
                    }
                }

                $services[] = $service;
            }
        }

        // Calculate new amount based on input mode (skip tax calculations for now)
        $newAmount = 0;
        $newTaxCalculation = null;
        if ($inputMode === 'simple') {
            $newAmount = $request->amount;
        } elseif ($inputMode === 'product') {
            // Calculate total from items (skip taxes)
            foreach ($request->items as $item) {
                $newAmount += $item['price'] * $item['quantity'];
            }
        } elseif ($inputMode === 'service') {
            // Calculate total from services
            foreach ($request->services as $item) {
                $newAmount += $item['price'] * $item['quantity'];
            }
        }

        DB::transaction(function () use ($request, $transaction, $account, $inputMode, $products, $services, $newAmount, $newTaxCalculation) {
            $balanceService = new AccountBalanceService();

            // Revert old balance (allow negative balances for transaction reversals)
            $balanceService->updateBalance($transaction->account_id, $transaction->amount, 'subtract', true);

            // Revert old stock movements for all income items
            $oldIncomeItems = $transaction->incomeItems;
            foreach ($oldIncomeItems as $item) {
                if ($item->product) {
                    $stockMovements = StockMovement::where('reference', 'Transaction #' . $transaction->id)
                        ->where('product_id', $item->product_id)
                        ->get();

                    foreach ($stockMovements as $stockMovement) {
                        $item->product->increment('stock_quantity', $stockMovement->quantity);
                        $stockMovement->delete();
                    }
                }
            }

            // Delete old income items
            $transaction->incomeItems()->delete();

            // Update transaction
            $transaction->update([
                'account_id' => $request->account_id,
                'category_id' => $request->category_id,
                'product_id' => null, // For multi-item, we don't set a single product_id
                'service_id' => null, // For multi-service, we don't set a single service_id
                'amount' => $newAmount,
                'description' => $request->description,
                'date' => $request->date,
            ]);

            // Update new balance
            $balanceService->updateBalance($account->id, $newAmount, 'add');

            // Handle product stock movement if in product mode
            if ($inputMode === 'product' && !empty($products)) {
                // Create income items and handle stock for each item
                foreach ($request->items as $index => $item) {
                    $product = $products[$index];

                    // Create income item record (skip tax calculations)
                    \App\Models\IncomeItem::create([
                        'income_id' => $transaction->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                        'tax_rate' => 0,
                        'tax_amount' => 0,
                        'tax_type' => null,
                        'total_with_tax' => $item['price'] * $item['quantity'],
                    ]);

                    StockMovement::create([
                        'user_id' => Auth::id(),
                        'product_id' => $product->id,
                        'type' => 'out',
                        'quantity' => $item['quantity'],
                        'date' => $request->date,
                        'reference' => 'Transaction #' . $transaction->id,
                        'notes' => 'Sale transaction - ' . $product->name,
                        'branch_id' => session('active_branch') ?? Auth::user()->branch_id ?? null,
                    ]);

                    $product->decrement('stock_quantity', $item['quantity']);
                }
            }

            // Handle service items if in service mode
            if ($inputMode === 'service' && !empty($services)) {
                // Create income items for each service
                foreach ($request->services as $index => $item) {
                    // Create income item record for service
                    \App\Models\IncomeItem::create([
                        'income_id' => $transaction->id,
                        'service_id' => $item['service_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);
                }
            }

            // Skip journal entries for tax transactions for now
        });

        // Log activity (skip tax info for now)
        ActivityLogService::log(
            'income_updated',
            "Updated income transaction #{$transaction->id}: {$request->description}",
            [
                'transaction_id' => $transaction->id,
                'old_amount' => $transaction->amount,
                'new_amount' => $newAmount,
                'account_name' => $account->name,
                'category_name' => $category->name,
                'input_mode' => $inputMode,
            ]
        );

        // Clear dashboard cache since data has changed
        DashboardController::clearDashboardCache();

        return redirect()->route('incomes.index')->with('success', 'Income updated successfully.');
    }

    public function destroy($id)
    {
        // BranchScope will handle branch filtering
        $transaction = Transaction::where('id', $id)
            ->where('type', 'income')
            ->where('user_id', Auth::id()) // Additional security check
            ->firstOrFail();

        DB::transaction(function () use ($transaction) {
            $balanceService = new AccountBalanceService();

            // Revert balance (allow negative balances for transaction reversals)
            $balanceService->updateBalance($transaction->account_id, $transaction->amount, 'subtract', true);

            // Revert stock movements for all income items
            $incomeItems = $transaction->incomeItems;
            foreach ($incomeItems as $item) {
                $stockMovements = StockMovement::where('reference', 'Transaction #' . $transaction->id)
                    ->where('product_id', $item->product_id)
                    ->get();

                foreach ($stockMovements as $stockMovement) {
                    $item->product->increment('stock_quantity', $stockMovement->quantity);
                    $stockMovement->delete();
                }
            }

            // Delete income items
            $transaction->incomeItems()->delete();

            $transaction->delete();
        });

        // Log activity
        ActivityLogService::log(
            'income_deleted',
            "Deleted income transaction: {$transaction->description} (Rp " . number_format($transaction->amount, 0, ',', '.') . ")",
            [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'account_name' => $transaction->account?->name,
                'category_name' => $transaction->category?->name,
                'product_name' => $transaction->product?->name,
            ]
        );

        // Clear dashboard cache since data has changed
        DashboardController::clearDashboardCache();

        return redirect()->route('incomes.index')->with('success', 'Income deleted successfully.');
    }

    /**
     * Calculate tax for a product-quantity combination.
     */
    private function calculateProductTax($product, $quantity)
    {
        try {
            if (!$product) {
                throw new \Exception('Product not found for tax calculation');
            }

            if ($quantity <= 0) {
                throw new \Exception('Invalid quantity for tax calculation');
            }

            $baseAmount = $product->sale_price * $quantity;

            if ($product->tax_percentage <= 0) {
                // No tax or tax-exempt product
                return [
                    'base_amount' => $baseAmount,
                    'tax_rate' => 0,
                    'tax_amount' => 0,
                    'total' => $baseAmount,
                    'tax_type' => null,
                ];
            }

            $taxRate = $product->tax_percentage;
            $taxAmount = ($baseAmount * $taxRate) / 100;
            $total = $baseAmount + $taxAmount;

            return [
                'base_amount' => $baseAmount,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'tax_type' => $product->tax_type ?? 'ppn',
            ];
        } catch (\Exception $e) {
            \Log::error('Tax calculation error: ' . $e->getMessage(), [
                'product_id' => $product ? $product->id : null,
                'quantity' => $quantity,
            ]);

            // Return zero tax on error to maintain functionality
            return [
                'base_amount' => $product ? ($product->sale_price * $quantity) : 0,
                'tax_rate' => 0,
                'tax_amount' => 0,
                'total' => $product ? ($product->sale_price * $quantity) : 0,
                'tax_type' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Calculate total tax for all items in a transaction.
     */
    private function calculateTransactionTax($items)
    {
        try {
            if (empty($items)) {
                return [
                    'total_base_amount' => 0,
                    'total_tax_amount' => 0,
                    'total_with_tax' => 0,
                    'tax_breakdown' => [],
                ];
            }

            $totalTax = 0;
            $totalBase = 0;
            $taxBreakdown = [];
            $errors = [];

            foreach ($items as $item) {
                $product = $item['product'] ?? null;
                if ($product) {
                    $taxCalculation = $this->calculateProductTax($product, $item['quantity']);

                    if (isset($taxCalculation['error'])) {
                        $errors[] = "Product {$product->name}: {$taxCalculation['error']}";
                    }

                    $totalTax += $taxCalculation['tax_amount'];
                    $totalBase += $taxCalculation['base_amount'];

                    $taxBreakdown[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $item['quantity'],
                        'base_amount' => $taxCalculation['base_amount'],
                        'tax_rate' => $taxCalculation['tax_rate'],
                        'tax_amount' => $taxCalculation['tax_amount'],
                        'tax_type' => $taxCalculation['tax_type'],
                    ];
                }
            }

            $result = [
                'total_base_amount' => $totalBase,
                'total_tax_amount' => $totalTax,
                'total_with_tax' => $totalBase + $totalTax,
                'tax_breakdown' => $taxBreakdown,
            ];

            if (!empty($errors)) {
                $result['errors'] = $errors;
                \Log::warning('Tax calculation completed with errors: ' . implode('; ', $errors));
            }

            return $result;

        } catch (\Exception $e) {
            \Log::error('Transaction tax calculation error: ' . $e->getMessage());

            // Return zero tax on error to maintain functionality
            return [
                'total_base_amount' => 0,
                'total_tax_amount' => 0,
                'total_with_tax' => 0,
                'tax_breakdown' => [],
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Prepare tax journal entries for a transaction.
     */
    private function prepareTaxJournalEntries($transaction, $items)
    {
        $taxCalculation = $this->calculateTransactionTax($items);

        if ($taxCalculation['total_tax_amount'] <= 0) {
            return [];
        }

        $journalEntries = [];

        // Get tax settings for the branch
        $taxSettings = \App\Models\TaxSetting::getForBranch($transaction->branch_id);

        // Find tax output account (PPN Keluaran)
        $taxAccount = \App\Models\Account::where('branch_id', $transaction->branch_id)
            ->where('name', 'like', '%PPN%')
            ->orWhere('name', 'like', '%pajak%')
            ->first();

        if (!$taxAccount) {
            // Create default tax account if not exists
            $taxAccount = \App\Models\Account::create([
                'name' => 'PPN Keluaran',
                'code' => '2101',
                'type' => 'liability',
                'branch_id' => $transaction->branch_id,
                'user_id' => $transaction->user_id,
                'is_active' => true,
            ]);
        }

        // Debit: Cash/Receivable Account (full amount including tax)
        $journalEntries[] = [
            'account_id' => $transaction->account_id,
            'debit' => $taxCalculation['total_with_tax'],
            'credit' => 0,
            'description' => 'Penerimaan pendapatan dengan PPN',
        ];

        // Credit: Revenue Account (base amount)
        $revenueAccount = $transaction->category->account ?? \App\Models\Account::where('branch_id', $transaction->branch_id)
            ->where('type', 'income')
            ->first();

        if ($revenueAccount) {
            $journalEntries[] = [
                'account_id' => $revenueAccount->id,
                'debit' => 0,
                'credit' => $taxCalculation['total_base_amount'],
                'description' => 'Pendapatan usaha',
            ];
        }

        // Credit: Tax Output Account (tax amount)
        $journalEntries[] = [
            'account_id' => $taxAccount->id,
            'debit' => 0,
            'credit' => $taxCalculation['total_tax_amount'],
            'description' => 'PPN Keluaran',
        ];

        return $journalEntries;
    }
}