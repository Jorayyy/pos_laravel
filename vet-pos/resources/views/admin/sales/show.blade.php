@extends('layouts.app')
@section('title', $sale->invoice_number . ' - VetPOS')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.sales.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to Sales</a>
    <div class="flex items-center justify-between mt-2">
        <h1 class="text-2xl font-bold">Transaction {{ $sale->invoice_number }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.receipts.show', $sale) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">View Receipt</a>
            @if($sale->status === 'completed')
            <form method="POST" action="{{ route('admin.sales.void', $sale) }}" onsubmit="return confirm('Void this sale? Stock will be restored.')">
                @csrf
                <button class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">Void Sale</button>
            </form>
            <form method="POST" action="{{ route('admin.sales.refund', $sale) }}" onsubmit="return confirm('Refund this sale? Stock will be restored.')">
                @csrf
                <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-600">Refund</button>
            </form>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="font-semibold mb-4">Transaction Details</h2>
        <dl class="space-y-2 text-sm">
            <div><dt class="text-gray-500">Invoice</dt><dd class="font-mono">{{ $sale->invoice_number }}</dd></div>
            <div><dt class="text-gray-500">Date</dt><dd>{{ $sale->created_at->format('M d, Y g:i A') }}</dd></div>
            <div><dt class="text-gray-500">Cashier</dt><dd>{{ $sale->user->name }}</dd></div>
            <div><dt class="text-gray-500">Customer</dt><dd>{{ $sale->owner->full_name ?? 'Walk-in' }}</dd></div>
            <div><dt class="text-gray-500">Pet</dt><dd>{{ $sale->pet->name ?? '-' }}</dd></div>
            <div><dt class="text-gray-500">Payment</dt><dd class="capitalize">{{ str_replace('_', ' ', $sale->payment_method) }}</dd></div>
            <div><dt class="text-gray-500">Status</dt>
                <dd><span class="text-xs px-2 py-0.5 rounded-full {{ $sale->status === 'completed' ? 'bg-green-100 text-green-700' : ($sale->status === 'voided' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($sale->status) }}</span></dd>
            </div>
        </dl>
    </div>

    <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200">
        <div class="px-4 py-3 border-b border-gray-200 font-semibold">Items</div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2">Type</th>
                        <th class="text-left px-4 py-2">Item</th>
                        <th class="text-right px-4 py-2">Qty</th>
                        <th class="text-right px-4 py-2">Unit Price</th>
                        <th class="text-right px-4 py-2">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($sale->items as $item)
                    <tr>
                        <td class="px-4 py-2">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $item->type === 'product' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">{{ ucfirst($item->type) }}</span>
                        </td>
                        <td class="px-4 py-2 font-medium">{{ $item->name }}</td>
                        <td class="px-4 py-2 text-right">{{ $item->quantity }}</td>
                        <td class="px-4 py-2 text-right">₱{{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-4 py-2 text-right font-medium">₱{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-gray-200">
                    <tr><td colspan="4" class="px-4 py-2 text-right text-gray-500">Subtotal</td><td class="px-4 py-2 text-right">₱{{ number_format($sale->subtotal, 2) }}</td></tr>
                    @if($sale->discount > 0)
                    <tr><td colspan="4" class="px-4 py-2 text-right text-gray-500">Discount</td><td class="px-4 py-2 text-right text-red-600">-₱{{ number_format($sale->discount, 2) }}</td></tr>
                    @endif
                    @if($sale->tax > 0)
                    <tr><td colspan="4" class="px-4 py-2 text-right text-gray-500">Tax</td><td class="px-4 py-2 text-right">₱{{ number_format($sale->tax, 2) }}</td></tr>
                    @endif
                    <tr><td colspan="4" class="px-4 py-2 text-right font-bold">Total</td><td class="px-4 py-2 text-right font-bold text-lg">₱{{ number_format($sale->total, 2) }}</td></tr>
                    <tr><td colspan="4" class="px-4 py-2 text-right text-gray-500">Amount Paid</td><td class="px-4 py-2 text-right">₱{{ number_format($sale->amount_paid, 2) }}</td></tr>
                    <tr><td colspan="4" class="px-4 py-2 text-right text-gray-500">Change</td><td class="px-4 py-2 text-right">₱{{ number_format($sale->change_amount, 2) }}</td></tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
