@extends('layouts.app')
@section('title', 'Inventory - VetPOS')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Inventory Management</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="font-semibold mb-4">Stock In</h2>
        <form method="POST" action="{{ route('admin.inventory.stock-in') }}" class="space-y-3">
            @csrf
            <select name="product_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">Select product...</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->name }} (SKU: {{ $p->sku }})</option>
                @endforeach
            </select>
            <input type="number" name="quantity" min="1" required placeholder="Quantity" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <input type="text" name="reason" required placeholder="Reason" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <button class="w-full bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">Stock In</button>
        </form>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="font-semibold mb-4">Stock Out</h2>
        <form method="POST" action="{{ route('admin.inventory.stock-out') }}" class="space-y-3">
            @csrf
            <select name="product_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">Select product...</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->stock }} in stock)</option>
                @endforeach
            </select>
            <input type="number" name="quantity" min="1" required placeholder="Quantity" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <input type="text" name="reason" required placeholder="Reason" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <button class="w-full bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">Stock Out</button>
        </form>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="font-semibold mb-4">Adjust Stock</h2>
        <form method="POST" action="{{ route('admin.inventory.adjust') }}" class="space-y-3">
            @csrf
            <select name="product_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">Select product...</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->stock }} in stock)</option>
                @endforeach
            </select>
            <input type="number" name="quantity" required placeholder="+/- Quantity (e.g. -3 or +5)" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <input type="text" name="reason" required placeholder="Reason" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <button class="w-full bg-gray-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-900">Adjust</button>
        </form>
    </div>
</div>

<div class="bg-white rounded-lg border border-gray-200">
    <div class="px-4 py-3 border-b border-gray-200 font-semibold">Stock Movement History</div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="text-left px-4 py-2">Date</th>
                    <th class="text-left px-4 py-2">Product</th>
                    <th class="text-left px-4 py-2">Type</th>
                    <th class="text-right px-4 py-2">Qty</th>
                    <th class="text-right px-4 py-2">Before</th>
                    <th class="text-right px-4 py-2">After</th>
                    <th class="text-left px-4 py-2">Reason</th>
                    <th class="text-left px-4 py-2">User</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($movements as $m)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-500">{{ $m->created_at->format('M d, g:i A') }}</td>
                    <td class="px-4 py-2 font-medium">{{ $m->product->name ?? 'Deleted' }}</td>
                    <td class="px-4 py-2">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $m->type === 'sale' ? 'bg-red-100 text-red-700' : ($m->type === 'stock_in' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700') }}">{{ $m->type }}</span>
                    </td>
                    <td class="px-4 py-2 text-right font-mono">{{ $m->quantity }}</td>
                    <td class="px-4 py-2 text-right text-gray-500">{{ $m->previous_stock }}</td>
                    <td class="px-4 py-2 text-right font-medium">{{ $m->new_stock }}</td>
                    <td class="px-4 py-2 text-gray-500">{{ $m->reason }}</td>
                    <td class="px-4 py-2 text-gray-500">{{ $m->user->name ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">No movements.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $movements->links() }}</div>
</div>
@endsection
