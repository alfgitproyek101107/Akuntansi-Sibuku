<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockMovementController extends Controller
{
    public function index()
    {
        // BranchScope will automatically filter stock movements by current branch
        $stockMovements = StockMovement::with('product')->orderBy('date', 'desc')->get();
        return view('stock_movements.index', compact('stockMovements'));
    }

    public function create()
    {
        // BranchScope will automatically filter products by current branch
        $products = Product::orderBy('name')->get();
        return view('stock_movements.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify ownership of product
        $product = Product::find($request->product_id);
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        DB::transaction(function () use ($request, $product) {
            // Prepare data with branch_id
            $data = $request->all();
            $data['branch_id'] = session('active_branch') ?? Auth::user()->branch_id ?? null;

            // Create stock movement
            $stockMovement = Auth::user()->stockMovements()->create($data);

            // Adjust product stock
            if ($request->type === 'in') {
                $product->increment('stock_quantity', $request->quantity);
            } elseif ($request->type === 'out') {
                if ($product->stock_quantity < $request->quantity) {
                    throw new \Exception('Insufficient stock quantity.');
                }
                $product->decrement('stock_quantity', $request->quantity);
            }
            // For adjustment, just record, stock already adjusted manually
        });

        return redirect()->route('stock-movements.index')->with('success', 'Stock movement recorded successfully.');
    }

    public function show(StockMovement $stockMovement)
    {
        // BranchScope handles branch access, but we keep user ownership for additional security
        if ($stockMovement->user_id !== Auth::id()) {
            abort(403);
        }
        return view('stock_movements.show', compact('stockMovement'));
    }

    public function edit(StockMovement $stockMovement)
    {
        // BranchScope handles branch access, but we keep user ownership for additional security
        if ($stockMovement->user_id !== Auth::id()) {
            abort(403);
        }

        // BranchScope will automatically filter products by current branch
        $products = Product::orderBy('name')->get();

        return view('stock_movements.edit', compact('stockMovement', 'products'));
    }

    public function update(Request $request, StockMovement $stockMovement)
    {
        if ($stockMovement->user_id !== Auth::id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify ownership of product
        $product = Product::find($request->product_id);
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        DB::transaction(function () use ($request, $stockMovement, $product) {
            // Revert old stock adjustment
            if ($stockMovement->type === 'in') {
                $product->decrement('stock_quantity', $stockMovement->quantity);
            } elseif ($stockMovement->type === 'out') {
                $product->increment('stock_quantity', $stockMovement->quantity);
            }

            // Update stock movement
            $stockMovement->update($request->all());

            // Apply new stock adjustment
            if ($request->type === 'in') {
                $product->increment('stock_quantity', $request->quantity);
            } elseif ($request->type === 'out') {
                if ($product->stock_quantity < $request->quantity) {
                    throw new \Exception('Insufficient stock quantity.');
                }
                $product->decrement('stock_quantity', $request->quantity);
            }
        });

        return redirect()->route('stock-movements.index')->with('success', 'Stock movement updated successfully.');
    }

    public function destroy(StockMovement $stockMovement)
    {
        // BranchScope handles branch access, but we keep user ownership for additional security
        if ($stockMovement->user_id !== Auth::id()) {
            abort(403);
        }

        DB::transaction(function () use ($stockMovement) {
            // Revert stock adjustment
            if ($stockMovement->type === 'in') {
                $stockMovement->product->decrement('stock_quantity', $stockMovement->quantity);
            } elseif ($stockMovement->type === 'out') {
                $stockMovement->product->increment('stock_quantity', $stockMovement->quantity);
            }

            $stockMovement->delete();
        });

        return redirect()->route('stock-movements.index')->with('success', 'Stock movement deleted successfully.');
    }
}