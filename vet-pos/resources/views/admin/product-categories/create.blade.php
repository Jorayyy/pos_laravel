@extends('layouts.app')
@section('title', 'Add Category - VetPOS')
@section('content')
<div class="max-w-lg">
    <div class="mb-6">
        <a href="{{ route('admin.product-categories.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back</a>
        <h1 class="text-2xl font-bold mt-2">Add Product Category</h1>
    </div>
    <form method="POST" action="{{ route('admin.product-categories.store') }}" class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}> Active
        </label>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">Save</button>
            <a href="{{ route('admin.product-categories.index') }}" class="bg-gray-100 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">Cancel</a>
        </div>
    </form>
</div>
@endsection
