<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

#[Fillable(['full_name', 'photo', 'contact_number', 'email', 'address', 'notes'])]
#[Hidden([])]
class Owner extends Model
{
    use HasFactory;

    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('full_name', 'like', "%{$search}%")
              ->orWhere('contact_number', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
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
