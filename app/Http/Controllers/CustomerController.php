<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        // BranchScope will automatically filter customers by current branch
        $customers = Customer::orderBy('name')->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Prepare data with branch_id
        $data = $request->all();
        $data['branch_id'] = session('active_branch') ?? Auth::user()->branch_id ?? null;

        Auth::user()->customers()->create($data);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        // BranchScope handles branch access, but we keep user ownership for additional security
        if ($customer->user_id !== Auth::id()) {
            abort(403);
        }
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        // BranchScope handles branch access, but we keep user ownership for additional security
        if ($customer->user_id !== Auth::id()) {
            abort(403);
        }
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        // BranchScope handles branch access, but we keep user ownership for additional security
        if ($customer->user_id !== Auth::id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Ensure branch_id remains consistent
        $data = $request->all();
        $data['branch_id'] = $customer->branch_id; // Keep existing branch_id

        $customer->update($data);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        // BranchScope handles branch access, but we keep user ownership for additional security
        if ($customer->user_id !== Auth::id()) {
            abort(403);
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}