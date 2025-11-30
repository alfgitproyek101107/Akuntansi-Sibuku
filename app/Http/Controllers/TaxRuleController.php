<?php

namespace App\Http\Controllers;

use App\Models\TaxRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\ActivityLogService;

class TaxRuleController extends Controller
{
    /**
     * Display a listing of tax rules with pagination and filtering
     */
    public function index(Request $request)
    {
        $query = TaxRule::with('creator');

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $taxRules = $query->orderBy('name')->paginate(15);

        return view('tax-rules.index', compact('taxRules'));
    }

    /**
     * Show the form for creating a new tax rule
     */
    public function create()
    {
        return view('tax-rules.create');
    }

    /**
     * Store a newly created tax rule in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:input,output',
            'percentage' => 'required|numeric|min:0|max:100',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Get current branch
            $branchId = session('active_branch') ?? Auth::user()->branch_id ?? null;

            $data = $request->all();
            $data['branch_id'] = $branchId;
            $data['created_by'] = Auth::id();

            $taxRule = TaxRule::create($data);

            // Log activity
            ActivityLogService::log(
                'tax_rule_created',
                "Tax rule '{$taxRule->name}' created",
                [
                    'tax_rule_id' => $taxRule->id,
                    'tax_rule_name' => $taxRule->name,
                    'type' => $taxRule->type,
                    'percentage' => $taxRule->percentage,
                ]
            );

            return redirect()->route('tax-rules.index')
                ->with('success', 'Tax rule created successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create tax rule: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified tax rule
     */
    public function show(TaxRule $taxRule)
    {
        // BranchScope handles branch access
        $taxRule->load(['creator', 'products']);

        return view('tax-rules.show', compact('taxRule'));
    }

    /**
     * Show the form for editing the specified tax rule
     */
    public function edit(TaxRule $taxRule)
    {
        // BranchScope handles branch access
        return view('tax-rules.edit', compact('taxRule'));
    }

    /**
     * Update the specified tax rule in storage
     */
    public function update(Request $request, TaxRule $taxRule)
    {
        // BranchScope handles branch access
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:input,output',
            'percentage' => 'required|numeric|min:0|max:100',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $oldValues = $taxRule->only(['name', 'type', 'percentage', 'code', 'description', 'is_active']);

            $taxRule->update($request->all());

            // Log activity
            ActivityLogService::log(
                'tax_rule_updated',
                "Tax rule '{$taxRule->name}' updated",
                [
                    'tax_rule_id' => $taxRule->id,
                    'tax_rule_name' => $taxRule->name,
                    'old_values' => $oldValues,
                    'new_values' => $taxRule->only(['name', 'type', 'percentage', 'code', 'description', 'is_active']),
                    'changed_fields' => array_keys(array_diff_assoc($taxRule->only(['name', 'type', 'percentage', 'code', 'description', 'is_active']), $oldValues)),
                ]
            );

            return redirect()->route('tax-rules.index')
                ->with('success', 'Tax rule updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update tax rule: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified tax rule from storage
     */
    public function destroy(TaxRule $taxRule)
    {
        // BranchScope handles branch access

        try {
            // Check if tax rule is used by products
            if ($taxRule->products()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete tax rule that is being used by products.');
            }

            $taxRuleName = $taxRule->name;

            $taxRule->delete();

            // Log activity
            ActivityLogService::log(
                'tax_rule_deleted',
                "Tax rule '{$taxRuleName}' deleted",
                [
                    'tax_rule_id' => $taxRule->id,
                    'tax_rule_name' => $taxRuleName,
                    'type' => $taxRule->type,
                    'percentage' => $taxRule->percentage,
                ]
            );

            return redirect()->route('tax-rules.index')
                ->with('success', 'Tax rule deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete tax rule: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the active status of a tax rule
     */
    public function toggleStatus(TaxRule $taxRule)
    {
        // BranchScope handles branch access

        try {
            $oldStatus = $taxRule->is_active;
            $taxRule->update(['is_active' => !$oldStatus]);

            $statusText = $taxRule->is_active ? 'activated' : 'deactivated';

            // Log activity
            ActivityLogService::log(
                'tax_rule_status_changed',
                "Tax rule '{$taxRule->name}' {$statusText}",
                [
                    'tax_rule_id' => $taxRule->id,
                    'tax_rule_name' => $taxRule->name,
                    'old_status' => $oldStatus,
                    'new_status' => $taxRule->is_active,
                ]
            );

            return redirect()->back()
                ->with('success', "Tax rule {$statusText} successfully.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to change tax rule status: ' . $e->getMessage());
        }
    }

    /**
     * Get tax rules for AJAX dropdowns
     */
    public function getTaxRules(Request $request)
    {
        $query = TaxRule::active();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $taxRules = $query->orderBy('name')
            ->limit(50)
            ->get(['id', 'name', 'code', 'type', 'percentage']);

        return response()->json([
            'success' => true,
            'data' => $taxRules->map(function ($rule) {
                return [
                    'id' => $rule->id,
                    'text' => $rule->name . ($rule->code ? " ({$rule->code})" : '') . " - {$rule->percentage}%",
                    'name' => $rule->name,
                    'code' => $rule->code,
                    'type' => $rule->type,
                    'percentage' => $rule->percentage,
                ];
            })
        ]);
    }
}