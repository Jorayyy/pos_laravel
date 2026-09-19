@extends('layouts.app')
@section('title', 'Weekly Sales - VetPOS')
@section('content')
<h1 class="text-2xl font-bold mb-6">Weekly Sales Report</h1>
<div class="bg-white rounded-lg border border-gray-200 mb-6 p-4">
    <form method="GET" class="flex gap-2 items-center">
        <span class="text-sm text-gray-500">From</span>
        <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
        <span class="text-sm text-gray-500">To</span>
        <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
        <button class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">View</button>
        <span class="ml-4 text-sm text-gray-500">Total: <strong>₱{{ number_format($total, 2) }}</strong> | {{ $count }} transactions</span>
    </form>
</div>
<div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr><th class="text-left px-4 py-2">Date</th><th class="text-right px-4 py-2">Transactions</th><th class="text-right px-4 py-2">Total Sales</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($dailyData as $row)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2">{{ Carbon::parse($row->date)->format('l, M d, Y') }}</td>
                <td class="px-4 py-2 text-right">{{ $row->count }}</td>
                <td class="px-4 py-2 text-right font-medium">₱{{ number_format($row->total, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">No data.</td></tr>
            @endforelse
        </tbody>
        <tfoot class="border-t-2 border-gray-200 font-bold">
            <tr><td class="px-4 py-2">Total</td><td class="px-4 py-2 text-right">{{ $count }}</td><td class="px-4 py-2 text-right">₱{{ number_format($total, 2) }}</td></tr>
        </tfoot>
    </table>
</div>
@endsection
