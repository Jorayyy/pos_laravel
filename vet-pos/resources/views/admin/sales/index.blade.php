@extends('layouts.app')
@section('title', 'Sales - VetPOS')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Sales & Transactions</h1>
</div>
<div class="bg-white rounded-lg border border-gray-200">
    <div class="p-4 border-b border-gray-200">
        <form method="GET" class="flex gap-2 flex-wrap">
            <input type="date" name="from" value="{{ request('from') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <span class="self-center text-sm text-gray-500">to</span>
            <input type="date" name="to" value="{{ request('to') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Status</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="voided" {{ request('status') === 'voided' ? 'selected' : '' }}>Voided</option>
                <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>
            <select name="payment_method" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Payment</option>
                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="gcash" {{ request('payment_method') === 'gcash' ? 'selected' : '' }}>GCash</option>
                <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                <option value="other" {{ request('payment_method') === 'other' ? 'selected' : '' }}>Other</option>
            </select>
            <select name="user_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Cashiers</option>
                @foreach($cashiers as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
            <button class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Filter</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="text-left px-4 py-2">Invoice</th>
                    <th class="text-left px-4 py-2">Date</th>
                    <th class="text-left px-4 py-2">Cashier</th>
                    <th class="text-left px-4 py-2">Customer</th>
                    <th class="text-right px-4 py-2">Total</th>
                    <th class="text-center px-4 py-2">Payment</th>
                    <th class="text-center px-4 py-2">Status</th>
                    <th class="text-right px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($sales as $sale)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $sale->invoice_number }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $sale->created_at->format('M d, Y g:i A') }}</td>
                    <td class="px-4 py-3">{{ $sale->user->name }}</td>
                    <td class="px-4 py-3">{{ $sale->owner->full_name ?? 'Walk-in' }}</td>
                    <td class="px-4 py-3 text-right font-medium">₱{{ number_format($sale->total, 2) }}</td>
                    <td class="px-4 py-3 text-center capitalize">{{ str_replace('_', ' ', $sale->payment_method) }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $sale->status === 'completed' ? 'bg-green-100 text-green-700' : ($sale->status === 'voided' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($sale->status) }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.sales.show', $sale) }}" class="text-blue-600 hover:underline text-xs">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">No sales found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $sales->links() }}</div>
</div>
@endsection
