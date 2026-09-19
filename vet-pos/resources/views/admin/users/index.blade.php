@extends('layouts.app')
@section('title', 'Users - VetPOS')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Users</h1>
    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ Add User</a>
</div>
<div class="bg-white rounded-lg border border-gray-200">
    <div class="p-4 border-b border-gray-200">
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <button class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Search</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="text-left px-4 py-2">Name</th>
                    <th class="text-left px-4 py-2">Email</th>
                    <th class="text-left px-4 py-2">Role</th>
                    <th class="text-center px-4 py-2">Status</th>
                    <th class="text-right px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $user->email }}</td>
                    <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">{{ $user->role->name ?? 'No role' }}</span></td>
                    <td class="px-4 py-3 text-center">
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                            @csrf
                            <button class="text-xs px-2 py-0.5 rounded-full {{ $user->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</button>
                        </form>
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline text-xs">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $users->links() }}</div>
</div>
@endsection
