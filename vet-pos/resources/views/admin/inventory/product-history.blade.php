@extends('layouts.app')
@section('title', 'Stock History - ' . $product->name . ' - VetPOS')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.inventory.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to Inventory</a>
    <div class="mt-2">
        <h1 class="text-2xl font-bold">Stock History: {{ $product->name }}</h1>
        <p class="text-sm text-gray-500">Current stock: <strong>{{ $product->stock }}</strong> {{ $product->unit }}</p>
    </div>
</div>
<div class="bg-white rounded-lg border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="text-left px-4 py-2">Date</th>
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
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No movements.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $movements->links() }}</div>
</div>
@endsection
