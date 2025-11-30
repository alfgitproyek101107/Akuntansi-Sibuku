<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Get current branch ID from session
     */
    private function getCurrentBranchId()
    {
        return session('active_branch') ?? Auth::user()->branch_id ?? null;
    }

    public function index()
    {
        $user = Auth::user();

        // Primary approach: Show services from user's accessible branches
        $availableBranchIds = [];

        if ($user) {
            // Get branches the user has access to via user_branches table
            $userBranches = DB::table('user_branches')
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->pluck('branch_id')
                ->toArray();

            if (!empty($userBranches)) {
                $availableBranchIds = $userBranches;
            } elseif ($user->branch_id) {
                // Fallback to user's default branch
                $availableBranchIds = [$user->branch_id];
            }
        }

        // Get services from accessible branches
        if (!empty($availableBranchIds)) {
            $services = Service::whereIn('branch_id', $availableBranchIds)
                ->with(['productCategory', 'branch'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Ultimate fallback: show all services for this user (ignore branch filtering)
            $services = Service::where('user_id', $user->id)
                ->with(['productCategory', 'branch'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Ensure services is always a collection
        $services = $services ?? collect();

        // Calculate metrics for the view
        $totalServices = $services->count();
        $averagePrice = $services->avg('price') ?? 0;
        $highestPrice = $services->max('price') ?? 0;
        $uniqueCategories = $services->pluck('product_category_id')->unique()->filter()->count();

        return view('services.index', compact(
            'services',
            'totalServices',
            'averagePrice',
            'highestPrice',
            'uniqueCategories'
        ));
    }

    public function create()
    {
        // Get product categories for the current user
        $productCategories = Auth::user()->productCategories()->get() ?? collect();

        return view('services.create', compact('productCategories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify ownership of product category
        $productCategory = ProductCategory::find($request->product_category_id);
        if (!$productCategory) {
            abort(404);
        }
        if ($productCategory->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->only(['product_category_id', 'name', 'description', 'price']);
        $data['branch_id'] = $this->getCurrentBranchId();
        $data['user_id'] = Auth::id();

        Service::create($data);

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    public function show(Service $service)
    {
        $branchId = $this->getCurrentBranchId();
        if ($service->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to service');
        }
        return view('services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $branchId = $this->getCurrentBranchId();
        if ($service->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to service');
        }
        $productCategories = Auth::user()->productCategories()->get() ?? collect();
        return view('services.edit', compact('service', 'productCategories'));
    }

    public function update(Request $request, Service $service)
    {
        $branchId = $this->getCurrentBranchId();
        if ($service->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to service');
        }

        $validator = Validator::make($request->all(), [
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify ownership of product category
        $productCategory = ProductCategory::find($request->product_category_id);
        if ($productCategory->user_id !== Auth::id()) {
            abort(403);
        }

        $service->update($request->all());

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $branchId = $this->getCurrentBranchId();
        if ($service->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to service');
        }

        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }

    // DEBUG: Show all services without filtering
    public function debugIndex()
    {
        $allServices = Service::with(['productCategory', 'branch', 'user'])->get();

        return view('services.debug', compact('allServices'));
    }
}