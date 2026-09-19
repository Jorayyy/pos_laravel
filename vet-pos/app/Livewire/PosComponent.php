<?php

namespace App\Livewire;

use App\Models\AuditLog;
use App\Models\InventoryMovement;
use App\Models\Owner;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class PosComponent extends Component
{
    public string $search = '';
    public string $categoryFilter = '';
    public array $cart = [];
    public ?int $ownerId = null;
    public ?int $petId = null;
    public float $discount = 0;
    public float $taxRate = 0;
    public string $paymentMethod = 'cash';
    public float $amountPaid = 0;
    public bool $showCheckout = false;
    public ?string $activeTab = 'products';

    public string $ownerSearch = '';
    public bool $showOwnerDropdown = false;

    protected function rules(): array
    {
        return [
            'ownerId' => 'nullable|exists:owners,id',
            'petId' => 'nullable|exists:pets,id',
            'discount' => 'min:0',
            'taxRate' => 'min:0|max:100',
            'paymentMethod' => 'in:cash,gcash,card,bank_transfer,other',
            'amountPaid' => 'min:0',
        ];
    }

    #[Computed]
    public function products()
    {
        return Product::query()
            ->active()
            ->search($this->search)
            ->when($this->categoryFilter, fn ($q) => $q->where('product_category_id', $this->categoryFilter))
            ->with('category')
            ->orderBy('name')
            ->paginate(24);
    }

    #[Computed]
    public function services()
    {
        return Service::query()
            ->active()
            ->search($this->search)
            ->with('category')
            ->orderBy('name')
            ->paginate(24);
    }

    #[Computed]
    public function cartItems(): array
    {
        return $this->cart;
    }

    #[Computed]
    public function subtotal(): float
    {
        return (float) collect($this->cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    #[Computed]
    public function discountAmount(): float
    {
        return min($this->discount, $this->subtotal);
    }

    #[Computed]
    public function taxAmount(): float
    {
        return round(($this->subtotal - $this->discountAmount) * ($this->taxRate / 100), 2);
    }

    #[Computed]
    public function grandTotal(): float
    {
        return round($this->subtotal - $this->discountAmount + $this->taxAmount, 2);
    }

    #[Computed]
    public function change(): float
    {
        return max(0, $this->amountPaid - $this->grandTotal);
    }

    #[Computed]
    public function productCategories()
    {
        return ProductCategory::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function selectedOwner()
    {
        return $this->ownerId ? Owner::with('pets')->find($this->ownerId) : null;
    }

    #[Computed]
    public function ownerResults()
    {
        if (strlen($this->ownerSearch) < 2) {
            return collect();
        }

        return Owner::search($this->ownerSearch)->limit(10)->get();
    }

    public function updatedCategoryFilter(): void
    {
        $this->dispatch('pageChanged');
    }

    public function updatedSearch(): void
    {
        $this->dispatch('pageChanged');
    }

    public function addToCart(string $type, int $id): void
    {
        $existingIndex = collect($this->cart)->search(fn ($item) => $item['type'] === $type && $item['id'] === $id);

        if ($existingIndex !== false) {
            $this->updateQuantity($existingIndex, $this->cart[$existingIndex]['quantity'] + 1);
            return;
        }

        if ($type === 'product') {
            $product = Product::findOrFail($id);

            if ($product->stock <= 0) {
                session()->flash('error', "{$product->name} is out of stock.");
                return;
            }

            $this->cart[] = [
                'type' => 'product',
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => (float) $product->selling_price,
                'quantity' => 1,
                'stock' => $product->stock,
                'unit' => $product->unit,
            ];
        } else {
            $service = Service::findOrFail($id);

            $this->cart[] = [
                'type' => 'service',
                'id' => $service->id,
                'name' => $service->name,
                'sku' => null,
                'price' => (float) $service->price,
                'quantity' => 1,
                'stock' => null,
                'unit' => 'svc',
            ];
        }
    }

    public function updateQuantity(int $index, int $qty): void
    {
        if (!isset($this->cart[$index])) {
            return;
        }

        if ($qty <= 0) {
            $this->removeItem($index);
            return;
        }

        $item = &$this->cart[$index];

        if ($item['type'] === 'product') {
            $product = Product::find($item['id']);

            if (!$product || $product->stock < $qty) {
                session()->flash('error', 'Insufficient stock for ' . $item['name'] . '. Available: ' . ($item['stock'] ?? 0));
                $item['quantity'] = $item['stock'] ?? 0;

                if ($item['quantity'] <= 0) {
                    $this->removeItem($index);
                }
                return;
            }

            $item['stock'] = $product->stock;
        }

        $item['quantity'] = $qty;
    }

    public function removeItem(int $index): void
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->discount = 0;
        $this->amountPaid = 0;
        $this->ownerId = null;
        $this->petId = null;
    }

    public function selectOwner(?int $ownerId): void
    {
        $this->ownerId = $ownerId;
        $this->petId = null;
        $this->ownerSearch = '';
        $this->showOwnerDropdown = false;
    }

    public function selectPet(?int $petId): void
    {
        $this->petId = $petId;
    }

    public function openCheckout(): void
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Cart is empty.');
            return;
        }

        $this->amountPaid = $this->grandTotal;
        $this->showCheckout = true;
    }

    public function checkout(): ?string
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Cart is empty.');
            return null;
        }

        $this->validate();

        $grandTotal = $this->grandTotal;

        if ($this->amountPaid < $grandTotal && $this->paymentMethod === 'cash') {
            session()->flash('error', 'Insufficient payment amount.');
            return null;
        }

        $stockIssues = [];
        foreach ($this->cart as $item) {
            if ($item['type'] === 'product') {
                $product = Product::find($item['id']);
                if (!$product) {
                    $stockIssues[] = "{$item['name']} no longer exists.";
                    continue;
                }
                if ($product->stock < $item['quantity']) {
                    $stockIssues[] = "{$item['name']}: need {$item['quantity']}, only {$product->stock} available.";
                }
            }
        }

        if (!empty($stockIssues)) {
            session()->flash('error', 'Stock issues: ' . implode(' ', $stockIssues));
            return null;
        }

        $subtotal = $this->subtotal;
        $discountAmount = $this->discountAmount;
        $taxAmount = $this->taxAmount;
        $change = $this->change;

        $sale = DB::transaction(function () use ($subtotal, $discountAmount, $taxAmount, $grandTotal, $change) {
            $sale = Sale::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'user_id' => Auth::id(),
                'owner_id' => $this->ownerId,
                'pet_id' => $this->petId,
                'subtotal' => $subtotal,
                'discount' => $discountAmount,
                'tax' => $taxAmount,
                'total' => $grandTotal,
                'payment_method' => $this->paymentMethod,
                'amount_paid' => $this->amountPaid,
                'change_amount' => $change,
                'status' => 'completed',
            ]);

            foreach ($this->cart as $item) {
                $itemTotal = round($item['price'] * $item['quantity'], 2);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['type'] === 'product' ? $item['id'] : null,
                    'service_id' => $item['type'] === 'service' ? $item['id'] : null,
                    'type' => $item['type'],
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'discount' => 0,
                    'total' => $itemTotal,
                ]);

                if ($item['type'] === 'product') {
                    $product = Product::find($item['id']);
                    $previousStock = $product->stock;
                    $newStock = $previousStock - $item['quantity'];

                    $product->decrement('stock', $item['quantity']);

                    InventoryMovement::create([
                        'product_id' => $product->id,
                        'type' => 'sale',
                        'quantity' => -$item['quantity'],
                        'previous_stock' => $previousStock,
                        'new_stock' => $newStock,
                        'reason' => "Sale #{$sale->invoice_number}",
                        'user_id' => Auth::id(),
                        'sale_id' => $sale->id,
                    ]);
                }
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'event' => 'sale_created',
                'auditable_type' => Sale::class,
                'auditable_id' => $sale->id,
                'new_values' => [
                    'invoice_number' => $sale->invoice_number,
                    'total' => $grandTotal,
                    'payment_method' => $this->paymentMethod,
                    'items_count' => count($this->cart),
                ],
                'ip_address' => request()->ip(),
            ]);

            return $sale;
        });

        $receiptUrl = route('receipt', $sale->id);

        $this->cart = [];
        $this->discount = 0;
        $this->taxRate = 0;
        $this->amountPaid = 0;
        $this->ownerId = null;
        $this->petId = null;
        $this->showCheckout = false;
        $this->paymentMethod = 'cash';

        session()->flash('success', "Sale completed! Invoice: {$sale->invoice_number}");

        return $receiptUrl;
    }

    private function generateInvoiceNumber(): string
    {
        $date = now()->format('Ymd');
        $lastSale = Sale::where('invoice_number', 'like', "VET-{$date}-%")
            ->orderByDesc('invoice_number')
            ->first();

        if ($lastSale) {
            $lastSequence = (int) substr($lastSale->invoice_number, -5);
            $newSequence = str_pad($lastSequence + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newSequence = '00001';
        }

        return "VET-{$date}-{$newSequence}";
    }

    public function render()
    {
        return view('livewire.pos-component');
    }
}
