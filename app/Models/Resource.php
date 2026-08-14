<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Resource
 * Manages digital assets like case studies, manuals, and videos.
 */
class Resource extends Model {

    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'format',
        'file_path',
        'url',
        'thumbnail',
        'resource_type_id',
        'resource_specialty_id',
        'is_active',
        'views',
        'position',
        'featured'
    ];


    /**
     * Get the resource type
     */
    public function resourceType()
    {
        return $this->belongsTo(ResourceType::class);
    }

    /**
     * Get the resource specialty
     */
    public function resourceSpecialty()
    {
        return $this->belongsTo(ResourceSpecialty::class);
    }

    /**
     * Get the image URL attribute for web compatibility
     */
    public function getImageUrlAttribute()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : null;
    }
}
