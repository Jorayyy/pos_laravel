@extends('layouts.app')
@section('title', 'Settings - VetPOS')
@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold mb-6">Settings</h1>
    <form method="POST" action="{{ route('admin.settings.update') }}" class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Clinic/Business Name *</label>
            <input type="text" name="business_name" value="{{ $settings['business_name'] ?? '' }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
            <input type="text" name="business_address" value="{{ $settings['business_address'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" name="business_phone" value="{{ $settings['business_phone'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="business_email" value="{{ $settings['business_email'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Currency *</label>
                <input type="text" name="currency" value="{{ $settings['currency'] ?? '₱' }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tax Rate (%) *</label>
                <input type="number" step="0.01" name="tax_rate" value="{{ $settings['tax_rate'] ?? '0' }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Low Stock Threshold *</label>
                <input type="number" name="low_stock_threshold" value="{{ $settings['low_stock_threshold'] ?? '5' }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Receipt Footer</label>
            <textarea name="receipt_footer" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ $settings['receipt_footer'] ?? '' }}</textarea>
        </div>
        <div class="pt-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">Save Settings</button>
        </div>
    </form>
</div>
@endsection
