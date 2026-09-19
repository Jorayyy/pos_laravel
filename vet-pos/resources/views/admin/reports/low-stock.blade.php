@extends('layouts.app')
@section('title', 'Low Stock Report - VetPOS')
@section('content')
<h1 class="text-2xl font-bold mb-6">Low Stock Products</h1>
<div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr><th class="text-left px-4 py-2">SKU</th><th class="text-left px-4 py-2">Product</th><th class="text-left px-4 py-2">Category</th><th class="text-right px-4 py-2">Current Stock</th><th class="text-right px-4 py-2">Reorder Level</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($products as $p)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 font-mono text-xs">{{ $p->sku }}</td>
                <td class="px-4 py-2 font-medium">{{ $p->name }}</td>
                <td class="px-4 py-2 text-gray-500">{{ $p->category->name ?? '-' }}</td>
                <td class="px-4 py-2 text-right text-red-600 font-bold">{{ $p->stock }}</td>
                <td class="px-4 py-2 text-right">{{ $p->reorder_level }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">All products are well stocked.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
