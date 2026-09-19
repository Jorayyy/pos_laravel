<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::query()
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->withCount('products')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.product-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.product-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        DB::transaction(function () use ($validated) {
            $category = ProductCategory::create($validated);
            AuditLogHelper::log('created', $category, null, $validated);
        });

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Product category created successfully.');
    }

    public function show(ProductCategory $productCategory)
    {
        $productCategory->load('products');

        return view('admin.product-categories.show', compact('productCategory'));
    }

    public function edit(ProductCategory $productCategory)
    {
        return view('admin.product-categories.edit', compact('productCategory'));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        DB::transaction(function () use ($productCategory, $validated) {
            $old = $productCategory->only(array_keys($validated));
            $productCategory->update($validated);
            AuditLogHelper::log('updated', $productCategory, $old, $validated);
        });

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Product category updated successfully.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        DB::transaction(function () use ($productCategory) {
            AuditLogHelper::log('deleted', $productCategory, $productCategory->toArray(), null);
            $productCategory->delete();
        });

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Product category deleted successfully.');
    }
}
