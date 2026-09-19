@extends('layouts.app')
@section('title', 'Edit Product - VetPOS')
@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to Products</a>
        <h1 class="text-2xl font-bold mt-2">Edit Product</h1>
    </div>
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        @csrf @method('PUT')
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Barcode</label>
                <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
            <div class="flex items-center gap-4">
                <div id="photo-preview" class="w-20 h-20 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 text-xs overflow-hidden bg-gray-50">
                    <span id="photo-placeholder" class="{{ $product->photo_url ? 'hidden' : '' }}">No photo</span>
                    <img id="photo-thumb" class="w-full h-full object-cover {{ $product->photo_url ? '' : 'hidden' }}" src="{{ $product->photo_url ?? '' }}" alt="Preview">
                </div>
                <div class="flex-1">
                    <input type="file" name="photo" accept="image/*" onchange="previewPhoto(this)" class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-400 mt-1">Max 2MB. JPG, PNG, WebP.</p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="product_category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">None</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('product_category_id', $product->product_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cost Price *</label>
                <input type="number" step="0.01" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Selling Price *</label>
                <input type="number" step="0.01" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reorder Level *</label>
                <input type="number" name="reorder_level" value="{{ old('reorder_level', $product->reorder_level) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Expiration Date</label>
                <input type="date" name="expiration_date" value="{{ old('expiration_date', $product->expiration_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
        </div>
        <div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}> Active
            </label>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">Update Product</button>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-100 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">Cancel</a>
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
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
