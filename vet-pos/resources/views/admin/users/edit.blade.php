@extends('layouts.app')
@section('title', 'Edit User - VetPOS')
@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back</a>
        <h1 class="text-2xl font-bold mt-2">Edit User</h1>
    </div>
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password (leave blank to keep)</label>
                <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
            <select name="role_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}> Active
        </label>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">Update User</button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-100 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">Cancel</a>
        </div>
    </form>
</div>
@endsection
