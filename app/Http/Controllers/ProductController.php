<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\TaxRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        // BranchScope will automatically filter products by current branch
        $products = Product::with('productCategory')->orderBy('name')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        // BranchScope will automatically filter product categories by current branch
        $productCategories = ProductCategory::orderBy('name')->get();
        $taxRules = $this->getTaxRulesForDropdown();
        return view('products.create', compact('productCategories', 'taxRules'));
    }

    public function store(Request $request)
    {
        $validator = $this->validateTaxFields($request->all(), [
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'sku' => 'nullable|string|max:255|unique:products,sku',
            'sale_price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify ownership of product category (BranchScope will handle branch filtering)
        $productCategory = ProductCategory::find($request->product_category_id);
        if (!$productCategory) {
            abort(403, 'Product category not found or access denied.');
        }

        // Prepare data with branch_id
        $data = $request->all();
        $data['branch_id'] = session('active_branch') ?? Auth::user()->branch_id ?? null;

        // Handle tax rule synchronization
        $data = $this->syncTaxFromRule($data);

        Auth::user()->products()->create($data);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        // BranchScope handles branch access, but we keep user ownership for additional security
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        // Get related data (BranchScope will filter automatically)
        $transactions = $product->transactions()->with('account')->orderBy('date', 'desc')->get();
        $stockMovements = $product->stockMovements()->orderBy('date', 'desc')->get();

        return view('products.show', compact('product', 'transactions', 'stockMovements'));
    }

    public function edit(Product $product)
    {
        // BranchScope handles branch access, but we keep user ownership for additional security
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        // Get product categories (BranchScope will filter to current branch)
        $productCategories = ProductCategory::all();
        $taxRules = $this->getTaxRulesForDropdown();

        return view('products.edit', compact('product', 'productCategories', 'taxRules'));
    }

    public function update(Request $request, Product $product)
    {
        // BranchScope will handle access control, but we keep user ownership check for additional security
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        $validator = $this->validateTaxFields($request->all(), [
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'sku' => 'nullable|string|max:255|unique:products,sku,' . $product->id,
            'sale_price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify ownership of product category (BranchScope will handle branch filtering)
        $productCategory = ProductCategory::find($request->product_category_id);
        if (!$productCategory) {
            abort(403, 'Product category not found or access denied.');
        }

        // Ensure branch_id remains consistent
        $data = $request->all();
        $data['branch_id'] = $product->branch_id; // Keep existing branch_id

        // Handle tax rule synchronization
        $data = $this->syncTaxFromRule($data);

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // BranchScope handles branch access, but we keep user ownership for additional security
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if product has transactions (BranchScope will filter automatically)
        if ($product->transactions()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete product with existing transactions.');
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * Get tax rules for dropdown selection.
     */
    protected function getTaxRulesForDropdown()
    {
        $branchId = session('active_branch') ?? Auth::user()->branch_id ?? null;
        return TaxRule::active()
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->orderBy('name')
            ->get();
    }

    /**
     * Validate tax fields with custom validation logic.
     */
    protected function validateTaxFields(array $data, array $baseRules = [])
    {
        $taxRules = [
            'tax_rule_id' => 'nullable|exists:tax_rules,id',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'is_tax_included' => 'boolean',
            'tax_type' => 'required|in:ppn,non_pajak,ppn_0,bebas_pajak',
        ];

        $rules = array_merge($baseRules, $taxRules);

        return Validator::make($data, $rules);
    }

    /**
     * Sync tax settings from tax rule if provided.
     */
    protected function syncTaxFromRule(array $data)
    {
        if (!empty($data['tax_rule_id'])) {
            $taxRule = TaxRule::find($data['tax_rule_id']);
            if ($taxRule) {
                // Only sync if not manually overridden
                if (!isset($data['tax_percentage']) || $data['tax_percentage'] === null) {
                    $data['tax_percentage'] = $taxRule->percentage;
                }
                if (!isset($data['tax_type']) || $data['tax_type'] === null) {
                    // Map tax rule type to product tax_type
                    $data['tax_type'] = $this->mapTaxRuleTypeToProductType($taxRule);
                }
            }
        }

        // Set defaults for backward compatibility
        if (!isset($data['tax_percentage'])) {
            $data['tax_percentage'] = 0;
        }
        if (!isset($data['tax_type'])) {
            $data['tax_type'] = 'non_pajak';
        }
        if (!isset($data['is_tax_included'])) {
            $data['is_tax_included'] = false;
        }

        return $data;
    }

    /**
     * Map tax rule type to product tax_type.
     */
    protected function mapTaxRuleTypeToProductType(TaxRule $taxRule)
    {
        if ($taxRule->percentage == 0) {
            return 'non_pajak';
        } elseif ($taxRule->percentage == 11) {
            return 'ppn';
        } elseif ($taxRule->percentage == 0 && $taxRule->code == 'PPN0') {
            return 'ppn_0';
        } else {
            return 'bebas_pajak';
        }
    }
}