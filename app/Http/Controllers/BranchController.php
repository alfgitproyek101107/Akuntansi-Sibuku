<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = Branch::paginate(10);
        return view('branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Branch::create($request->all());

        return redirect()->route('branches.index')->with('success', 'Cabang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        return view('branches.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $branch->update($request->all());

        return redirect()->route('branches.index')->with('success', 'Cabang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('branches.index')->with('success', 'Cabang berhasil dihapus');
    }

    /**
     * Switch active branch for current user
     */
    public function switch($branchId)
    {
        $user = auth()->user();

        // Handle "Semua Cabang" (all branches)
        if ($branchId == 0) {
            if ($user->userRole->name !== 'super_admin') {
                abort(403, 'Unauthorized');
            }
            session()->forget('active_branch');
            return redirect()->back()->with('success', 'Berhasil beralih ke: Semua Cabang');
        }

        // Find the branch
        $branch = Branch::findOrFail($branchId);

        // Check if user has access to this branch
        if ($user->userRole->name !== 'super_admin' && $user->branch && $user->branch->id !== $branch->id) {
            abort(403, 'Unauthorized');
        }

        // Store active branch in session
        session(['active_branch' => $branch->id]);

        return redirect()->back()->with('success', 'Berhasil beralih ke cabang: ' . $branch->name);
    }

    /**
     * Show branch selection page
     */
    public function select()
    {
        $user = auth()->user();

        // Check if branch is already selected
        if (session()->has('active_branch')) {
            return redirect()->route('dashboard');
        }

        // Get user's accessible branches using the new enterprise logic
        $branches = \App\Http\Middleware\BranchIsolation::getUserBranches($user);

        // If user only has one branch, auto-select it
        if ($branches->count() === 1) {
            $branch = $branches->first();
            session(['active_branch' => $branch->id]);
            return redirect()->route('dashboard')->with('success', 'Cabang aktif: ' . $branch->name);
        }

        return view('branches.select', compact('branches'));
    }

    /**
     * Set selected branch for current session
     */
    public function setBranch(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
        ]);

        $user = auth()->user();
        $branchId = $request->branch_id;

        // Validate user has access to this branch
        $hasAccess = \App\Http\Middleware\BranchIsolation::getUserBranches($user)
            ->pluck('id')
            ->contains($branchId);

        if (!$hasAccess) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke cabang ini');
        }

        // Set branch in session
        session(['active_branch' => $branchId]);

        $branch = Branch::find($branchId);

        return redirect()->route('dashboard')->with('success', 'Cabang aktif: ' . $branch->name);
    }

    /**
     * Get available branches for current user
     */
    public function available()
    {
        $user = auth()->user();

        if ($user->userRole->name === 'super_admin') {
            $branches = Branch::all();
        } else {
            // Users can access their assigned branch or all branches if no specific branch assigned
            $branches = $user->branch ? collect([$user->branch]) : Branch::all();
        }

        return response()->json($branches);
    }
}
