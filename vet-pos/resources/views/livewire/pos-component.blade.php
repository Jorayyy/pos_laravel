<div class="flex h-screen bg-gray-100 overflow-hidden" x-data="{ receiptUrl: null }">

    {{-- Left: Products/Services Area --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top Bar --}}
        <div class="bg-white shadow-sm p-3 flex items-center gap-3 flex-shrink-0">
            {{-- Search --}}
            <div class="relative flex-1 max-w-xl">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search products or services..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                />
            </div>

            {{-- Tab Toggle --}}
            <div class="flex rounded-lg overflow-hidden border border-gray-300">
                <button
                    wire:click="$set('activeTab', 'products')"
                    class="px-4 py-2 text-sm font-medium {{ $activeTab === 'products' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}"
                >
                    Products
                </button>
                <button
                    wire:click="$set('activeTab', 'services')"
                    class="px-4 py-2 text-sm font-medium {{ $activeTab === 'services' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}"
                >
                    Services
                </button>
            </div>
        </div>

        {{-- Category Filters (Products only) --}}
        @if($activeTab === 'products')
            <div class="bg-white border-b px-3 py-2 flex gap-2 overflow-x-auto flex-shrink-0">
                <button
                    wire:click="$set('categoryFilter', '')"
                    class="px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap {{ $categoryFilter === '' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                >
                    All
                </button>
                @foreach($this->productCategories as $cat)
                    <button
                        wire:click="$set('categoryFilter', '{{ $cat->id }}')"
                        class="px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap {{ $categoryFilter === (string) $cat->id ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        @endif

        {{-- Products/Services Grid --}}
        <div class="flex-1 overflow-y-auto p-3">
            @if($activeTab === 'products')
                @if($this->products->isEmpty())
                    <div class="flex flex-col items-center justify-center h-full text-gray-500">
                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <p>No products found</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2">
                        @foreach($this->products as $product)
                            <button
                                wire:click="addToCart('product', {{ $product->id }})"
                                @disabled($product->stock <= 0)
                                class="relative bg-white rounded-lg border p-3 text-left hover:shadow-md transition-shadow {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:border-blue-400' }}"
                            >
                                @if($product->stock <= 0)
                                    <span class="absolute top-1 right-1 text-[10px] bg-red-100 text-red-700 px-1.5 py-0.5 rounded-full font-medium">OUT</span>
                                @elseif($product->stock <= $product->reorder_level)
                                    <span class="absolute top-1 right-1 text-[10px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded-full font-medium">Low: {{ $product->stock }}</span>
                                @endif

                                <div class="font-medium text-sm leading-tight mb-1 line-clamp-2">{{ $product->name }}</div>
                                <div class="text-[11px] text-gray-500 mb-1">{{ $product->sku }}</div>
                                @if($product->category)
                                    <div class="text-[10px] text-gray-400 mb-1">{{ $product->category->name }}</div>
                                @endif
                                <div class="text-blue-600 font-bold text-sm">₱{{ number_format($product->selling_price, 2) }}</div>
                                <div class="text-[10px] text-gray-400 mt-0.5">Stock: {{ $product->stock }} {{ $product->unit }}</div>
                            </button>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        {{ $this->products->links() }}
                    </div>
                @endif
            @else
                @if($this->services->isEmpty())
                    <div class="flex flex-col items-center justify-center h-full text-gray-500">
                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p>No services found</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2">
                        @foreach($this->services as $service)
                            <button
                                wire:click="addToCart('service', {{ $service->id }})"
                                class="bg-white rounded-lg border p-3 text-left hover:shadow-md transition-shadow cursor-pointer hover:border-blue-400"
                            >
                                <div class="font-medium text-sm leading-tight mb-1 line-clamp-2">{{ $service->name }}</div>
                                @if($service->category)
                                    <div class="text-[10px] text-gray-400 mb-1">{{ $service->category->name }}</div>
                                @endif
                                <div class="text-blue-600 font-bold text-sm">₱{{ number_format($service->price, 2) }}</div>
                                @if($service->duration_minutes)
                                    <div class="text-[10px] text-gray-400 mt-0.5">{{ $service->duration_minutes }} min</div>
                                @endif
                            </button>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        {{ $this->services->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Right: Cart Panel --}}
    <div class="w-[380px] bg-white border-l flex flex-col flex-shrink-0">

        {{-- Owner/Pet Selection --}}
        <div class="p-3 border-b bg-gray-50">
            <div class="relative">
                <label class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">Customer</label>
                @if($this->selectedOwner)
                    <div class="mt-1 flex items-center justify-between bg-blue-50 border border-blue-200 rounded-lg px-3 py-2">
                        <div>
                            <div class="font-medium text-sm">{{ $this->selectedOwner->full_name }}</div>
                            @if($this->selectedOwner->pets->count())
                                <select
                                    wire:model.live="petId"
                                    class="mt-1 text-xs border-blue-200 rounded-md focus:ring-blue-500"
                                >
                                    <option value="">Select Pet</option>
                                    @foreach($this->selectedOwner->pets as $pet)
                                        <option value="{{ $pet->id }}">{{ $pet->name }} ({{ $pet->species }})</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <button wire:click="selectOwner(null)" class="text-gray-400 hover:text-red-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @else
                    <input
                        type="text"
                        wire:model.live="ownerSearch"
                        wire:focus="$set('showOwnerDropdown', true)"
                        placeholder="Search owner by name or phone..."
                        class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
                    />
                    @if($this->showOwnerDropdown && $this->ownerResults->count())
                        <div class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                            @foreach($this->ownerResults as $owner)
                                <button
                                    wire:click="selectOwner({{ $owner->id }})"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-blue-50 border-b last:border-0"
                                >
                                    <div class="font-medium">{{ $owner->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $owner->contact_number }}</div>
                                </button>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto">
            @if(empty($this->cart))
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    <p class="text-sm">Cart is empty</p>
                    <p class="text-xs">Click items to add</p>
                </div>
            @else
                <div class="divide-y">
                    @foreach($this->cartItems as $index => $item)
                        <div class="p-3 hover:bg-gray-50">
                            <div class="flex justify-between items-start mb-1">
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-sm truncate">{{ $item['name'] }}</div>
                                    <div class="text-[11px] text-gray-500">
                                        @if($item['type'] === 'product')
                                            {{ $item['sku'] }} &middot; ₱{{ number_format($item['price'], 2) }}
                                        @else
                                            Service &middot; ₱{{ number_format($item['price'], 2) }}
                                        @endif
                                    </div>
                                </div>
                                <div class="text-sm font-bold text-right ml-2">
                                    ₱{{ number_format($item['price'] * $item['quantity'], 2) }}
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1">
                                    <button
                                        wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] - 1 }})"
                                        class="w-7 h-7 rounded border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-100"
                                    >
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                    </button>
                                    <input
                                        type="number"
                                        wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                        value="{{ $item['quantity'] }}"
                                        min="1"
                                        @if($item['type'] === 'product') max="{{ $item['stock'] }}" @endif
                                        class="w-14 text-center text-sm border border-gray-300 rounded py-1"
                                    />
                                    <button
                                        wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})"
                                        class="w-7 h-7 rounded border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-100"
                                    >
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                                <button
                                    wire:click="removeItem({{ $index }})"
                                    class="text-gray-400 hover:text-red-500 p-1"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Cart Summary & Checkout --}}
        <div class="border-t bg-gray-50 p-3 space-y-2 flex-shrink-0">

            {{-- Discount & Tax --}}
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="text-[11px] font-medium text-gray-500">Discount (₱)</label>
                    <input
                        type="number"
                        wire:model.live="discount"
                        min="0"
                        step="0.01"
                        class="mt-0.5 w-full px-2 py-1.5 border border-gray-300 rounded text-sm"
                    />
                </div>
                <div>
                    <label class="text-[11px] font-medium text-gray-500">Tax (%)</label>
                    <input
                        type="number"
                        wire:model.live="taxRate"
                        min="0"
                        max="100"
                        step="0.01"
                        class="mt-0.5 w-full px-2 py-1.5 border border-gray-300 rounded text-sm"
                    />
                </div>
            </div>

            {{-- Totals --}}
            <div class="space-y-1 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>₱{{ number_format($this->subtotal, 2) }}</span>
                </div>
                @if($this->discountAmount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Discount</span>
                        <span>-₱{{ number_format($this->discountAmount, 2) }}</span>
                    </div>
                @endif
                @if($this->taxAmount > 0)
                    <div class="flex justify-between text-gray-600">
                        <span>Tax ({{ number_format($this->taxRate, 1) }}%)</span>
                        <span>₱{{ number_format($this->taxAmount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-bold text-lg border-t pt-1">
                    <span>Total</span>
                    <span>₱{{ number_format($this->grandTotal, 2) }}</span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-2">
                <button
                    wire:click="clearCart"
                    @disabled(empty($this->cart))
                    class="px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 disabled:opacity-50"
                >
                    Clear
                </button>
                <button
                    wire:click="openCheckout"
                    @disabled(empty($this->cart))
                    class="flex-1 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 disabled:opacity-50"
                >
                    Checkout ₱{{ number_format($this->grandTotal, 2) }}
                </button>
            </div>
        </div>
    </div>

    {{-- Checkout Modal --}}
    @if($showCheckout)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="$set('showCheckout', false)">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

                {{-- Modal Header --}}
                <div class="bg-blue-600 text-white px-5 py-4">
                    <h2 class="text-lg font-bold">Complete Payment</h2>
                    <p class="text-blue-100 text-sm">Total: ₱{{ number_format($this->grandTotal, 2) }}</p>
                </div>

                {{-- Modal Body --}}
                <div class="p-5 space-y-4">

                    {{-- Payment Method --}}
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Payment Method</label>
                        <div class="mt-2 grid grid-cols-3 gap-2">
                            @foreach(['cash' => 'Cash', 'gcash' => 'GCash', 'card' => 'Card', 'bank_transfer' => 'Bank', 'other' => 'Other'] as $value => $label)
                                <button
                                    wire:click="$set('paymentMethod', '{{ $value }}')"
                                    class="py-2 px-3 rounded-lg border-2 text-sm font-medium transition-colors {{ $paymentMethod === $value ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-600 hover:border-gray-300' }}"
                                >
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Amount Paid --}}
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Amount Paid</label>
                        <div class="mt-1 relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">₱</span>
                            <input
                                type="number"
                                wire:model.live="amountPaid"
                                min="0"
                                step="0.01"
                                class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg text-2xl font-bold focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        {{-- Quick amount buttons for cash --}}
                        @if($paymentMethod === 'cash')
                            <div class="flex gap-2 mt-2">
                                <button
                                    wire:click="$set('amountPaid', {{ $this->grandTotal }})"
                                    class="flex-1 py-1.5 text-xs font-medium bg-gray-100 rounded hover:bg-gray-200"
                                >
                                    Exact
                                </button>
                                @foreach([500, 1000] as $bill)
                                    @if($bill >= $this->grandTotal)
                                        <button
                                            wire:click="$set('amountPaid', {{ $bill }})"
                                            class="flex-1 py-1.5 text-xs font-medium bg-gray-100 rounded hover:bg-gray-200"
                                        >
                                            ₱{{ number_format($bill) }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Change --}}
                    @if($paymentMethod === 'cash' && $this->change > 0)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                            <div class="text-xs text-green-600 font-medium">Change</div>
                            <div class="text-2xl font-bold text-green-700">₱{{ number_format($this->change, 2) }}</div>
                        </div>
                    @endif

                    {{-- Order Summary --}}
                    <div class="bg-gray-50 rounded-lg p-3 max-h-40 overflow-y-auto">
                        <div class="text-xs font-medium text-gray-500 mb-2">Order Summary</div>
                        @foreach($this->cartItems as $item)
                            <div class="flex justify-between text-sm py-0.5">
                                <span class="text-gray-600">{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                                <span class="font-medium">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </div>
                        @endforeach>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-5 pb-5 flex gap-3">
                    <button
                        wire:click="$set('showCheckout', false)"
                        class="px-5 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="checkout"
                        wire:loading.attr="disabled"
                        class="flex-1 py-3 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700 disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="checkout">Confirm Payment</span>
                        <span wire:loading wire:target="checkout">Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Flash Messages --}}
    @if(session()->has('error'))
        <div class="fixed top-4 right-4 z-50 bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2"
             x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    @endif

    @if(session()->has('success'))
        <div class="fixed top-4 right-4 z-50 bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2"
             x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif
</div>
