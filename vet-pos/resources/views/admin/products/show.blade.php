@extends('layouts.app')
@section('title', $product->name . ' - VetPOS')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.products.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to Products</a>
    <div class="flex items-center justify-between mt-2">
        <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Edit</a>
            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">Delete</button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="space-y-6">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            @if($product->photo_url)
                <div class="mb-4 rounded-lg overflow-hidden border border-gray-200">
                    <img src="{{ $product->photo_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                </div>
            @endif
            <h2 class="font-semibold mb-4">Product Info</h2>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-gray-500">SKU</dt><dd class="font-mono">{{ $product->sku }}</dd></div>
                <div><dt class="text-gray-500">Barcode</dt><dd class="font-mono">{{ $product->barcode ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Category</dt><dd>{{ $product->category->name ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Brand</dt><dd>{{ $product->brand ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Unit</dt><dd>{{ $product->unit }}</dd></div>
                <div><dt class="text-gray-500">Cost Price</dt><dd>₱{{ number_format($product->cost_price, 2) }}</dd></div>
                <div><dt class="text-gray-500">Selling Price</dt><dd>₱{{ number_format($product->selling_price, 2) }}</dd></div>
                <div><dt class="text-gray-500">Stock</dt>
                    <dd class="@if($product->stock <= 0) text-red-600 font-bold @elseif($product->stock <= $product->reorder_level) text-amber-600 font-bold @endif">
                        {{ $product->stock }} {{ $product->unit }}
                    </dd>
                </div>
                <div><dt class="text-gray-500">Reorder Level</dt><dd>{{ $product->reorder_level }}</dd></div>
                <div><dt class="text-gray-500">Expiration</dt><dd>{{ $product->expiration_date ? $product->expiration_date->format('M d, Y') : '-' }}</dd></div>
                <div><dt class="text-gray-500">Status</dt><dd>{{ $product->is_active ? 'Active' : 'Inactive' }}</dd></div>
            </dl>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="font-semibold mb-4">Adjust Stock</h2>
            <form method="POST" action="{{ route('admin.products.adjust-stock', $product) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Adjustment (+/-)</label>
                    <input type="number" name="adjustment" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="+10 or -5">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Reason</label>
                    <input type="text" name="reason" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-900">Apply Adjustment</button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 font-semibold">Stock Movement History</div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-2">Date</th>
                            <th class="text-left px-4 py-2">Type</th>
                            <th class="text-right px-4 py-2">Qty</th>
                            <th class="text-right px-4 py-2">Before</th>
                            <th class="text-right px-4 py-2">After</th>
                            <th class="text-left px-4 py-2">Reason</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($product->inventoryMovements as $m)
                        <tr>
                            <td class="px-4 py-2 text-gray-500">{{ $m->created_at->format('M d, g:i A') }}</td>
                            <td class="px-4 py-2">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ in_array($m->type, ['stock_in', 'sale', 'void_return', 'refund']) ? ($m->type === 'sale' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700') : 'bg-gray-100 text-gray-700' }}">{{ $m->type }}</span>
                            </td>
                            <td class="px-4 py-2 text-right font-mono">{{ $m->quantity > 0 ? "+{$m->quantity}" : $m->quantity }}</td>
                            <td class="px-4 py-2 text-right text-gray-500">{{ $m->previous_stock }}</td>
                            <td class="px-4 py-2 text-right font-medium">{{ $m->new_stock }}</td>
                            <td class="px-4 py-2 text-gray-500">{{ $m->reason }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-4 text-center text-gray-400">No movements.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
