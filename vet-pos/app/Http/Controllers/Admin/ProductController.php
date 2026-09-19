<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->search($request->search)
            ->with('category');

        if ($request->category_id) {
            $query->where('product_category_id', $request->category_id);
        }

        if ($request->has('active')) {
            $query->active();
        }

        $sortField = $request->sort ?? 'created_at';
        $sortDir = $request->dir ?? 'desc';
        $allowed = ['name', 'sku', 'selling_price', 'stock', 'created_at'];

        if (in_array($sortField, $allowed)) {
            $query->orderBy($sortField, $sortDir);
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = ProductCategory::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = ProductCategory::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:50|unique:products,sku',
            'barcode' => 'nullable|string|max:50|unique:products,barcode',
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'description' => 'nullable|string',
            'brand' => 'nullable|string|max:255',
            'unit' => 'required|string|max:20',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'expiration_date' => 'nullable|date|after:today',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('products', 'public');
        }

        DB::transaction(function () use ($validated) {
            $product = Product::create($validated);

            if ($validated['stock'] > 0) {
                InventoryMovement::create([
                    'product_id' => $product->id,
                    'type' => 'initial',
                    'quantity' => $validated['stock'],
                    'previous_stock' => 0,
                    'new_stock' => $validated['stock'],
                    'reason' => 'Initial stock',
                    'user_id' => auth()->id(),
                ]);
            }

            AuditLogHelper::log('created', $product, null, $validated);
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'inventoryMovements' => fn ($q) => $q->with('user')->latest()->limit(20)]);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|max:50|unique:products,barcode,' . $product->id,
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'description' => 'nullable|string',
            'brand' => 'nullable|string|max:255',
            'unit' => 'required|string|max:20',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'expiration_date' => 'nullable|date|after:today',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($product->photo && \Storage::disk('public')->exists($product->photo)) {
                \Storage::disk('public')->delete($product->photo);
            }
            $validated['photo'] = $request->file('photo')->store('products', 'public');
        }

        DB::transaction(function () use ($product, $validated) {
            $old = $product->only(array_keys($validated));
            $product->update($validated);
            AuditLogHelper::log('updated', $product, $old, $validated);
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            AuditLogHelper::log('deleted', $product, $product->toArray(), null);
            $product->delete();
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function adjustStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'adjustment' => 'required|integer',
            'reason' => 'required|string|max:255',
        ]);

        $adjustment = (int) $validated['adjustment'];
        $newStock = $product->stock + $adjustment;

        if ($newStock < 0) {
            return back()->withErrors(['adjustment' => 'Stock cannot go below zero.']);
        }

        DB::transaction(function () use ($product, $adjustment, $newStock, $validated) {
            $previousStock = $product->stock;
            $product->update(['stock' => $newStock]);

            InventoryMovement::create([
                'product_id' => $product->id,
                'type' => $adjustment > 0 ? 'stock_in' : 'stock_out',
                'quantity' => abs($adjustment),
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reason' => $validated['reason'],
                'user_id' => auth()->id(),
            ]);

            AuditLogHelper::log('stock_adjusted', $product, ['stock' => $previousStock], ['stock' => $newStock]);
        });

        return back()->with('success', 'Stock adjusted successfully.');
    }
}
