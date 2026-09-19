<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $sales = Sale::query()
            ->dateRange($request->from, $request->to)
            ->paymentMethod($request->payment_method)
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->user_id, fn ($q) => $q->where('user_id', $request->user_id))
            ->with(['user', 'owner'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $cashiers = \App\Models\User::orderBy('name')->get();

        return view('admin.sales.index', compact('sales', 'cashiers'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['items.product', 'items.service', 'user', 'owner', 'pet']);

        return view('admin.sales.show', compact('sale'));
    }

    public function voidSale(Sale $sale)
    {
        if ($sale->status !== 'completed') {
            return back()->withErrors(['status' => 'Only completed sales can be voided.']);
        }

        DB::transaction(function () use ($sale) {
            $old = $sale->toArray();

            foreach ($sale->items as $item) {
                if ($item->product_id && $item->type === 'product') {
                    $product = $item->product;
                    $previousStock = $product->stock;
                    $newStock = $previousStock + $item->quantity;

                    $product->update(['stock' => $newStock]);

                    InventoryMovement::create([
                        'product_id' => $product->id,
                        'type' => 'void_return',
                        'quantity' => $item->quantity,
                        'previous_stock' => $previousStock,
                        'new_stock' => $newStock,
                        'reason' => "Void of sale #{$sale->invoice_number}",
                        'user_id' => auth()->id(),
                        'sale_id' => $sale->id,
                    ]);
                }
            }

            $sale->update(['status' => 'voided']);

            AuditLogHelper::log('voided', $sale, $old, ['status' => 'voided']);
        });

        return back()->with('success', 'Sale voided successfully. Stock has been restored.');
    }

    public function refund(Sale $sale)
    {
        if ($sale->status !== 'completed') {
            return back()->withErrors(['status' => 'Only completed sales can be refunded.']);
        }

        DB::transaction(function () use ($sale) {
            $old = $sale->toArray();

            foreach ($sale->items as $item) {
                if ($item->product_id && $item->type === 'product') {
                    $product = $item->product;
                    $previousStock = $product->stock;
                    $newStock = $previousStock + $item->quantity;

                    $product->update(['stock' => $newStock]);

                    InventoryMovement::create([
                        'product_id' => $product->id,
                        'type' => 'refund',
                        'quantity' => $item->quantity,
                        'previous_stock' => $previousStock,
                        'new_stock' => $newStock,
                        'reason' => "Refund of sale #{$sale->invoice_number}",
                        'user_id' => auth()->id(),
                        'sale_id' => $sale->id,
                    ]);
                }
            }

            $sale->update(['status' => 'refunded']);

            AuditLogHelper::log('refunded', $sale, $old, ['status' => 'refunded']);
        });

        return back()->with('success', 'Sale refunded successfully. Stock has been restored.');
    }
}
