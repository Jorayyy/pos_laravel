@extends('layouts.app')
@section('title', 'Edit Pet - VetPOS')
@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.pets.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to Pets</a>
        <h1 class="text-2xl font-bold mt-2">Edit Pet</h1>
    </div>
    <form method="POST" action="{{ route('admin.pets.update', $pet) }}" class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Owner *</label>
            <select name="owner_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">Select owner...</option>
                @foreach($owners as $owner)
                    <option value="{{ $owner->id }}" {{ old('owner_id', $pet->owner_id) == $owner->id ? 'selected' : '' }}>{{ $owner->full_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pet Name *</label>
                <input type="text" name="name" value="{{ old('name', $pet->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Species *</label>
                <input type="text" name="species" value="{{ old('species', $pet->species) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Breed</label>
                <input type="text" name="breed" value="{{ old('breed', $pet->breed) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sex</label>
                <div class="flex gap-4 mt-1">
                    <label class="flex items-center gap-1 text-sm"><input type="radio" name="sex" value="male" {{ old('sex', $pet->sex) === 'male' ? 'checked' : '' }}> Male</label>
                    <label class="flex items-center gap-1 text-sm"><input type="radio" name="sex" value="female" {{ old('sex', $pet->sex) === 'female' ? 'checked' : '' }}> Female</label>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Birthdate</label>
                <input type="date" name="birthdate" value="{{ old('birthdate', $pet->birthdate?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                <input type="text" name="color" value="{{ old('color', $pet->color) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                <input type="number" step="0.1" name="weight" value="{{ old('weight', $pet->weight) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Microchip Number</label>
            <input type="text" name="microchip_number" value="{{ old('microchip_number', $pet->microchip_number) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('notes', $pet->notes) }}</textarea>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">Update Pet</button>
            <a href="{{ route('admin.pets.index') }}" class="bg-gray-100 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">Cancel</a>
        </div>
    </form>
</div>
@endsection
