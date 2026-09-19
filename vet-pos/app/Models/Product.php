<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

#[Fillable(['sku', 'barcode', 'name', 'photo', 'product_category_id', 'description', 'brand', 'unit', 'cost_price', 'selling_price', 'stock', 'reorder_level', 'expiration_date', 'is_active'])]
#[Hidden([])]
class Product extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'expiration_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('stock', '<=', 'reorder_level');
    }

    public function scopeExpiring(Builder $query, int $days = 30): Builder
    {
        return $query->whereNotNull('expiration_date')
                    ->where('expiration_date', '<=', now()->addDays($days))
                    ->where('expiration_date', '>=', now());
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->whereNotNull('expiration_date')
                    ->where('expiration_date', '<', now());
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%")
              ->orWhere('brand', 'like', "%{$search}%")
              ->orWhere('barcode', 'like', "%{$search}%");
        });
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo && \Storage::disk('public')->exists($this->photo)) {
            return \Storage::url($this->photo);
        }

        return null;
    }
}
