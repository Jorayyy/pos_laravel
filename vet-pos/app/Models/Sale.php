<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

#[Fillable(['invoice_number', 'user_id', 'owner_id', 'pet_id', 'subtotal', 'discount', 'tax', 'total', 'payment_method', 'amount_paid', 'change_amount', 'status', 'notes'])]
#[Hidden([])]
class Sale extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'change_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeDateRange(Builder $query, ?string $from, ?string $to): Builder
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query;
    }

    public function scopePaymentMethod(Builder $query, ?string $method): Builder
    {
        if (!$method) {
            return $query;
        }

        return $query->where('payment_method', $method);
    }
}
