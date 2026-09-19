@extends('layouts.app')
@section('title', 'Products - VetPOS')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Products</h1>
    <a href="{{ route('admin.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ Add Product</a>
</div>
<div class="bg-white rounded-lg border border-gray-200">
    <div class="p-4 border-b border-gray-200">
        <form method="GET" class="flex gap-2 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, SKU, barcode..." class="flex-1 min-w-[200px] px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <select name="category_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <label class="flex items-center gap-1 text-sm"><input type="checkbox" name="active" value="1" {{ request('active') ? 'checked' : '' }}> Active Only</label>
            <button class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Filter</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="text-left px-4 py-2">SKU</th>
                    <th class="text-left px-4 py-2">Name</th>
                    <th class="text-left px-4 py-2">Category</th>
                    <th class="text-right px-4 py-2">Price</th>
                    <th class="text-right px-4 py-2">Stock</th>
                    <th class="text-center px-4 py-2">Status</th>
                    <th class="text-right px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $product->sku }}</td>
                    <td class="px-4 py-3 font-medium">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg border border-gray-200 overflow-hidden bg-gray-50 flex-shrink-0">
                                @if($product->photo_url)
                                    <img src="{{ $product->photo_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 hover:underline">{{ $product->name }}</a>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-right">₱{{ number_format($product->selling_price, 2) }}</td>
                    <td class="px-4 py-3 text-right">
                        @if($product->stock <= 0)
                            <span class="text-red-600 font-medium">0</span>
                        @elseif($product->stock <= $product->reorder_level)
                            <span class="text-amber-600 font-medium">{{ $product->stock }}</span>
                        @else
                            {{ $product->stock }}
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($product->is_active)
                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">Active</span>
                        @else
                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $products->links() }}</div>
</div>
@endsection
