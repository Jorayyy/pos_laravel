@extends('layouts.app')
@section('title', 'Add Owner - VetPOS')
@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.owners.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to Owners</a>
        <h1 class="text-2xl font-bold mt-2">Add Pet Owner</h1>
    </div>
    <form method="POST" action="{{ route('admin.owners.store') }}" enctype="multipart/form-data" class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
            <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
            <div class="flex items-center gap-4">
                <div id="photo-preview" class="w-16 h-16 rounded-full border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 text-xs overflow-hidden bg-gray-50 flex-shrink-0">
                    <span id="photo-placeholder">No photo</span>
                    <img id="photo-thumb" class="w-full h-full object-cover hidden" alt="">
                </div>
                <div class="flex-1">
                    <input type="file" name="photo" accept="image/*" onchange="previewPhoto(this)" class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    <p class="text-xs text-gray-400 mt-1">Optional. Max 2MB.</p>
                </div>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number *</label>
            <input type="text" name="contact_number" value="{{ old('contact_number') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
            <input type="text" name="address" value="{{ old('address') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">Save Owner</button>
            <a href="{{ route('admin.owners.index') }}" class="bg-gray-100 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">Cancel</a>
        </div>
    </form>
</div>
@push('scripts')
<script>
function previewPhoto(input) {
    const preview = document.getElementById('photo-thumb');
    const placeholder = document.getElementById('photo-placeholder');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) { preview.src = e.target.result; preview.classList.remove('hidden'); placeholder.classList.add('hidden'); }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
