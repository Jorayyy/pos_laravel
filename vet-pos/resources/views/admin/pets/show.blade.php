@extends('layouts.app')
@section('title', $pet->name . ' - VetPOS')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.pets.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to Pets</a>
    <div class="flex items-center justify-between mt-2">
        <h1 class="text-2xl font-bold">{{ $pet->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.pets.edit', $pet) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Edit</a>
            <form method="POST" action="{{ route('admin.pets.destroy', $pet) }}" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">Delete</button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        @if($pet->photo_url)
            <div class="mb-4 rounded-full overflow-hidden border border-gray-200 w-24 h-24">
                <img src="{{ $pet->photo_url }}" alt="{{ $pet->name }}" class="w-full h-full object-cover">
            </div>
        @endif
        <h2 class="font-semibold mb-4">Pet Information</h2>
        <dl class="space-y-2 text-sm">
            <div><dt class="text-gray-500">Owner</dt><dd><a href="{{ route('admin.owners.show', $pet->owner) }}" class="text-blue-600 hover:underline">{{ $pet->owner->full_name }}</a></dd></div>
            <div><dt class="text-gray-500">Species</dt><dd>{{ $pet->species }}</dd></div>
            <div><dt class="text-gray-500">Breed</dt><dd>{{ $pet->breed ?? '-' }}</dd></div>
            <div><dt class="text-gray-500">Sex</dt><dd>{{ $pet->sex ? ucfirst($pet->sex) : '-' }}</dd></div>
            <div><dt class="text-gray-500">Birthdate</dt><dd>{{ $pet->birthdate ? $pet->birthdate->format('M d, Y') : '-' }}</dd></div>
            <div><dt class="text-gray-500">Color</dt><dd>{{ $pet->color ?? '-' }}</dd></div>
            <div><dt class="text-gray-500">Weight</dt><dd>{{ $pet->weight ? "{$pet->weight} kg" : '-' }}</dd></div>
            <div><dt class="text-gray-500">Microchip</dt><dd>{{ $pet->microchip_number ?? '-' }}</dd></div>
            <div><dt class="text-gray-500">Notes</dt><dd>{{ $pet->notes ?? '-' }}</dd></div>
        </dl>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 font-semibold">Visit History</div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr><th class="text-left px-4 py-2">Invoice</th><th class="text-left px-4 py-2">Date</th><th class="text-right px-4 py-2">Total</th><th class="text-center px-4 py-2">Status</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pet->sales as $sale)
                        <tr>
                            <td class="px-4 py-2"><a href="{{ route('admin.sales.show', $sale) }}" class="text-blue-600 hover:underline">{{ $sale->invoice_number }}</a></td>
                            <td class="px-4 py-2 text-gray-500">{{ $sale->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-2 text-right">₱{{ number_format($sale->total, 2) }}</td>
                            <td class="px-4 py-2 text-center">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $sale->status === 'completed' ? 'bg-green-100 text-green-700' : ($sale->status === 'voided' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($sale->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-4 text-center text-gray-400">No visits yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
