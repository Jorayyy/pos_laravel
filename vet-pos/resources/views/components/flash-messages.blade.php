@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="px-4 py-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg mx-4 sm:mx-6 mt-4 flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button @click="show = false" class="text-green-500 hover:text-green-700 ml-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
@endif

@if (session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg mx-4 sm:mx-6 mt-4 flex items-center justify-between">
        <span>{{ session('error') }}</span>
        <button @click="show = false" class="text-red-500 hover:text-red-700 ml-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
@endif

@if ($errors->any())
    <div x-data="{ show: true }" x-show="show"
         class="px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg mx-4 sm:mx-6 mt-4">
        <div class="flex items-center justify-between mb-1">
            <span class="font-medium">Please fix the following errors:</span>
            <button @click="show = false" class="text-red-500 hover:text-red-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
