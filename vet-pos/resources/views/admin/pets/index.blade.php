@extends('layouts.app')
@section('title', 'Pets - VetPOS')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Pets</h1>
    <a href="{{ route('admin.pets.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ Add Pet</a>
</div>
<div class="bg-white rounded-lg border border-gray-200">
    <div class="p-4 border-b border-gray-200">
        <form method="GET" class="flex gap-2 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search pets..." class="flex-1 min-w-[200px] px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <select name="species" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Species</option>
                @foreach($species as $sp)
                    <option {{ request('species') === $sp ? 'selected' : '' }}>{{ $sp }}</option>
                @endforeach
            </select>
            <select name="owner_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Owners</option>
                @foreach($owners as $o)
                    <option value="{{ $o->id }}" {{ request('owner_id') == $o->id ? 'selected' : '' }}>{{ $o->full_name }}</option>
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
                    <th class="text-left px-4 py-2">Species</th>
                    <th class="text-left px-4 py-2">Breed</th>
                    <th class="text-left px-4 py-2">Owner</th>
                    <th class="text-center px-4 py-2">Sex</th>
                    <th class="text-right px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pets as $pet)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">
                        <a href="{{ route('admin.pets.show', $pet) }}" class="text-blue-600 hover:underline">{{ $pet->name }}</a>
                    </td>
                    <td class="px-4 py-3">{{ $pet->species }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $pet->breed ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $pet->owner->full_name }}</td>
                    <td class="px-4 py-3 text-center">{{ $pet->sex ? ucfirst($pet->sex) : '-' }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.pets.edit', $pet) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.pets.destroy', $pet) }}" class="inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No pets found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $pets->links() }}</div>
</div>
@endsection
