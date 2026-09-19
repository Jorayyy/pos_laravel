<div class="flex h-screen bg-gray-950 overflow-hidden text-white" x-data="{ receiptUrl: null }">

    {{-- Left: Products/Services Area --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top Bar --}}
        <div class="bg-gray-900/80 backdrop-blur-xl border-b border-white/5 p-4 flex items-center gap-4 flex-shrink-0">
            {{-- Logo --}}
            <div class="flex items-center gap-2.5 mr-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <span class="text-sm font-bold tracking-tight hidden sm:block">VetPOS</span>
            </div>

            {{-- Search --}}
            <div class="relative flex-1 max-w-xl">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search products or services..."
                    class="w-full pl-10 pr-4 py-2.5 bg-white/5 border border-white/10 rounded-xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 text-sm text-white placeholder-gray-500 transition-all"
                />
            </div>

            {{-- Tab Toggle --}}
            <div class="flex rounded-xl overflow-hidden bg-white/5 border border-white/10 p-0.5">
                <button
                    wire:click="$set('activeTab', 'products')"
                    class="px-4 py-2 text-sm font-semibold rounded-lg transition-all {{ $activeTab === 'products' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'text-gray-400 hover:text-white' }}"
                >
                    Products
                </button>
                <button
                    wire:click="$set('activeTab', 'services')"
                    class="px-4 py-2 text-sm font-semibold rounded-lg transition-all {{ $activeTab === 'services' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'text-gray-400 hover:text-white' }}"
                >
                    Services
                </button>
            </div>

            {{-- Back to Admin --}}
            <a href="{{ route('admin.dashboard') }}" class="p-2.5 text-gray-500 hover:text-white hover:bg-white/5 rounded-xl transition-all" title="Back to Dashboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
        </div>

        {{-- Category Filters (Products only) --}}
        @if($activeTab === 'products')
            <div class="bg-gray-900/40 border-b border-white/5 px-4 py-2.5 flex gap-2 overflow-x-auto flex-shrink-0">
                <button
                    wire:click="$set('categoryFilter', '')"
                    class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all {{ $categoryFilter === '' ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/20' : 'bg-white/5 text-gray-400 border border-white/5 hover:bg-white/10 hover:text-white' }}"
                >
                    All Items
                </button>
                @foreach($this->productCategories as $cat)
                    <button
                        wire:click="$set('categoryFilter', '{{ $cat->id }}')"
                        class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all {{ $categoryFilter === (string) $cat->id ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/20' : 'bg-white/5 text-gray-400 border border-white/5 hover:bg-white/10 hover:text-white' }}"
                    >
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        @endif

        {{-- Products/Services Grid --}}
        <div class="flex-1 overflow-y-auto p-4">
            @if($activeTab === 'products')
                @if($this->products->isEmpty())
                    <div class="flex flex-col items-center justify-center h-full text-gray-600">
                        <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <p class="font-semibold">No products found</p>
                        <p class="text-sm text-gray-700 mt-1">Try a different search or category</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-3">
                        @foreach($this->products as $product)
                            <button
                                wire:click="addToCart('product', {{ $product->id }})"
                                @disabled($product->stock <= 0)
                                class="relative bg-white/[0.03] border border-white/[0.06] rounded-2xl p-3 text-left hover:bg-white/[0.07] hover:border-emerald-500/30 transition-all duration-200 group {{ $product->stock <= 0 ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer' }}"
                            >
                                {{-- Photo --}}
                                <div class="w-full aspect-square rounded-xl mb-3 overflow-hidden bg-white/5 flex items-center justify-center">
                                    @if($product->photo_url)
                                        <img src="{{ $product->photo_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <svg class="w-8 h-8 text-gray-700 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    @endif
                                </div>

                                {{-- Badge --}}
                                @if($product->stock <= 0)
                                    <span class="absolute top-2 right-2 text-[10px] bg-red-500/20 text-red-400 px-2 py-0.5 rounded-full font-bold border border-red-500/20">OUT</span>
                                @elseif($product->stock <= $product->reorder_level)
                                    <span class="absolute top-2 right-2 text-[10px] bg-amber-500/20 text-amber-400 px-2 py-0.5 rounded-full font-bold border border-amber-500/20">Low</span>
                                @endif

                                {{-- Info --}}
                                <div class="font-semibold text-[13px] leading-tight mb-1 line-clamp-2 text-white/90">{{ $product->name }}</div>
                                @if($product->category)
                                    <div class="text-[10px] text-gray-500 mb-2">{{ $product->category->name }}</div>
                                @endif
                                <div class="flex items-center justify-between">
                                    <div class="text-emerald-400 font-bold text-sm">₱{{ number_format($product->selling_price, 2) }}</div>
                                    <div class="text-[10px] text-gray-600">{{ $product->stock }} {{ $product->unit }}</div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $this->products->links() }}
                    </div>
                @endif
            @else
                @if($this->services->isEmpty())
                    <div class="flex flex-col items-center justify-center h-full text-gray-600">
                        <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <p class="font-semibold">No services found</p>
                        <p class="text-sm text-gray-700 mt-1">Try a different search</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-3">
                        @foreach($this->services as $service)
                            <button
                                wire:click="addToCart('service', {{ $service->id }})"
                                class="relative bg-white/[0.03] border border-white/[0.06] rounded-2xl p-4 text-left hover:bg-white/[0.07] hover:border-emerald-500/30 transition-all duration-200 cursor-pointer group"
                            >
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-500/20 border border-emerald-500/10 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                </div>
                                <div class="font-semibold text-[13px] leading-tight mb-1 line-clamp-2 text-white/90">{{ $service->name }}</div>
                                @if($service->category)
                                    <div class="text-[10px] text-gray-500 mb-2">{{ $service->category->name }}</div>
                                @endif
                                <div class="text-emerald-400 font-bold text-sm">₱{{ number_format($service->price, 2) }}</div>
                                @if($service->duration_minutes)
                                    <div class="text-[10px] text-gray-600 mt-1">{{ $service->duration_minutes }} min</div>
                                @endif
                            </button>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $this->services->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Right: Cart Panel --}}
    <div class="w-[400px] bg-gray-900 border-l border-white/5 flex flex-col flex-shrink-0">

        {{-- Cart Header --}}
        <div class="p-4 border-b border-white/5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-bold text-white/90 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    Current Order
                    @if(!empty($this->cart))
                        <span class="text-[10px] bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded-full">{{ count($this->cart) }} items</span>
                    @endif
                </h2>
            </div>

            {{-- Owner/Pet Selection --}}
            <div class="relative">
                @if($this->selectedOwner)
                    <div class="flex items-center justify-between bg-emerald-500/10 border border-emerald-500/20 rounded-xl px-3 py-2.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400 font-bold text-xs">
                                {{ strtoupper(substr($this->selectedOwner->full_name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-sm text-white/90">{{ $this->selectedOwner->full_name }}</div>
                                @if($this->selectedOwner->pets->count())
                                    <select
                                        wire:model.live="petId"
                                        class="mt-0.5 text-xs bg-transparent border-none text-gray-400 focus:ring-0 p-0 cursor-pointer"
                                    >
                                        <option value="" class="bg-gray-900">Select Pet</option>
                                        @foreach($this->selectedOwner->pets as $pet)
                                            <option value="{{ $pet->id }}" class="bg-gray-900">{{ $pet->name }} ({{ $pet->species }})</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <button wire:click="selectOwner(null)" class="p-1.5 text-gray-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @else
                    <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Customer (optional)</label>
                    <input
                        type="text"
                        wire:model.live="ownerSearch"
                        wire:focus="$set('showOwnerDropdown', true)"
                        placeholder="Search by name or phone..."
                        class="mt-1.5 w-full px-3 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white placeholder-gray-600 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all"
                    />
                    @if($this->showOwnerDropdown && $this->ownerResults->count())
                        <div class="absolute z-10 mt-1 w-full bg-gray-800 border border-white/10 rounded-xl shadow-2xl max-h-48 overflow-y-auto">
                            @foreach($this->ownerResults as $owner)
                                <button
                                    wire:click="selectOwner({{ $owner->id }})"
                                    class="w-full text-left px-3 py-2.5 text-sm hover:bg-emerald-500/10 border-b border-white/5 last:border-0 transition-colors"
                                >
                                    <div class="font-semibold text-white/90">{{ $owner->full_name }}</div>
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
                <div class="flex flex-col items-center justify-center h-full text-gray-700 px-6">
                    <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    </div>
                    <p class="font-semibold text-sm">Cart is empty</p>
                    <p class="text-xs text-gray-700 mt-1">Tap items on the left to add</p>
                </div>
            @else
                <div class="divide-y divide-white/5">
                    @foreach($this->cartItems as $index => $item)
                        <div class="p-3.5 hover:bg-white/[0.02] transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-sm text-white/90 truncate">{{ $item['name'] }}</div>
                                    <div class="text-[11px] text-gray-500 mt-0.5">
                                        @if($item['type'] === 'product')
                                            {{ $item['sku'] }} &middot; ₱{{ number_format($item['price'], 2) }} each
                                        @else
                                            Service &middot; ₱{{ number_format($item['price'], 2) }}
                                        @endif
                                    </div>
                                </div>
                                <div class="text-sm font-bold text-emerald-400 ml-3">
                                    ₱{{ number_format($item['price'] * $item['quantity'], 2) }}
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <button
                                        wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] - 1 }})"
                                        class="w-7 h-7 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-white/10 hover:text-white transition-all"
                                    >
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                    </button>
                                    <input
                                        type="number"
                                        wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                        value="{{ $item['quantity'] }}"
                                        min="1"
                                        @if($item['type'] === 'product') max="{{ $item['stock'] }}" @endif
                                        class="w-14 text-center text-sm font-semibold bg-white/5 border border-white/10 rounded-lg py-1 text-white focus:ring-1 focus:ring-emerald-500/50"
                                    />
                                    <button
                                        wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})"
                                        class="w-7 h-7 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-white/10 hover:text-white transition-all"
                                    >
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                                <button
                                    wire:click="removeItem({{ $index }})"
                                    class="p-1.5 text-gray-600 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all"
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
        <div class="border-t border-white/5 p-4 space-y-3 flex-shrink-0 bg-gray-900/50 backdrop-blur-sm">

            {{-- Discount & Tax --}}
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Discount (₱)</label>
                    <input
                        type="number"
                        wire:model.live="discount"
                        min="0"
                        step="0.01"
                        class="mt-1 w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-sm text-white focus:ring-1 focus:ring-emerald-500/50"
                    />
                </div>
                <div>
                    <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Tax (%)</label>
                    <input
                        type="number"
                        wire:model.live="taxRate"
                        min="0"
                        max="100"
                        step="0.01"
                        class="mt-1 w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-sm text-white focus:ring-1 focus:ring-emerald-500/50"
                    />
                </div>
            </div>

            {{-- Totals --}}
            <div class="space-y-1.5 text-sm">
                <div class="flex justify-between text-gray-400">
                    <span>Subtotal</span>
                    <span>₱{{ number_format($this->subtotal, 2) }}</span>
                </div>
                @if($this->discountAmount > 0)
                    <div class="flex justify-between text-emerald-400">
                        <span>Discount</span>
                        <span>-₱{{ number_format($this->discountAmount, 2) }}</span>
                    </div>
                @endif
                @if($this->taxAmount > 0)
                    <div class="flex justify-between text-gray-400">
                        <span>Tax ({{ number_format($this->taxRate, 1) }}%)</span>
                        <span>₱{{ number_format($this->taxAmount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-bold text-xl border-t border-white/10 pt-2 text-white">
                    <span>Total</span>
                    <span class="text-emerald-400">₱{{ number_format($this->grandTotal, 2) }}</span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-2">
                <button
                    wire:click="clearCart"
                    @disabled(empty($this->cart))
                    class="px-4 py-3 border border-white/10 rounded-xl text-sm font-semibold text-gray-400 hover:bg-white/5 hover:text-white disabled:opacity-30 disabled:cursor-not-allowed transition-all"
                >
                    Clear
                </button>
                <button
                    wire:click="openCheckout"
                    @disabled(empty($this->cart))
                    class="flex-1 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl text-sm font-bold hover:from-emerald-600 hover:to-teal-600 disabled:opacity-30 disabled:cursor-not-allowed shadow-lg shadow-emerald-500/25 transition-all"
                >
                    Checkout &middot; ₱{{ number_format($this->grandTotal, 2) }}
                </button>
            </div>
        </div>
    </div>

    {{-- Checkout Modal --}}
    @if($showCheckout)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" wire:click.self="$set('showCheckout', false)">
            <div class="bg-gray-900 border border-white/10 rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden" x-transition>

                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-white">Complete Payment</h2>
                            <p class="text-emerald-100 text-sm mt-0.5">Total due: ₱{{ number_format($this->grandTotal, 2) }}</p>
                        </div>
                        <button wire:click="$set('showCheckout', false)" class="p-2 text-white/70 hover:text-white hover:bg-white/10 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-5">

                    {{-- Payment Method --}}
                    <div>
                        <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Payment Method</label>
                        <div class="mt-2 grid grid-cols-3 gap-2">
                            @foreach(['cash' => 'Cash', 'gcash' => 'GCash', 'card' => 'Card', 'bank_transfer' => 'Bank', 'other' => 'Other'] as $value => $label)
                                <button
                                    wire:click="$set('paymentMethod', '{{ $value }}')"
                                    class="py-2.5 px-3 rounded-xl border text-sm font-semibold transition-all {{ $paymentMethod === $value ? 'border-emerald-500 bg-emerald-500/10 text-emerald-400 shadow-lg shadow-emerald-500/10' : 'border-white/10 text-gray-500 hover:border-white/20 hover:text-white' }}"
                                >
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Amount Paid --}}
                    <div>
                        <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Amount Paid</label>
                        <div class="mt-1.5 relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-lg">₱</span>
                            <input
                                type="number"
                                wire:model.live="amountPaid"
                                min="0"
                                step="0.01"
                                class="w-full pl-10 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-2xl font-bold text-white focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50"
                            />
                        </div>
                        {{-- Quick amount buttons for cash --}}
                        @if($paymentMethod === 'cash')
                            <div class="flex gap-2 mt-2">
                                <button
                                    wire:click="$set('amountPaid', {{ $this->grandTotal }})"
                                    class="flex-1 py-2 text-xs font-semibold bg-white/5 border border-white/10 rounded-lg text-gray-400 hover:bg-white/10 hover:text-white transition-all"
                                >
                                    Exact Amount
                                </button>
                                @foreach([500, 1000] as $bill)
                                    @if($bill >= $this->grandTotal)
                                        <button
                                            wire:click="$set('amountPaid', {{ $bill }})"
                                            class="flex-1 py-2 text-xs font-semibold bg-white/5 border border-white/10 rounded-lg text-gray-400 hover:bg-white/10 hover:text-white transition-all"
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
                        <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4 text-center">
                            <div class="text-xs text-emerald-400 font-semibold uppercase tracking-wider">Change</div>
                            <div class="text-3xl font-bold text-emerald-400 mt-1">₱{{ number_format($this->change, 2) }}</div>
                        </div>
                    @endif

                    {{-- Order Summary --}}
                    <div class="bg-white/[0.02] border border-white/5 rounded-xl p-4 max-h-40 overflow-y-auto">
                        <div class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Order Summary</div>
                        @foreach($this->cartItems as $item)
                            <div class="flex justify-between text-sm py-1">
                                <span class="text-gray-400">{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                                <span class="font-semibold text-white/80">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 pb-6 flex gap-3">
                    <button
                        wire:click="$set('showCheckout', false)"
                        class="px-5 py-3 border border-white/10 rounded-xl text-sm font-semibold text-gray-400 hover:bg-white/5 hover:text-white transition-all"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="checkout"
                        wire:loading.attr="disabled"
                        class="flex-1 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl text-sm font-bold hover:from-emerald-600 hover:to-teal-600 disabled:opacity-50 shadow-lg shadow-emerald-500/25 transition-all"
                    >
                        <span wire:loading.remove wire:target="checkout">Confirm Payment</span>
                        <span wire:loading wire:target="checkout" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Flash Messages --}}
    @if(session()->has('error'))
        <div class="fixed top-4 right-4 z-50 bg-red-500/90 backdrop-blur-sm text-white px-5 py-3.5 rounded-xl shadow-2xl flex items-center gap-3 border border-red-500/20"
             x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition>
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @if(session()->has('success'))
        <div class="fixed top-4 right-4 z-50 bg-emerald-500/90 backdrop-blur-sm text-white px-5 py-3.5 rounded-xl shadow-2xl flex items-center gap-3 border border-emerald-500/20"
             x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition>
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
</div>
