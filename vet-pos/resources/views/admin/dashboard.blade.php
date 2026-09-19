@extends('layouts.app')
@section('title', 'Dashboard - VetPOS')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="text-sm text-gray-500">Today's Sales</div>
        <div class="text-2xl font-bold text-gray-900">₱{{ number_format($todayRevenue, 2) }}</div>
        <div class="text-xs text-gray-400">{{ $todayCount }} transactions</div>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="text-sm text-gray-500">This Month</div>
        <div class="text-2xl font-bold text-gray-900">₱{{ number_format($monthRevenue, 2) }}</div>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="text-sm text-gray-500">Low Stock Items</div>
        <div class="text-2xl font-bold text-amber-600">{{ $lowStockProducts->count() }}</div>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="text-sm text-gray-500">Expiring Soon</div>
        <div class="text-2xl font-bold text-red-600">{{ $expiringProducts->count() }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 font-semibold">Recent Transactions</div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 py-2">Invoice</th>
                            <th class="text-left px-4 py-2">Date</th>
                            <th class="text-left px-4 py-2">Cashier</th>
                            <th class="text-left px-4 py-2">Customer</th>
                            <th class="text-right px-4 py-2">Total</th>
                            <th class="text-center px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentTransactions as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 font-medium">{{ $sale->invoice_number }}</td>
                            <td class="px-4 py-2 text-gray-500">{{ $sale->created_at->format('M d, g:i A') }}</td>
                            <td class="px-4 py-2">{{ $sale->user->name }}</td>
                            <td class="px-4 py-2">{{ $sale->owner->full_name ?? 'Walk-in' }}</td>
                            <td class="px-4 py-2 text-right font-medium">₱{{ number_format($sale->total, 2) }}</td>
                            <td class="px-4 py-2 text-center">
                                @if($sale->status === 'completed')
                                    <span class="inline-block px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700">Completed</span>
                                @elseif($sale->status === 'voided')
                                    <span class="inline-block px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-700">Voided</span>
                                @else
                                    <span class="inline-block px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-700">Refunded</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No transactions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 font-semibold">Payment Breakdown</div>
            <div class="p-4 space-y-2">
                @forelse($paymentBreakdown as $pb)
                <div class="flex justify-between text-sm">
                    <span class="capitalize">{{ str_replace('_', ' ', $pb->payment_method) }}</span>
                    <span class="font-medium">₱{{ number_format($pb->total, 2) }} ({{ $pb->count }})</span>
                </div>
                @empty
                <p class="text-sm text-gray-400">No data.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 font-semibold">Low Stock Products</div>
            <div class="p-4 space-y-2">
                @forelse($lowStockProducts as $product)
                <div class="flex justify-between text-sm">
                    <span>{{ $product->name }}</span>
                    <span class="text-amber-600 font-medium">{{ $product->stock }} left</span>
                </div>
                @empty
                <p class="text-sm text-gray-400">All products well stocked.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
