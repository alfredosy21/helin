<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Product Model
 *
 * Represents a commercial product in the Helin Latam catalog with comprehensive
 * support for pricing, inventory, SEO, media management, and dynamic attributes.
 *
 * @package App\Models
 */
class Product extends Model {

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'brand_id',
        'description',
        'clinical_specs',
        'price',
        'currency',
        'stock',
        'unit',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'category_id',
        'is_active',
        'is_featured',
        'is_new',
        'is_on_sale',
        'sale_price',
        'sale_start_date',
        'sale_end_date',
        'view_count',
        'search_count',
        'rating',
        'review_count',
        'published_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'sale_start_date' => 'date',
        'sale_end_date' => 'date',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_on_sale' => 'boolean',
        'view_count' => 'integer',
        'search_count' => 'integer',
        'rating' => 'decimal:2',
        'review_count' => 'integer',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the category that owns the product.
     *
     * @return BelongsTo<Category, Product>
     */
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand that owns the product.
     *
     * @return BelongsTo<Brand, Product>
     */
    public function brand(): BelongsTo {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the media files associated with the product.
     *
     * @return HasMany<ProductMedia, ProductMedia>
     */
    public function media(): HasMany {
        return $this->hasMany(ProductMedia::class)->orderBy('position', 'asc');
    }

    /**
     * Get the main image of the product.
     *
     * @return HasMany<ProductMedia, ProductMedia>
     */
    public function mainImage(): HasMany {
        return $this->hasMany(ProductMedia::class)->where('is_main', true);
    }

    /**
     * Get the images of the product.
     *
     * @return HasMany<ProductMedia, ProductMedia>
     */
    public function images(): HasMany {
        return $this->hasMany(ProductMedia::class)
                        ->where('type', 'image')
                        ->orderBy('position', 'asc');
    }

    /**
     * Get the documents of the product.
     *
     * @return HasMany<ProductMedia, ProductMedia>
     */
    public function documents(): HasMany {
        return $this->hasMany(ProductMedia::class)->where('type', 'document');
    }

    /**
     * Get the attribute values associated with the product.
     *
     * @return BelongsToMany<AttributeValue, Product>
     */
    public function attributeValues(): BelongsToMany {
        return $this->belongsToMany(AttributeValue::class, 'attribute_value_product')
                        ->withPivot(['notes', 'numeric_value', 'text_value'])
                        ->withTimestamps();
    }

    /**
     * Scope to get only active products.
     *
     * @param Builder<Product> $query
     * @return Builder<Product>
     */
    public function scopeActive(Builder $query): Builder {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get featured products.
     *
     * @param Builder<Product> $query
     * @return Builder<Product>
     */
    public function scopeFeatured(Builder $query): Builder {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to get new products.
     *
     * @param Builder<Product> $query
     * @return Builder<Product>
     */
    public function scopeNew(Builder $query): Builder {
        return $query->where('is_new', true);
    }

    /**
     * Scope to get products on sale.
     *
     * @param Builder<Product> $query
     * @return Builder<Product>
     */
    public function scopeOnSale(Builder $query): Builder {
        return $query->where('is_on_sale', true)
                        ->where('sale_start_date', '<=', now())
                        ->where(function ($query) {
                            $query->whereNull('sale_end_date')
                            ->orWhere('sale_end_date', '>=', now());
                        });
    }

    /**
     * Scope to get published products.
     *
     * @param Builder<Product> $query
     * @return Builder<Product>
     */
    public function scopePublished(Builder $query): Builder {
        return $query->where('is_active', true)
                        ->whereNotNull('published_at')
                        ->where('published_at', '<=', now());
    }

    /**
     * Check if the product is currently on sale.
     *
     * @return bool
     */
    public function isCurrentlyOnSale(): bool {
        if (!$this->is_on_sale || !$this->sale_price) {
            return false;
        }

        $now = now();

        if ($this->sale_start_date && $this->sale_start_date > $now) {
            return false;
        }

        if ($this->sale_end_date && $this->sale_end_date < $now) {
            return false;
        }

        return true;
    }

    /**
     * Get the current price (sale price if on sale, regular price otherwise).
     *
     * @return float|null
     */
    public function getCurrentPriceAttribute(): ?float {
        return $this->isCurrentlyOnSale() ? $this->sale_price : $this->price;
    }

    /**
     * Get the main image URL with fallback.
     *
     * @return string
     */
    public function getMainImageUrlAttribute(): string {
        $mainImage = $this->mainImage()->first();

        if ($mainImage) {
            return asset('storage/' . $mainImage->file_path);
        }

        return asset('images/default-product.png');
    }

    /**
     * Get the formatted price.
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): string {
        return number_format($this->price, 2) . ' ' . $this->currency;
    }

    /**
     * Get the formatted sale price.
     *
     * @return string|null
     */
    public function getFormattedSalePriceAttribute(): ?string {
        return $this->sale_price ? number_format($this->sale_price, 2) . ' ' . $this->currency : null;
    }

    /**
     * Increment the view count.
     *
     * @return void
     */
    public function incrementViewCount(): void {
        $this->increment('view_count');
    }

    /**
     * Increment the search count.
     *
     * @return void
     */
    public function incrementSearchCount(): void {
        $this->increment('search_count');
    }

    /**
     * Update the rating based on reviews.
     *
     * @param float $newRating
     * @param int $newReviewCount
     * @return void
     */
    public function updateRating(float $newRating, int $newReviewCount): void {
        $this->update([
            'rating' => $newRating,
            'review_count' => $newReviewCount
        ]);
    }
}
