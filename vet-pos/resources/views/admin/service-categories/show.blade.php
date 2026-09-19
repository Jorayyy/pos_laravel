@extends('layouts.app')
@section('title', $serviceCategory->name . ' - VetPOS')
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.service-categories.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back</a>
    <div class="flex items-center justify-between mt-2">
        <h1 class="text-2xl font-bold">{{ $serviceCategory->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.service-categories.edit', $serviceCategory) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Edit</a>
            <form method="POST" action="{{ route('admin.service-categories.destroy', $serviceCategory) }}" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">Delete</button>
            </form>
        </div>
    </div>
</div>
<div class="bg-white rounded-lg border border-gray-200">
    <div class="px-4 py-3 border-b border-gray-200 font-semibold">Services ({{ $serviceCategory->services->count() }})</div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr><th class="text-left px-4 py-2">Name</th><th class="text-right px-4 py-2">Price</th><th class="text-center px-4 py-2">Duration</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($serviceCategory->services as $s)
                <tr>
                    <td class="px-4 py-2"><a href="{{ route('admin.services.show', $s) }}" class="text-blue-600 hover:underline">{{ $s->name }}</a></td>
                    <td class="px-4 py-2 text-right">₱{{ number_format($s->price, 2) }}</td>
                    <td class="px-4 py-2 text-center">{{ $s->duration_minutes ? "{$s->duration_minutes} min" : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-4 py-4 text-center text-gray-400">No services.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
