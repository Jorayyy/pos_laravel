@extends('layouts.app')
@section('title', 'Sales by Service - VetPOS')
@section('content')
<h1 class="text-2xl font-bold mb-6">Sales by Service</h1>
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
        <thead class="bg-gray-50"><tr><th class="text-left px-4 py-2">Service</th><th class="text-right px-4 py-2">Times Performed</th><th class="text-right px-4 py-2">Revenue</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($data as $row)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 font-medium">{{ $row->service->name ?? 'Deleted' }}</td>
                <td class="px-4 py-2 text-right">{{ $row->total_qty }}</td>
                <td class="px-4 py-2 text-right font-medium">₱{{ number_format($row->total_amount, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">No data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
