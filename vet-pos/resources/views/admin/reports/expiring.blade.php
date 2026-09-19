@extends('layouts.app')
@section('title', 'Expiring Products - VetPOS')
@section('content')
<h1 class="text-2xl font-bold mb-6">Expiring Products (within {{ $days }} days)</h1>
<div class="bg-white rounded-lg border border-gray-200 mb-6 p-4">
    <form method="GET" class="flex gap-2 items-center">
        <span class="text-sm text-gray-500">Days:</span>
        <select name="days" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="7" {{ $days == 7 ? 'selected' : '' }}>7 days</option>
            <option value="30" {{ $days == 30 ? 'selected' : '' }}>30 days</option>
            <option value="60" {{ $days == 60 ? 'selected' : '' }}>60 days</option>
            <option value="90" {{ $days == 90 ? 'selected' : '' }}>90 days</option>
        </select>
        <button class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">View</button>
    </form>
</div>
<div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr><th class="text-left px-4 py-2">SKU</th><th class="text-left px-4 py-2">Product</th><th class="text-left px-4 py-2">Category</th><th class="text-center px-4 py-2">Expiration</th><th class="text-right px-4 py-2">Stock</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($products as $p)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 font-mono text-xs">{{ $p->sku }}</td>
                <td class="px-4 py-2 font-medium">{{ $p->name }}</td>
                <td class="px-4 py-2 text-gray-500">{{ $p->category->name ?? '-' }}</td>
                <td class="px-4 py-2 text-center text-red-600 font-medium">{{ $p->expiration_date->format('M d, Y') }}</td>
                <td class="px-4 py-2 text-right">{{ $p->stock }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No expiring products.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
