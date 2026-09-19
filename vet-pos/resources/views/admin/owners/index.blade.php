@extends('layouts.app')
@section('title', 'Pet Owners - VetPOS')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Pet Owners</h1>
    <a href="{{ route('admin.owners.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ Add Owner</a>
</div>
<div class="bg-white rounded-lg border border-gray-200">
    <div class="p-4 border-b border-gray-200">
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search owners..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
            <button class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Search</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="text-left px-4 py-2">Name</th>
                    <th class="text-left px-4 py-2">Contact</th>
                    <th class="text-left px-4 py-2">Email</th>
                    <th class="text-center px-4 py-2">Pets</th>
                    <th class="text-right px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($owners as $owner)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full border border-gray-200 overflow-hidden bg-gray-50 flex-shrink-0">
                                @if($owner->photo_url)
                                    <img src="{{ $owner->photo_url }}" alt="{{ $owner->full_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 font-semibold text-xs bg-emerald-50 text-emerald-600">{{ strtoupper(substr($owner->full_name, 0, 1)) }}</div>
                                @endif
                            </div>
                            <a href="{{ route('admin.owners.show', $owner) }}" class="text-blue-600 hover:underline">{{ $owner->full_name }}</a>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $owner->contact_number ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $owner->email ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">{{ $owner->pets_count }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.owners.edit', $owner) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.owners.destroy', $owner) }}" class="inline" onsubmit="return confirm('Delete this owner?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No owners found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $owners->links() }}</div>
</div>
@endsection
