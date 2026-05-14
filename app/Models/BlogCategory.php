<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * BlogCategory Model
 *
 * Handles categories for blog posts with visual customization and SEO optimization.
 * Supports hierarchical organization and engagement tracking.
 *
 * @package App\Models
 */
class BlogCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'image',
        'is_active',
        'order'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the blogs for the blog category.
     *
     * @return HasMany<Blog, BlogCategory>
     */
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    /**
     * Get only active blogs for the category.
     *
     * @return HasMany<Blog, BlogCategory>
     */
    public function activeBlogs(): HasMany
    {
        return $this->hasMany(Blog::class)->where('is_active', true);
    }

    /**
     * Get published blogs for the category.
     *
     * @return HasMany<Blog, BlogCategory>
     */
    public function publishedBlogs(): HasMany
    {
        return $this->hasMany(Blog::class)
                    ->where('is_active', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope to get only active categories.
     *
     * @param Builder<BlogCategory> $query
     * @return Builder<BlogCategory>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get categories ordered by position and name.
     *
     * @param Builder<BlogCategory> $query
     * @return Builder<BlogCategory>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order', 'asc')
                    ->orderBy('name', 'asc');
    }

    /**
     * Get the color with fallback.
     *
     * @return string
     */
    public function getColorAttribute(): string
    {
        return $this->color ?? '#3B82F6';
    }

    /**
     * Get the blog count for the category.
     *
     * @return int
     */
    public function getBlogCountAttribute(): int
    {
        return $this->blogs()->count();
    }

    /**
     * Get the active blog count for the category.
     *
     * @return int
     */
    public function getActiveBlogCountAttribute(): int
    {
        return $this->activeBlogs()->count();
    }

    /**
     * Get the published blog count for the category.
     *
     * @return int
     */
    public function getPublishedBlogCountAttribute(): int
    {
        return $this->publishedBlogs()->count();
    }

    /**
     * Update the blog count.
     *
     * @return void
     */
    public function updateBlogCount(): void
    {
        $this->update(['blog_count' => $this->blogs()->count()]);
    }

    /**
     * Check if the category has blogs.
     *
     * @return bool
     */
    public function hasBlogs(): bool
    {
        return $this->blogs()->exists();
    }

    /**
     * Get the style attributes for UI.
     *
     * @return array<string, string>
     */
    public function getStyleAttributesAttribute(): array
    {
        return [
            'color' => $this->color,
            'background-color' => $this->color . '10',
            'border-color' => $this->color . '30'
        ];
    }
}
