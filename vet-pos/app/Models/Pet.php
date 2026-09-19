<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

#[Fillable(['owner_id', 'name', 'photo', 'species', 'breed', 'sex', 'birthdate', 'color', 'weight', 'microchip_number', 'notes'])]
#[Hidden([])]
class Pet extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
            'weight' => 'decimal:2',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
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
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('species', 'like', "%{$search}%")
              ->orWhere('breed', 'like', "%{$search}%");
        });
    }

    public function scopeSpecies(Builder $query, ?string $species): Builder
    {
        if (!$species) {
            return $query;
        }

        return $query->where('species', $species);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo && \Storage::disk('public')->exists($this->photo)) {
            return \Storage::url($this->photo);
        }

        return null;
    }
}
