@extends('layouts.app')
@section('title', $user->name . ' - VetPOS')
@section('content')
<div class="max-w-lg">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back</a>
        <div class="flex items-center justify-between mt-2">
            <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Edit</a>
        </div>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <dl class="space-y-3 text-sm">
            <div><dt class="text-gray-500">Email</dt><dd>{{ $user->email }}</dd></div>
            <div><dt class="text-gray-500">Role</dt><dd><span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">{{ $user->role->name ?? 'No role' }}</span></dd></div>
            <div><dt class="text-gray-500">Status</dt><dd>{{ $user->is_active ? 'Active' : 'Inactive' }}</dd></div>
            <div><dt class="text-gray-500">Joined</dt><dd>{{ $user->created_at->format('M d, Y') }}</dd></div>
        </dl>
    </div>
</div>
@endsection
