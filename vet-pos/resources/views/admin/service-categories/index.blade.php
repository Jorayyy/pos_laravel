@extends('layouts.app')
@section('title', 'Service Categories - VetPOS')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Service Categories</h1>
    <a href="{{ route('admin.service-categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ Add Category</a>
</div>
<div class="bg-white rounded-lg border border-gray-200">
    <div class="p-4 border-b border-gray-200">
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <button class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Search</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="text-left px-4 py-2">Name</th>
                    <th class="text-left px-4 py-2">Slug</th>
                    <th class="text-center px-4 py-2">Services</th>
                    <th class="text-center px-4 py-2">Status</th>
                    <th class="text-right px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $cat)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $cat->name }}</td>
                    <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $cat->slug }}</td>
                    <td class="px-4 py-3 text-center">{{ $cat->services_count }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $cat->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $cat->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.service-categories.edit', $cat) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.service-categories.destroy', $cat) }}" class="inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No categories.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $categories->links() }}</div>
</div>
@endsection
