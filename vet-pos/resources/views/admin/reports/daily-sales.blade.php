@extends('layouts.app')
@section('title', 'Daily Sales - VetPOS')
@section('content')
<h1 class="text-2xl font-bold mb-6">Daily Sales Report</h1>
<div class="bg-white rounded-lg border border-gray-200 mb-6 p-4">
    <form method="GET" class="flex gap-2 items-center">
        <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
        <button class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">View</button>
        <span class="ml-4 text-sm text-gray-500">Total: <strong>₱{{ number_format($total, 2) }}</strong> | {{ $count }} transactions</span>
    </form>
</div>
<div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr><th class="text-left px-4 py-2">Invoice</th><th class="text-left px-4 py-2">Time</th><th class="text-left px-4 py-2">Cashier</th><th class="text-left px-4 py-2">Customer</th><th class="text-right px-4 py-2">Total</th><th class="text-center px-4 py-2">Payment</th><th class="text-center px-4 py-2">Status</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($sales as $sale)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2"><a href="{{ route('admin.sales.show', $sale) }}" class="text-blue-600 hover:underline font-mono text-xs">{{ $sale->invoice_number }}</a></td>
                <td class="px-4 py-2 text-gray-500">{{ $sale->created_at->format('g:i A') }}</td>
                <td class="px-4 py-2">{{ $sale->user->name }}</td>
                <td class="px-4 py-2">{{ $sale->owner->full_name ?? 'Walk-in' }}</td>
                <td class="px-4 py-2 text-right font-medium">₱{{ number_format($sale->total, 2) }}</td>
                <td class="px-4 py-2 text-center capitalize">{{ str_replace('_', ' ', $sale->payment_method) }}</td>
                <td class="px-4 py-2 text-center"><span class="text-xs px-2 py-0.5 rounded-full {{ $sale->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($sale->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No sales for this date.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
