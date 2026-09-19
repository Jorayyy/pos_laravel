@extends('layouts.app')
@section('title', 'Services - VetPOS')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Veterinary Services</h1>
    <a href="{{ route('admin.services.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ Add Service</a>
</div>
<div class="bg-white rounded-lg border border-gray-200">
    <div class="p-4 border-b border-gray-200">
        <form method="GET" class="flex gap-2 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search services..." class="flex-1 min-w-[200px] px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <select name="category_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Filter</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="text-left px-4 py-2">Name</th>
                    <th class="text-left px-4 py-2">Category</th>
                    <th class="text-right px-4 py-2">Price</th>
                    <th class="text-center px-4 py-2">Duration</th>
                    <th class="text-center px-4 py-2">Status</th>
                    <th class="text-right px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($services as $service)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">
                        <a href="{{ route('admin.services.show', $service) }}" class="text-blue-600 hover:underline">{{ $service->name }}</a>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $service->category->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-right">₱{{ number_format($service->price, 2) }}</td>
                    <td class="px-4 py-3 text-center">{{ $service->duration_minutes ? "{$service->duration_minutes} min" : '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $service->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $service->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.services.edit', $service) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No services found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $services->links() }}</div>
</div>
@endsection
