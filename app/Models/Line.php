<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Line
 *
 * Represents the top-level classification (Line of Business/Product)
 * within the Helin Medical catalog.
 *
 * @package App\Models
 */
class Line extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active'  => 'boolean',
        'order'      => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: A line typically contains multiple products.
     *
     * @return HasMany<Product>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relationship: Get active products for the line.
     *
     * @return HasMany<Product>
     */
    public function activeProducts(): HasMany
    {
        return $this->products()->where('is_active', true);
    }

    /**
     * Scope to get only active lines.
     *
     * @param Builder<Line> $query
     * @return Builder<Line>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get lines ordered by order and name.
     *
     * @param Builder<Line> $query
     * @return Builder<Line>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order', 'asc')
                    ->orderBy('name', 'asc');
    }

    /**
     * Get the product count for the line.
     *
     * @return int
     */
    public function getProductCountAttribute(): int
    {
        return $this->products()->count();
    }

    /**
     * Get the active product count for the line.
     *
     * @return int
     */
    public function getActiveProductCountAttribute(): int
    {
        return $this->activeProducts()->count();
    }

    /**
     * Check if the line has products.
     *
     * @return bool
     */
    public function hasProducts(): bool
    {
        return $this->products()->exists();
    }

    /**
     * Check if the line has active products.
     *
     * @return bool
     */
    public function hasActiveProducts(): bool
    {
        return $this->activeProducts()->exists();
    }
}
