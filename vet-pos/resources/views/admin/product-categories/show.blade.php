@extends('layouts.app')
@section('title', $productCategory->name . ' - VetPOS')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.product-categories.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back</a>
    <div class="flex items-center justify-between mt-2">
        <h1 class="text-2xl font-bold">{{ $productCategory->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.product-categories.edit', $productCategory) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Edit</a>
            <form method="POST" action="{{ route('admin.product-categories.destroy', $productCategory) }}" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">Delete</button>
            </form>
        </div>
    </div>
</div>
<div class="bg-white rounded-lg border border-gray-200">
    <div class="px-4 py-3 border-b border-gray-200 font-semibold">Products in this Category ({{ $productCategory->products->count() }})</div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr><th class="text-left px-4 py-2">SKU</th><th class="text-left px-4 py-2">Name</th><th class="text-right px-4 py-2">Price</th><th class="text-right px-4 py-2">Stock</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($productCategory->products as $p)
                <tr>
                    <td class="px-4 py-2 font-mono text-xs">{{ $p->sku }}</td>
                    <td class="px-4 py-2"><a href="{{ route('admin.products.show', $p) }}" class="text-blue-600 hover:underline">{{ $p->name }}</a></td>
                    <td class="px-4 py-2 text-right">₱{{ number_format($p->selling_price, 2) }}</td>
                    <td class="px-4 py-2 text-right">{{ $p->stock }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-4 text-center text-gray-400">No products.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
