<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Blog Model
 *
 * Represents blog posts for medical content with comprehensive SEO optimization,
 * engagement tracking, and content management features.
 *
 * @package App\Models
 */
class Blog extends Model {

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'author',
        'content',
        'excerpt',
        'featured_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'blog_category_id',
        'is_active',
        'is_featured',
        'is_pinned',
        'order',
        'view_count',
        'read_time',
        'like_count',
        'comment_count',
        'share_count',
        'published_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_pinned' => 'boolean',
        'order' => 'integer',
        'view_count' => 'integer',
        'read_time' => 'integer',
        'like_count' => 'integer',
        'comment_count' => 'integer',
        'share_count' => 'integer',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the blog category that owns the blog.
     *
     * @return BelongsTo<BlogCategory, Blog>
     */
    public function blogCategory(): BelongsTo {
        return $this->belongsTo(BlogCategory::class);
    }

    /**
     * Get the gallery images for the blog.
     *
     * @return HasMany<BlogGallery, Blog>
     */
    public function gallery(): HasMany {
        return $this->hasMany(BlogGallery::class)->orderBy('position', 'asc');
    }

    /**
     * Get the featured image from gallery.
     *
     * @return HasMany<BlogGallery, Blog>
     */
    public function featuredImage(): HasMany {
        return $this->hasMany(BlogGallery::class)->where('is_featured', true);
    }

    /**
     * Get the main image (featured or first image).
     *
     * @return BlogGallery|null
     */
    public function getMainImage(): ?BlogGallery {
        return $this->featuredImage()->first() ?? $this->gallery()->first();
    }

    /**
     * Scope to get only active blogs.
     *
     * @param Builder<Blog> $query
     * @return Builder<Blog>
     */
    public function scopeActive(Builder $query): Builder {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get featured blogs.
     *
     * @param Builder<Blog> $query
     * @return Builder<Blog>
     */
    public function scopeFeatured(Builder $query): Builder {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to get pinned blogs.
     *
     * @param Builder<Blog> $query
     * @return Builder<Blog>
     */
    public function scopePinned(Builder $query): Builder {
        return $query->where('is_pinned', true);
    }

    /**
     * Scope to get published blogs.
     *
     * @param Builder<Blog> $query
     * @return Builder<Blog>
     */
    public function scopePublished(Builder $query): Builder {
        return $query->where('is_active', true)
                        ->whereNotNull('published_at')
                        ->where('published_at', '<=', now());
    }

    /**
     * Scope to get blogs by author.
     *
     * @param Builder<Blog> $query
     * @param string $author
     * @return Builder<Blog>
     */
    public function scopeByAuthor(Builder $query, string $author): Builder {
        return $query->where('author', $author);
    }

    /**
     * Scope to get blogs ordered by published date.
     *
     * @param Builder<Blog> $query
     * @return Builder<Blog>
     */
    public function scopeLatest(Builder $query): Builder {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Scope to get blogs ordered by popularity.
     *
     * @param Builder<Blog> $query
     * @return Builder<Blog>
     */
    public function scopePopular(Builder $query): Builder {
        return $query->orderBy('view_count', 'desc')
                        ->orderBy('like_count', 'desc');
    }

    /**
     * Get the excerpt with fallback from content.
     *
     * @return string
     */
    public function getExcerptAttribute(): string {
        if ($this->excerpt) {
            return $this->excerpt;
        }

        // Generate excerpt from content (first 150 characters)
        $content = strip_tags($this->content);
        return strlen($content) > 150 ? substr($content, 0, 147) . '...' : $content;
    }

    /**
     * Get the formatted read time.
     *
     * @return string
     */
    public function getFormattedReadTimeAttribute(): string {
        $minutes = $this->read_time ?? $this->estimateReadTime();

        return $minutes . ' min read';
    }

    /**
     * Estimate reading time based on content length.
     *
     * @return int
     */
    public function estimateReadTime(): int {
        $wordCount = str_word_count(strip_tags($this->content));
        return max(1, ceil($wordCount / 200)); // Average 200 words per minute
    }

    /**
     * Get the meta title with fallback.
     *
     * @return string
     */
    public function getMetaTitleAttribute(): string {
        return $this->meta_title ?: $this->title;
    }

    /**
     * Get the meta description with fallback.
     *
     * @return string
     */
    public function getMetaDescriptionAttribute(): string {
        return $this->meta_description ?: $this->excerpt;
    }

    /**
     * Check if the blog is published.
     *
     * @return bool
     */
    public function isPublished(): bool {
        return $this->is_active &&
                $this->published_at &&
                $this->published_at->isPast();
    }

    /**
     * Check if the blog is trending (high engagement).
     *
     * @return bool
     */
    public function isTrending(): bool {
        return $this->view_count > 1000 ||
                $this->like_count > 50 ||
                $this->comment_count > 10;
    }

    /**
     * Get the engagement score.
     *
     * @return int
     */
    public function getEngagementScoreAttribute(): int {
        return ($this->view_count * 1) +
                ($this->like_count * 5) +
                ($this->comment_count * 10) +
                ($this->share_count * 3);
    }
}
