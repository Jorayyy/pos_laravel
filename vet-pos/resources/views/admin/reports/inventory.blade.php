@extends('layouts.app')
@section('title', 'Inventory Report - VetPOS')
@section('content')
<h1 class="text-2xl font-bold mb-6">Inventory Report</h1>
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="text-sm text-gray-500">Total Stock Value (Cost)</div>
        <div class="text-2xl font-bold">₱{{ number_format($totalValue, 2) }}</div>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="text-sm text-gray-500">Total Retail Value</div>
        <div class="text-2xl font-bold">₱{{ number_format($totalRetail, 2) }}</div>
    </div>
</div>
<div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr><th class="text-left px-4 py-2">SKU</th><th class="text-left px-4 py-2">Product</th><th class="text-left px-4 py-2">Category</th><th class="text-right px-4 py-2">Cost</th><th class="text-right px-4 py-2">Price</th><th class="text-right px-4 py-2">Stock</th><th class="text-right px-4 py-2">Value (Cost)</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($products as $p)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 font-mono text-xs">{{ $p->sku }}</td>
                <td class="px-4 py-2 font-medium">{{ $p->name }}</td>
                <td class="px-4 py-2 text-gray-500">{{ $p->category->name ?? '-' }}</td>
                <td class="px-4 py-2 text-right">₱{{ number_format($p->cost_price, 2) }}</td>
                <td class="px-4 py-2 text-right">₱{{ number_format($p->selling_price, 2) }}</td>
                <td class="px-4 py-2 text-right">{{ $p->stock }}</td>
                <td class="px-4 py-2 text-right">₱{{ number_format($p->stock * $p->cost_price, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No products.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
