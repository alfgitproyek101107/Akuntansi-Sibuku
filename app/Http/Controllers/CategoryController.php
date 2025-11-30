<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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
        $branchId = $this->getCurrentBranchId();
        $categories = Category::where('branch_id', $branchId)
            ->withCount('transactions')
            ->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['name', 'type']);
        $data['branch_id'] = $this->getCurrentBranchId();
        $data['user_id'] = Auth::id();

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $branchId = $this->getCurrentBranchId();
        if ($category->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to category');
        }
        $transactions = $category->transactions()->with('account')->orderBy('date', 'desc')->get();
        return view('categories.show', compact('category', 'transactions'));
    }

    public function edit(Category $category)
    {
        $branchId = $this->getCurrentBranchId();
        if ($category->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to category');
        }
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $branchId = $this->getCurrentBranchId();
        if ($category->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to category');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category->update($request->only(['name', 'type']));

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $branchId = $this->getCurrentBranchId();
        if ($category->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to category');
        }

        // Check if category has transactions
        if ($category->transactions()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with existing transactions.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}