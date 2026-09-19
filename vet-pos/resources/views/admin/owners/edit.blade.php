@extends('layouts.app')
@section('title', 'Edit Owner - VetPOS')
@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.owners.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to Owners</a>
        <h1 class="text-2xl font-bold mt-2">Edit Owner</h1>
    </div>
    <form method="POST" action="{{ route('admin.owners.update', $owner) }}" class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
            <input type="text" name="full_name" value="{{ old('full_name', $owner->full_name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number *</label>
            <input type="text" name="contact_number" value="{{ old('contact_number', $owner->contact_number) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $owner->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
            <input type="text" name="address" value="{{ old('address', $owner->address) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">{{ old('notes', $owner->notes) }}</textarea>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">Update Owner</button>
            <a href="{{ route('admin.owners.index') }}" class="bg-gray-100 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">Cancel</a>
        </div>
    </form>
</div>
@endsection
