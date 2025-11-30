<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $productCategories = Auth::user()->productCategories()->get();
        return view('product_categories.index', compact('productCategories'));
    }

    public function create()
    {
        return view('product_categories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:product_categories,name,NULL,id,user_id,' . Auth::id(),
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Auth::user()->productCategories()->create($request->only(['name', 'description']));

        return redirect()->route('product-categories.index')->with('success', 'Kategori produk berhasil dibuat.');
    }

    public function show($id)
    {
        $productCategory = Auth::user()->productCategories()->findOrFail($id);
        $products = $productCategory->products()->get();
        $services = $productCategory->services()->get();
        return view('product_categories.show', compact('productCategory', 'products', 'services'));
    }

    public function edit($id)
    {
        $productCategory = Auth::user()->productCategories()->findOrFail($id);

        // Get last transaction for products in this category without causing ambiguous column error
        $lastTransaction = \App\Models\Transaction::whereHas('product', function($query) use ($productCategory) {
            $query->where('product_category_id', $productCategory->id);
        })
        ->orderBy('date', 'desc')
        ->first();

        return view('product_categories.edit', compact('productCategory', 'lastTransaction'));
    }

    public function update(Request $request, $id)
    {
        $productCategory = Auth::user()->productCategories()->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:product_categories,name,' . $id . ',id,user_id,' . Auth::id(),
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $productCategory->update($request->only(['name', 'description']));

        return redirect()->route('product-categories.show', $productCategory)->with('success', 'Kategori produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $productCategory = Auth::user()->productCategories()->findOrFail($id);

        // Check if category has products or services
        if ($productCategory->products()->count() > 0 || $productCategory->services()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete product category with existing products or services.');
        }

        $productCategory->delete();

        return redirect()->route('product-categories.index')->with('success', 'Kategori produk berhasil dihapus.');
    }
}