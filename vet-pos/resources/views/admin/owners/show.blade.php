@extends('layouts.app')
@section('title', $owner->full_name . ' - VetPOS')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.owners.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to Owners</a>
    <div class="flex items-center justify-between mt-2">
        <h1 class="text-2xl font-bold">{{ $owner->full_name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.owners.edit', $owner) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Edit</a>
            <form method="POST" action="{{ route('admin.owners.destroy', $owner) }}" onsubmit="return confirm('Delete this owner and all their pets?')">
                @csrf @method('DELETE')
                <button class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">Delete</button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="font-semibold mb-4">Contact Information</h2>
        <dl class="space-y-2 text-sm">
            <div><dt class="text-gray-500">Contact</dt><dd>{{ $owner->contact_number ?? '-' }}</dd></div>
            <div><dt class="text-gray-500">Email</dt><dd>{{ $owner->email ?? '-' }}</dd></div>
            <div><dt class="text-gray-500">Address</dt><dd>{{ $owner->address ?? '-' }}</dd></div>
            <div><dt class="text-gray-500">Notes</dt><dd>{{ $owner->notes ?? '-' }}</dd></div>
        </dl>
    </div>

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 font-semibold">Pets ({{ $owner->pets->count() }})</div>
            <div class="p-4">
                @forelse($owner->pets as $pet)
                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div>
                        <a href="{{ route('admin.pets.show', $pet) }}" class="font-medium text-blue-600 hover:underline">{{ $pet->name }}</a>
                        <span class="text-sm text-gray-500 ml-2">{{ $pet->species }} {{ $pet->breed ? "/ {$pet->breed}" : '' }}</span>
                    </div>
                    <span class="text-xs text-gray-400">{{ $pet->sex ? ucfirst($pet->sex) : '' }}</span>
                </div>
                @empty
                <p class="text-gray-400 text-sm">No pets registered.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 font-semibold">Recent Transactions</div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr><th class="text-left px-4 py-2">Invoice</th><th class="text-left px-4 py-2">Date</th><th class="text-right px-4 py-2">Total</th><th class="text-center px-4 py-2">Status</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($owner->sales as $sale)
                        <tr>
                            <td class="px-4 py-2"><a href="{{ route('admin.sales.show', $sale) }}" class="text-blue-600 hover:underline">{{ $sale->invoice_number }}</a></td>
                            <td class="px-4 py-2 text-gray-500">{{ $sale->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-2 text-right">₱{{ number_format($sale->total, 2) }}</td>
                            <td class="px-4 py-2 text-center">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $sale->status === 'completed' ? 'bg-green-100 text-green-700' : ($sale->status === 'voided' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($sale->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-4 text-center text-gray-400">No transactions.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
