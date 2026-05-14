<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * BlogGallery Model
 * 
 * Represents gallery images for blog posts with SEO optimization and organization features.
 * Supports thumbnails, alt text, and positioning for better user experience.
 * 
 * @package App\Models
 */
class BlogGallery extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'blog_id',
        'title',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'alt_text',
        'description',
        'thumbnail',
        'position',
        'is_featured',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'position' => 'integer',
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the blog that owns the gallery image.
     * 
     * @return BelongsTo<Blog, BlogGallery>
     */
    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
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
        return str_starts_with($this->mime_type, 'image/');
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
        
        $extension = strtolower($this->extension);
        
        return match($extension) {
            'pdf' => 'file-text',
            'doc', 'docx' => 'file-text',
            'xls', 'xlsx' => 'file-spreadsheet',
            'ppt', 'pptx' => 'file-presentation',
            'jpg', 'jpeg', 'png', 'gif', 'svg' => 'image',
            default => 'file'
        };
    }

    /**
     * Get the image dimensions if available (requires intervention package).
     * 
     * @return array<string, int>|null
     */
    public function getImageDimensions(): ?array
    {
        // This would require intervention/image package
        return null;
    }

    /**
     * Check if the file can be displayed inline.
     * 
     * @return bool
     */
    public function canBeDisplayedInline(): bool
    {
        return $this->isImage();
    }
}
