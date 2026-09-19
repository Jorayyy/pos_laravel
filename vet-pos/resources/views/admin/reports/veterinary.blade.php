@extends('layouts.app')
@section('title', 'Veterinary Report - VetPOS')
@section('content')
<h1 class="text-2xl font-bold mb-6">Veterinary Services Report</h1>
<div class="bg-white rounded-lg border border-gray-200 mb-6 p-4">
    <form method="GET" class="flex gap-2 items-center">
        <span class="text-sm text-gray-500">From</span>
        <input type="date" name="from" value="{{ request('from') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
        <span class="text-sm text-gray-500">To</span>
        <input type="date" name="to" value="{{ request('to') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
        <button class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Filter</button>
    </form>
</div>
<div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr><th class="text-left px-4 py-2">Invoice</th><th class="text-left px-4 py-2">Date</th><th class="text-left px-4 py-2">Pet</th><th class="text-left px-4 py-2">Owner</th><th class="text-left px-4 py-2">Services</th><th class="text-right px-4 py-2">Total</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($sales as $sale)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2"><a href="{{ route('admin.sales.show', $sale) }}" class="text-blue-600 hover:underline font-mono text-xs">{{ $sale->invoice_number }}</a></td>
                <td class="px-4 py-2 text-gray-500">{{ $sale->created_at->format('M d, Y') }}</td>
                <td class="px-4 py-2">{{ $sale->pet->name ?? '-' }}</td>
                <td class="px-4 py-2">{{ $sale->owner->full_name ?? '-' }}</td>
                <td class="px-4 py-2 text-gray-500">{{ $sale->items->where('type', 'service')->pluck('name')->join(', ') }}</td>
                <td class="px-4 py-2 text-right font-medium">₱{{ number_format($sale->total, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No veterinary transactions found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4 text-right text-sm text-gray-500">Total Revenue: <strong>₱{{ number_format($totalRevenue, 2) }}</strong></div>
@endsection
