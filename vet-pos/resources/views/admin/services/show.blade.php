@extends('layouts.app')
@section('title', $service->name . ' - VetPOS')
@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.services.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back</a>
        <div class="flex items-center justify-between mt-2">
            <h1 class="text-2xl font-bold">{{ $service->name }}</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.services.edit', $service) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Edit</a>
                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete?')">
                    @csrf @method('DELETE')
                    <button class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">Delete</button>
                </form>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <dl class="space-y-3 text-sm">
            <div><dt class="text-gray-500">Category</dt><dd>{{ $service->category->name ?? '-' }}</dd></div>
            <div><dt class="text-gray-500">Price</dt><dd class="text-lg font-semibold">₱{{ number_format($service->price, 2) }}</dd></div>
            <div><dt class="text-gray-500">Duration</dt><dd>{{ $service->duration_minutes ? "{$service->duration_minutes} minutes" : '-' }}</dd></div>
            <div><dt class="text-gray-500">Description</dt><dd>{{ $service->description ?? '-' }}</dd></div>
            <div><dt class="text-gray-500">Status</dt><dd><span class="text-xs px-2 py-0.5 rounded-full {{ $service->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $service->is_active ? 'Active' : 'Inactive' }}</span></dd></div>
        </dl>
    </div>
</div>
@endsection
