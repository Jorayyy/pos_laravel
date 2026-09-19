<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $movements = InventoryMovement::query()
            ->when($request->product_id, fn ($q) => $q->where('product_id', $request->product_id))
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->with(['product', 'user'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $products = Product::orderBy('name')->get();

        return view('admin.inventory.index', compact('movements', 'products'));
    }

    public function stockIn(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::findOrFail($validated['product_id']);
            $previousStock = $product->stock;
            $newStock = $previousStock + $validated['quantity'];

            $product->update(['stock' => $newStock]);

            InventoryMovement::create([
                'product_id' => $product->id,
                'type' => 'stock_in',
                'quantity' => $validated['quantity'],
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reason' => $validated['reason'],
                'user_id' => auth()->id(),
            ]);

            AuditLogHelper::log('stock_in', $product, ['stock' => $previousStock], ['stock' => $newStock]);
        });

        return back()->with('success', 'Stock added successfully.');
    }

    public function stockOut(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Insufficient stock.']);
        }

        DB::transaction(function () use ($product, $validated) {
            $previousStock = $product->stock;
            $newStock = $previousStock - $validated['quantity'];

            $product->update(['stock' => $newStock]);

            InventoryMovement::create([
                'product_id' => $product->id,
                'type' => 'stock_out',
                'quantity' => $validated['quantity'],
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reason' => $validated['reason'],
                'user_id' => auth()->id(),
            ]);

            AuditLogHelper::log('stock_out', $product, ['stock' => $previousStock], ['stock' => $newStock]);
        });

        return back()->with('success', 'Stock deducted successfully.');
    }

    public function adjust(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'reason' => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $newStock = $product->stock + $validated['quantity'];

        if ($newStock < 0) {
            return back()->withErrors(['quantity' => 'Stock cannot go below zero.']);
        }

        DB::transaction(function () use ($product, $validated, $newStock) {
            $previousStock = $product->stock;
            $product->update(['stock' => $newStock]);

            InventoryMovement::create([
                'product_id' => $product->id,
                'type' => 'adjustment',
                'quantity' => abs($validated['quantity']),
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reason' => $validated['reason'],
                'user_id' => auth()->id(),
            ]);

            AuditLogHelper::log('stock_adjusted', $product, ['stock' => $previousStock], ['stock' => $newStock]);
        });

        return back()->with('success', 'Stock adjusted successfully.');
    }

    public function productHistory(Product $product)
    {
        $movements = $product->inventoryMovements()
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('admin.inventory.product-history', compact('product', 'movements'));
    }
}
