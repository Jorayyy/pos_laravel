@extends('layouts.app')
@section('title', 'Edit Service - VetPOS')
@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.services.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back</a>
        <h1 class="text-2xl font-bold mt-2">Edit Service</h1>
    </div>
    <form method="POST" action="{{ route('admin.services.update', $service) }}" class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Service Name *</label>
            <input type="text" name="name" value="{{ old('name', $service->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="service_category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">None</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('service_category_id', $service->service_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $service->price) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('description', $service->description) }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duration (minutes)</label>
                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $service->duration_minutes) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div class="flex items-end">
                <label class="flex items-center gap-2 text-sm pb-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}> Active
                </label>
            </div>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">Update</button>
            <a href="{{ route('admin.services.index') }}" class="bg-gray-100 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">Cancel</a>
        </div>
    </form>
</div>
@endsection
