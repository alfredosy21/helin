<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * ProductMedia Model
 *
 * Represents media files (images, documents, videos) associated with products.
 * Supports SEO optimization, thumbnails, and organized galleries.
 *
 * @package App\Models
 */
class ProductMedia extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'product_id',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'type',
        'alt_text',
        'title',
        'label',
        'is_main',
        'is_featured',
        'position',
        'thumbnail',
        'description'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_main' => 'boolean',
        'is_featured' => 'boolean',
        'position' => 'integer',
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the product that owns the media.
     *
     * @return BelongsTo<Product, ProductMedia>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the file URL.
     *
     * @return string
     */
    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Get the thumbnail URL with fallback to main file.
     *
     * @return string
     */
    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail
            ? asset('storage/' . $this->thumbnail)
            : $this->file_url;
    }

    /**
     * Get the formatted file size.
     *
     * @return string
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' bytes';
    }

    /**
     * Get the file type label.
     *
     * @return string
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'image' => 'Image',
            'document' => 'Document',
            'video' => 'Video',
            default => ucfirst($this->type)
        };
    }

    /**
     * Get the file extension.
     *
     * @return string
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    /**
     * Check if the media is an image.
     *
     * @return bool
     */
    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    /**
     * Check if the media is a document.
     *
     * @return bool
     */
    public function isDocument(): bool
    {
        return $this->type === 'document';
    }

    /**
     * Check if the media is a video.
     *
     * @return bool
     */
    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    /**
     * Check if the media can be displayed inline.
     *
     * @return bool
     */
    public function canBeDisplayedInline(): bool
    {
        return $this->isImage() || $this->isVideo();
    }

    /**
     * Get the appropriate icon for the file type.
     *
     * @return string
     */
    public function getIconAttribute(): string
    {
        if ($this->isImage()) {
            return 'image';
        }

        if ($this->isVideo()) {
            return 'video';
        }

        $extension = strtolower($this->extension);

        return match($extension) {
            'pdf' => 'file-text',
            'doc', 'docx' => 'file-text',
            'xls', 'xlsx' => 'file-spreadsheet',
            'ppt', 'pptx' => 'file-presentation',
            default => 'file'
        };
    }
}
