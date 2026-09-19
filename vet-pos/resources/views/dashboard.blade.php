@extends('layouts.app')

@section('title', 'Dashboard - VetPOS')

@section('page-title')
    <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
@endsection

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Today's Sales</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">&#8369;{{ number_format($todayRevenue, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Transactions Today</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $todayCount }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Vet Services Today</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $todayVetCount ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Product Sales Today</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $todayProductCount ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Sales by Payment Method</h3>
            @if ($paymentBreakdown->count())
                <div class="space-y-3">
                    @foreach ($paymentBreakdown as $pb)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $pb->payment_method) }}</span>
                            <div class="text-right">
                                <span class="text-sm font-medium text-gray-900">&#8369;{{ number_format($pb->total, 2) }}</span>
                                <span class="text-xs text-gray-400 ml-2">({{ $pb->count }})</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400">No sales today.</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Low Stock Products</h3>
            @if ($lowStockProducts->count())
                <div class="space-y-2">
                    @foreach ($lowStockProducts as $product)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-700 truncate">{{ $product->name }}</span>
                            <span class="text-red-600 font-medium ml-2 whitespace-nowrap">{{ $product->stock }} left</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400">All products well-stocked.</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Expiring Soon (30 days)</h3>
            @if ($expiringProducts->count())
                <div class="space-y-2">
                    @foreach ($expiringProducts as $product)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-700 truncate">{{ $product->name }}</span>
                            <span class="text-amber-600 font-medium ml-2 whitespace-nowrap">{{ $product->expiration_date->format('M d, Y') }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400">No products expiring soon.</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900">Recent Transactions</h3>
            <a href="{{ route('admin.sales.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800">View all</a>
        </div>
        @if ($recentTransactions->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-left">
                        <tr>
                            <th class="px-5 py-3 font-medium text-gray-500">Invoice</th>
                            <th class="px-5 py-3 font-medium text-gray-500">Date</th>
                            <th class="px-5 py-3 font-medium text-gray-500">Cashier</th>
                            <th class="px-5 py-3 font-medium text-gray-500">Owner</th>
                            <th class="px-5 py-3 font-medium text-gray-500">Method</th>
                            <th class="px-5 py-3 font-medium text-gray-500 text-right">Total</th>
                            <th class="px-5 py-3 font-medium text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($recentTransactions as $sale)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3">
                                    <a href="{{ route('admin.sales.show', $sale) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">{{ $sale->invoice_number }}</a>
                                </td>
                                <td class="px-5 py-3 text-gray-600">{{ $sale->created_at->format('M d, g:i A') }}</td>
                                <td class="px-5 py-3 text-gray-600">{{ $sale->user->name ?? '-' }}</td>
                                <td class="px-5 py-3 text-gray-600">{{ $sale->owner->full_name ?? '-' }}</td>
                                <td class="px-5 py-3 text-gray-600 capitalize">{{ str_replace('_', ' ', $sale->payment_method) }}</td>
                                <td class="px-5 py-3 text-right font-medium text-gray-900">&#8369;{{ number_format($sale->total, 2) }}</td>
                                <td class="px-5 py-3">
                                    <x-status-badge :status="$sale->status" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-400 text-center py-8">No recent transactions.</p>
        @endif
    </div>
</div>
@endsection
