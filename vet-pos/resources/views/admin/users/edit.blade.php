@extends('layouts.app')
@section('title', 'Edit User - VetPOS')
@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back</a>
        <h1 class="text-2xl font-bold mt-2">Edit User</h1>
    </div>
    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
            <div class="flex items-center gap-4">
                <div id="photo-preview" class="w-16 h-16 rounded-full border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 text-xs overflow-hidden bg-gray-50 flex-shrink-0">
                    <span id="photo-placeholder" class="{{ $user->photo_url ? 'hidden' : '' }}">No photo</span>
                    <img id="photo-thumb" class="w-full h-full object-cover {{ $user->photo_url ? '' : 'hidden' }}" src="{{ $user->photo_url ?? '' }}" alt="">
                </div>
                <div class="flex-1">
                    <input type="file" name="photo" accept="image/*" onchange="previewPhoto(this)" class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    <p class="text-xs text-gray-400 mt-1">Optional. Max 2MB.</p>
                </div>
            </div>
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
